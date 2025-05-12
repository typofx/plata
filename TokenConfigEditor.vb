Imports System.Net.Http
Imports System.IO
Imports System.Text
Imports System.Text.RegularExpressions
Imports System.Text.Json
Imports System.Linq
Imports System.Configuration ' Para trabalhar com arquivos INI

Public Class TokenConfigEditor
    ' ==============================================
    ' CONSTANTES COM OS NOMES DAS VARIÁVEIS JS QUE SERÃO EDITADAS
    ' ==============================================
    Private Const JS_NAME_VAR As String = "const name"
    Private Const JS_SYMBOL_VAR As String = "const symbol"
    Private Const JS_DECIMALS_VAR As String = "const decimals"
    Private Const JS_ADDRESS_VAR As String = "const address"

    ' ==============================================
    ' VALORES PADRÃO
    ' ==============================================

    Private ReadOnly _settingsIniPath As String

    Private ReadOnly DEFAULT_NAME As String = "Wrapped Polygon"
    Private ReadOnly DEFAULT_SYMBOL As String = "WPOL"
    Private ReadOnly DEFAULT_DECIMALS As Integer = 18
    Private ReadOnly DEFAULT_ADDRESS As String = "0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270"
    Private ReadOnly JSON_URL As String = "https://typofx.ie/plataforma/panel/asset/assets_data.json"

    ' Controles da UI
    Public Property SymbolTextBox As TextBox
    Public Property DecimalsTextBox As TextBox
    Public Property AddressTextBox As TextBox
    Public Property SaveButton As Button
    Public Property ResetButton As Button
    Public Property TokensComboBox As ComboBox
    Public Property LoadTokensButton As Button

    ' Variável para armazenar o nome internamente
    Private _tokenName As String = DEFAULT_NAME

    ' Classe para representar os dados do JSON
    Public Class Asset
        Public Property id As Integer
        Public Property name As String
        Public Property icon As String
        Public Property ticker As String
        Public Property contract As String
        Public Property decimals As Integer
        Public Property network As String
        Public Property price As Decimal
        Public Property price_formatted As String
    End Class

    Public Sub New(workingDirectory As String)
        ' Inicializa os caminhos dos arquivos


        ' Caminho para o settings.ini (um nível acima da pasta atual)
        Dim parentDirectory = Directory.GetParent(workingDirectory).FullName
        _settingsIniPath = Path.Combine(parentDirectory, "settings.ini")

        ' Verifica e cria os arquivos temporários se necessário

    End Sub

    ' Verifica e cria os arquivos temporários se necessário


    ' Conteúdo padrão do arquivo JS
    Private Function GetDefaultJsContent() As String
        Return $"{JS_NAME_VAR} = '{DEFAULT_NAME}';" & vbCrLf &
               $"{JS_SYMBOL_VAR} = '{DEFAULT_SYMBOL}';" & vbCrLf &
               $"{JS_DECIMALS_VAR} = {DEFAULT_DECIMALS};" & vbCrLf &
               $"{JS_ADDRESS_VAR} = '{DEFAULT_ADDRESS}';" & vbCrLf &
               "// Outras configurações"
    End Function

    Public Sub Initialize()

        AddHandler ResetButton.Click, AddressOf ResetToDefaults
        AddHandler LoadTokensButton.Click, AddressOf LoadTokensFromAPI
        AddHandler TokensComboBox.SelectedIndexChanged, AddressOf TokenSelected

        ' Carrega as configurações do arquivo INI
        LoadSettingsFromIni()
    End Sub

    ' Carrega as configurações do arquivo settings.ini
    Private Sub LoadSettingsFromIni()
        If Not File.Exists(_settingsIniPath) Then
            ' Se o arquivo não existe, usa os valores padrão
            ResetToDefaults(Nothing, EventArgs.Empty)
            Return
        End If

        Try
            ' Lê todo o conteúdo do arquivo INI
            Dim iniContent = File.ReadAllText(_settingsIniPath)

            ' Extrai apenas a seção [Token0] usando expressão regular
            Dim tokenSectionMatch = Regex.Match(iniContent,
            "\[Token0\](.*?)(?=\[|$)", RegexOptions.Singleline)

            If tokenSectionMatch.Success Then
                Dim tokenSection = tokenSectionMatch.Groups(1).Value

                ' Extrai os valores individuais
                Dim nameMatch = Regex.Match(tokenSection, "TOKEN0_NAME\s*=\s*""([^""]*)""")
                If nameMatch.Success Then _tokenName = nameMatch.Groups(1).Value

                Dim symbolMatch = Regex.Match(tokenSection, "TOKEN0_SYMBOL\s*=\s*""([^""]*)""")
                If symbolMatch.Success Then SymbolTextBox.Text = symbolMatch.Groups(1).Value

                Dim decimalsMatch = Regex.Match(tokenSection, "TOKEN0_DECIMALS\s*=\s*(\d+)")
                If decimalsMatch.Success Then DecimalsTextBox.Text = decimalsMatch.Groups(1).Value

                Dim addressMatch = Regex.Match(tokenSection, "TOKEN0_ADDRESS\s*=\s*""([^""]*)""")
                If addressMatch.Success Then AddressTextBox.Text = addressMatch.Groups(1).Value
            End If

        Catch ex As Exception
            MessageBox.Show($"Erro ao ler o arquivo settings.ini: {ex.Message}", "Erro",
                  MessageBoxButtons.OK, MessageBoxIcon.Error)
            ResetToDefaults(Nothing, EventArgs.Empty)
        End Try
    End Sub

    ' Salva as configurações no arquivo settings.ini
    ' Salva as configurações no arquivo settings.ini preservando outras seções e a posição original
    Private Sub SaveSettingsToIni()
        Try
            Dim existingContent As String = ""
            Dim newContent As New StringBuilder()
            Dim token0SectionUpdated As Boolean = False

            ' Se o arquivo existe, lê o conteúdo atual
            If File.Exists(_settingsIniPath) Then
                existingContent = File.ReadAllText(_settingsIniPath)

                ' Divide o conteúdo em linhas
                Dim lines = existingContent.Split(New String() {Environment.NewLine, vbCrLf, vbLf}, StringSplitOptions.None)
                Dim inToken0Section As Boolean = False

                For Each line In lines
                    If line.Trim().StartsWith("[") AndAlso line.Trim().EndsWith("]") Then
                        ' Encontrou uma nova seção
                        If inToken0Section Then
                            ' Estava na seção Token0, agora saiu - adiciona os novos valores
                            newContent.AppendLine($"TOKEN0_NAME = ""{_tokenName}""")
                            newContent.AppendLine($"TOKEN0_SYMBOL = ""{SymbolTextBox.Text.Trim()}""")
                            newContent.AppendLine($"TOKEN0_DECIMALS = {DecimalsTextBox.Text.Trim()}")
                            newContent.AppendLine($"TOKEN0_ADDRESS = ""{AddressTextBox.Text.Trim()}""")
                            token0SectionUpdated = True
                            inToken0Section = False
                        End If

                        ' Verifica se é a seção Token0
                        If line.Trim().Equals("[Token0]", StringComparison.OrdinalIgnoreCase) Then
                            inToken0Section = True
                            token0SectionUpdated = True
                        End If

                        newContent.AppendLine(line)
                    ElseIf inToken0Section Then
                        ' Dentro da seção Token0, ignora as linhas antigas (serão substituídas)
                        Continue For
                    Else
                        ' Fora de qualquer seção ou em outras seções
                        newContent.AppendLine(line)
                    End If
                Next

                ' Se estava na seção Token0 no final do arquivo
                If inToken0Section AndAlso token0SectionUpdated Then
                    newContent.AppendLine($"TOKEN0_NAME = ""{_tokenName}""")
                    newContent.AppendLine($"TOKEN0_SYMBOL = ""{SymbolTextBox.Text.Trim()}""")
                    newContent.AppendLine($"TOKEN0_DECIMALS = {DecimalsTextBox.Text.Trim()}")
                    newContent.AppendLine($"TOKEN0_ADDRESS = ""{AddressTextBox.Text.Trim()}""")
                End If
            End If

            ' Se não encontrou a seção Token0, adiciona no final
            If Not token0SectionUpdated Then
                newContent.AppendLine()
                newContent.AppendLine("[Token0]")
                newContent.AppendLine($"TOKEN0_NAME = ""{_tokenName}""")
                newContent.AppendLine($"TOKEN0_SYMBOL = ""{SymbolTextBox.Text.Trim()}""")
                newContent.AppendLine($"TOKEN0_DECIMALS = {DecimalsTextBox.Text.Trim()}")
                newContent.AppendLine($"TOKEN0_ADDRESS = ""{AddressTextBox.Text.Trim()}""")
            End If

            ' Escreve no arquivo
            File.WriteAllText(_settingsIniPath, newContent.ToString().Trim(), Encoding.UTF8)

        Catch ex As Exception
            MessageBox.Show($"Erro ao salvar no arquivo settings.ini: {ex.Message}", "Erro",
                  MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Async Sub LoadTokensFromAPI(sender As Object, e As EventArgs)
        Try
            Using client As HttpClient = New HttpClient()
                ' Fazer a requisição HTTP
                Dim response = Await client.GetAsync(JSON_URL)
                response.EnsureSuccessStatusCode()

                ' Ler o conteúdo como string
                Dim json As String = Await response.Content.ReadAsStringAsync()

                ' Converter o JSON para uma lista de objetos
                Dim assets = JsonSerializer.Deserialize(Of List(Of Asset))(json)

                ' Filtrar apenas os tokens da rede Polygon
                Dim polygonAssets = assets.Where(Function(a) a.network = "polygon").ToList()

                ' Obter o endereço atual ANTES de carregar os itens
                Dim currentAddress = AddressTextBox.Text.Trim()

                ' Configurar o ComboBox
                TokensComboBox.BeginUpdate()

                ' Limpar e adicionar itens manualmente (sem DataSource)
                TokensComboBox.Items.Clear()

                ' Adicionar item vazio como primeira opção
                TokensComboBox.Items.Add("Selecione um token...")

                ' Variável para armazenar o índice do item atual (se existir)
                Dim currentIndex As Integer = -1

                ' Adicionar todos os tokens
                For Each asset In polygonAssets
                    TokensComboBox.Items.Add(asset)

                    ' Verificar se é o token atual
                    If asset.contract.Equals(currentAddress, StringComparison.OrdinalIgnoreCase) Then
                        currentIndex = TokensComboBox.Items.Count - 1 ' Pega o último índice adicionado
                    End If
                Next

                ' Selecionar o item atual se encontrado
                If currentIndex > 0 Then
                    TokensComboBox.SelectedIndex = currentIndex
                Else
                    TokensComboBox.SelectedIndex = 0 ' Seleciona o item vazio
                End If

                TokensComboBox.EndUpdate()

                ' Configurar DisplayMember e ValueMember APÓS adicionar os itens
                TokensComboBox.DisplayMember = "name"
                TokensComboBox.ValueMember = "contract"
            End Using
        Catch ex As Exception
            MessageBox.Show("Erro ao carregar tokens: " & ex.Message, "Erro",
                MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub TokenSelected(sender As Object, e As EventArgs)
        If TokensComboBox.SelectedItem IsNot Nothing AndAlso TypeOf TokensComboBox.SelectedItem Is Asset Then
            Dim selectedAsset = DirectCast(TokensComboBox.SelectedItem, Asset)

            ' Atualizar o nome internamente
            _tokenName = selectedAsset.name

            ' Preencher os campos com os dados do token selecionado
            SymbolTextBox.Text = selectedAsset.ticker
            DecimalsTextBox.Text = selectedAsset.decimals.ToString()
            AddressTextBox.Text = selectedAsset.contract
        End If
    End Sub





    Private Sub ResetToDefaults(sender As Object, e As EventArgs)
        _tokenName = DEFAULT_NAME
        SymbolTextBox.Text = DEFAULT_SYMBOL
        DecimalsTextBox.Text = DEFAULT_DECIMALS.ToString()
        AddressTextBox.Text = DEFAULT_ADDRESS
    End Sub

    Public Sub Cleanup()

        RemoveHandler ResetButton.Click, AddressOf ResetToDefaults
        RemoveHandler LoadTokensButton.Click, AddressOf LoadTokensFromAPI
        RemoveHandler TokensComboBox.SelectedIndexChanged, AddressOf TokenSelected
    End Sub
End Class