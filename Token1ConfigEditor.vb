Imports System.Net.Http
Imports System.IO
Imports System.Text
Imports System.Text.RegularExpressions
Imports System.Text.Json
Imports System.Linq
Imports System.Configuration ' Para trabalhar com arquivos INI

Public Class Token1ConfigEditor
    Private ReadOnly _filePaths As String()
    Private ReadOnly DEFAULT_NAME As String = "Plata"
    Private ReadOnly DEFAULT_SYMBOL As String = "PLT"
    Private ReadOnly DEFAULT_DECIMALS As Integer = 4
    Private ReadOnly DEFAULT_ADDRESS As String = "0xc298812164bd558268f51cc6e3b8b5daaf0b6341"
    Private ReadOnly JSON_URL As String = "https://typofx.ie/plataforma/panel/asset/assets_data.json"
    Private ReadOnly _settingsIniPath As String

    ' Controles da UI para o token1
    Public Property txtToken1Symbol As TextBox
    Public Property txtToken1Decimals As TextBox
    Public Property txtToken1Address As TextBox
    Public Property btnSaveToken1Config As Button
    Public Property btnResetToken1Config As Button
    Public Property cmbToken1List As ComboBox
    Public Property btnLoadToken1List As Button

    ' Variável para armazenar o nome do token internamente
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
        ' Inicializa o array com os caminhos dos arquivos
        _filePaths = {
            Path.Combine(workingDirectory, "aproveToken1d.js"),
            Path.Combine(workingDirectory, "sellPLTd.js"),
            Path.Combine(workingDirectory, "buyPLTd.js")
        }

        ' Caminho para o settings.ini (um nível acima da pasta atual)
        Dim parentDirectory = Directory.GetParent(workingDirectory).FullName
        _settingsIniPath = Path.Combine(parentDirectory, "settings.ini")
    End Sub

    Public Sub Initialize()
        AddHandler btnSaveToken1Config.Click, AddressOf SaveConfiguration
        AddHandler btnResetToken1Config.Click, AddressOf ResetToDefaults
        AddHandler btnLoadToken1List.Click, AddressOf LoadTokensFromAPI
        AddHandler cmbToken1List.SelectedIndexChanged, AddressOf TokenSelected

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

            ' Extrai apenas a seção [Token1] usando expressão regular
            Dim tokenSectionMatch = Regex.Match(iniContent,
            "\[Token1\](.*?)(?=\[|$)", RegexOptions.Singleline)

            If tokenSectionMatch.Success Then
                Dim tokenSection = tokenSectionMatch.Groups(1).Value

                ' Extrai os valores individuais
                Dim nameMatch = Regex.Match(tokenSection, "TOKEN1_NAME\s*=\s*""([^""]*)""")
                If nameMatch.Success Then _tokenName = nameMatch.Groups(1).Value

                Dim symbolMatch = Regex.Match(tokenSection, "TOKEN1_SYMBOL\s*=\s*""([^""]*)""")
                If symbolMatch.Success Then txtToken1Symbol.Text = symbolMatch.Groups(1).Value

                Dim decimalsMatch = Regex.Match(tokenSection, "TOKEN1_DECIMALS\s*=\s*(\d+)")
                If decimalsMatch.Success Then txtToken1Decimals.Text = decimalsMatch.Groups(1).Value

                Dim addressMatch = Regex.Match(tokenSection, "TOKEN1_ADDRESS\s*=\s*""([^""]*)""")
                If addressMatch.Success Then txtToken1Address.Text = addressMatch.Groups(1).Value
            End If

        Catch ex As Exception
            MessageBox.Show($"Erro ao ler o arquivo settings.ini: {ex.Message}", "Erro",
                  MessageBoxButtons.OK, MessageBoxIcon.Error)
            ResetToDefaults(Nothing, EventArgs.Empty)
        End Try
    End Sub

    ' Salva as configurações no arquivo settings.ini
    Private Sub SaveSettingsToIni()
        Try
            Dim existingContent As String = ""
            Dim newContent As New StringBuilder()
            Dim token1SectionUpdated As Boolean = False

            ' Se o arquivo existe, lê o conteúdo atual
            If File.Exists(_settingsIniPath) Then
                existingContent = File.ReadAllText(_settingsIniPath)

                ' Divide o conteúdo em linhas
                Dim lines = existingContent.Split(New String() {Environment.NewLine, vbCrLf, vbLf}, StringSplitOptions.None)
                Dim inToken1Section As Boolean = False

                For Each line In lines
                    If line.Trim().StartsWith("[") AndAlso line.Trim().EndsWith("]") Then
                        ' Encontrou uma nova seção
                        If inToken1Section Then
                            ' Estava na seção Token1, agora saiu - adiciona os novos valores
                            newContent.AppendLine($"TOKEN1_NAME = ""{_tokenName}""")
                            newContent.AppendLine($"TOKEN1_SYMBOL = ""{txtToken1Symbol.Text.Trim()}""")
                            newContent.AppendLine($"TOKEN1_DECIMALS = {txtToken1Decimals.Text.Trim()}")
                            newContent.AppendLine($"TOKEN1_ADDRESS = ""{txtToken1Address.Text.Trim()}""")
                            token1SectionUpdated = True
                            inToken1Section = False
                        End If

                        ' Verifica se é a seção Token1
                        If line.Trim().Equals("[Token1]", StringComparison.OrdinalIgnoreCase) Then
                            inToken1Section = True
                            token1SectionUpdated = True
                        End If

                        newContent.AppendLine(line)
                    ElseIf inToken1Section Then
                        ' Dentro da seção Token1, ignora as linhas antigas (serão substituídas)
                        Continue For
                    Else
                        ' Fora de qualquer seção ou em outras seções
                        newContent.AppendLine(line)
                    End If
                Next

                ' Se estava na seção Token1 no final do arquivo
                If inToken1Section AndAlso token1SectionUpdated Then
                    newContent.AppendLine($"TOKEN1_NAME = ""{_tokenName}""")
                    newContent.AppendLine($"TOKEN1_SYMBOL = ""{txtToken1Symbol.Text.Trim()}""")
                    newContent.AppendLine($"TOKEN1_DECIMALS = {txtToken1Decimals.Text.Trim()}")
                    newContent.AppendLine($"TOKEN1_ADDRESS = ""{txtToken1Address.Text.Trim()}""")
                End If
            End If

            ' Se não encontrou a seção Token1, adiciona no final
            If Not token1SectionUpdated Then
                newContent.AppendLine()
                newContent.AppendLine("[Token1]")
                newContent.AppendLine($"TOKEN1_NAME = ""{_tokenName}""")
                newContent.AppendLine($"TOKEN1_SYMBOL = ""{txtToken1Symbol.Text.Trim()}""")
                newContent.AppendLine($"TOKEN1_DECIMALS = {txtToken1Decimals.Text.Trim()}")
                newContent.AppendLine($"TOKEN1_ADDRESS = ""{txtToken1Address.Text.Trim()}""")
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
                Dim currentAddress = txtToken1Address.Text.Trim()

                ' Configurar o ComboBox
                cmbToken1List.BeginUpdate()

                ' Limpar e adicionar itens manualmente (sem DataSource)
                cmbToken1List.Items.Clear()

                ' Adicionar item vazio como primeira opção
                cmbToken1List.Items.Add("Selecione um token...")

                ' Variável para armazenar o índice do item atual (se existir)
                Dim currentIndex As Integer = -1

                ' Adicionar todos os tokens
                For Each asset In polygonAssets
                    cmbToken1List.Items.Add(asset)

                    ' Verificar se é o token atual
                    If asset.contract.Equals(currentAddress, StringComparison.OrdinalIgnoreCase) Then
                        currentIndex = cmbToken1List.Items.Count - 1 ' Pega o último índice adicionado
                    End If
                Next

                ' Selecionar o item atual se encontrado
                If currentIndex > 0 Then
                    cmbToken1List.SelectedIndex = currentIndex
                Else
                    cmbToken1List.SelectedIndex = 0 ' Seleciona o item vazio
                End If

                cmbToken1List.EndUpdate()

                ' Configurar DisplayMember e ValueMember APÓS adicionar os itens
                cmbToken1List.DisplayMember = "name"
                cmbToken1List.ValueMember = "contract"

            End Using
        Catch ex As Exception
            MessageBox.Show("Erro ao carregar tokens: " & ex.Message, "Erro",
                  MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub TokenSelected(sender As Object, e As EventArgs)
        If cmbToken1List.SelectedItem IsNot Nothing AndAlso TypeOf cmbToken1List.SelectedItem Is Asset Then
            Dim selectedAsset = DirectCast(cmbToken1List.SelectedItem, Asset)

            ' Atualizar o nome internamente
            _tokenName = selectedAsset.name

            ' Preencher os campos com os dados do token selecionado
            txtToken1Symbol.Text = selectedAsset.ticker
            txtToken1Decimals.Text = selectedAsset.decimals.ToString()
            txtToken1Address.Text = selectedAsset.contract
        End If
    End Sub

    Private Sub LoadCurrentValues()
        ' Verifica se existe pelo menos um arquivo
        If _filePaths.Length = 0 Then Return

        ' Tenta carregar do primeiro arquivo (assumindo que todos têm a mesma configuração inicial)
        Dim firstFilePath = _filePaths(0)

        If File.Exists(firstFilePath) Then
            Try
                Dim content = File.ReadAllText(firstFilePath)

                ' Extrai name1
                Dim nameMatch = Regex.Match(content, "const name1\s*=\s*'([^']+)'")
                If nameMatch.Success Then
                    _tokenName = nameMatch.Groups(1).Value
                End If

                ' Extrai symbol1
                Dim symbolMatch = Regex.Match(content, "const symbol1\s*=\s*'([^']+)'")
                If symbolMatch.Success AndAlso String.IsNullOrEmpty(txtToken1Symbol.Text) Then
                    txtToken1Symbol.Text = symbolMatch.Groups(1).Value
                End If

                ' Extrai decimals1
                Dim decimalsMatch = Regex.Match(content, "const decimals1\s*=\s*(\d+)")
                If decimalsMatch.Success AndAlso String.IsNullOrEmpty(txtToken1Decimals.Text) Then
                    txtToken1Decimals.Text = decimalsMatch.Groups(1).Value
                End If

                ' Extrai address1
                Dim addressMatch = Regex.Match(content, "const address1\s*=\s*'([^']+)'")
                If addressMatch.Success AndAlso String.IsNullOrEmpty(txtToken1Address.Text) Then
                    txtToken1Address.Text = addressMatch.Groups(1).Value
                End If

            Catch ex As Exception
                MessageBox.Show($"Erro ao ler o arquivo {Path.GetFileName(firstFilePath)}: " & ex.Message, "Erro",
                              MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End If

        ' Se nenhum valor foi encontrado, usa os padrões
        If String.IsNullOrEmpty(_tokenName) OrElse
           String.IsNullOrEmpty(txtToken1Symbol.Text) OrElse
           String.IsNullOrEmpty(txtToken1Decimals.Text) OrElse
           String.IsNullOrEmpty(txtToken1Address.Text) Then
            ResetToDefaults(Nothing, EventArgs.Empty)
        End If
    End Sub

    Private Sub SaveConfiguration(sender As Object, e As EventArgs)
        ' Validação dos campos
        If String.IsNullOrWhiteSpace(txtToken1Symbol.Text) OrElse
           String.IsNullOrWhiteSpace(txtToken1Decimals.Text) OrElse
           String.IsNullOrWhiteSpace(txtToken1Address.Text) Then
            MessageBox.Show("Preencha todos os campos antes de salvar.", "Aviso",
                           MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        ' Verifica se o valor de decimals é numérico
        Dim decimalsValue As Integer
        If Not Integer.TryParse(txtToken1Decimals.Text, decimalsValue) Then
            MessageBox.Show("O valor de Decimals deve ser um número inteiro.", "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return
        End If

        ' Verifica se o endereço tem formato válido (simplificado)
        If Not txtToken1Address.Text.Trim().StartsWith("0x") OrElse txtToken1Address.Text.Trim().Length <> 42 Then
            MessageBox.Show("O endereço do contrato deve começar com '0x' e ter 42 caracteres.", "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return
        End If

        Try
            Dim successCount As Integer = 0
            Dim errorFiles As New List(Of String)

            For Each filePath In _filePaths
                Try
                    If File.Exists(filePath) Then
                        Dim content = File.ReadAllText(filePath)

                        ' Atualiza name1
                        content = Regex.Replace(content, "const name1\s*=\s*'[^']*'",
                                              $"const name1 = '{_tokenName.Trim()}'")

                        ' Atualiza symbol1
                        content = Regex.Replace(content, "const symbol1\s*=\s*'[^']*'",
                                              $"const symbol1 = '{txtToken1Symbol.Text.Trim()}'")

                        ' Atualiza decimals1
                        content = Regex.Replace(content, "const decimals1\s*=\s*\d+",
                                              $"const decimals1 = {txtToken1Decimals.Text.Trim()}")

                        ' Atualiza address1
                        content = Regex.Replace(content, "const address1\s*=\s*'[^']*'",
                                              $"const address1 = '{txtToken1Address.Text.Trim()}'")

                        File.WriteAllText(filePath, content, Encoding.UTF8)
                        successCount += 1
                    Else
                        errorFiles.Add(Path.GetFileName(filePath))
                    End If
                Catch ex As Exception
                    errorFiles.Add($"{Path.GetFileName(filePath)} ({ex.Message})")
                End Try
            Next

            ' Salva as configurações no arquivo INI
            SaveSettingsToIni()

            ' Exibe mensagem de resultado
            If successCount = _filePaths.Length Then
                MessageBox.Show("Configuração do Token1 salva com sucesso em todos os arquivos!", "Sucesso",
                              MessageBoxButtons.OK, MessageBoxIcon.Information)
            ElseIf successCount > 0 Then
                MessageBox.Show($"Configuração do Token1 salva em {successCount} de {_filePaths.Length} arquivos." & vbCrLf &
                              $"Arquivos com erro: {String.Join(", ", errorFiles)}", "Resultado parcial",
                              MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Else
                MessageBox.Show("Falha ao salvar a configuração do Token1 em todos os arquivos.", "Erro",
                              MessageBoxButtons.OK, MessageBoxIcon.Error)
            End If

        Catch ex As Exception
            MessageBox.Show("Erro ao salvar configuração do Token1: " & ex.Message, "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub ResetToDefaults(sender As Object, e As EventArgs)
        _tokenName = DEFAULT_NAME
        txtToken1Symbol.Text = DEFAULT_SYMBOL
        txtToken1Decimals.Text = DEFAULT_DECIMALS.ToString()
        txtToken1Address.Text = DEFAULT_ADDRESS
    End Sub

    Public Sub Cleanup()
        RemoveHandler btnSaveToken1Config.Click, AddressOf SaveConfiguration
        RemoveHandler btnResetToken1Config.Click, AddressOf ResetToDefaults
        RemoveHandler btnLoadToken1List.Click, AddressOf LoadTokensFromAPI
        RemoveHandler cmbToken1List.SelectedIndexChanged, AddressOf TokenSelected
    End Sub
End Class