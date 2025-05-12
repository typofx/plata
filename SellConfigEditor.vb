Imports System.IO
Imports System.Text.RegularExpressions
Imports System.Text
Imports System.Net.Http
Imports System.Text.Json

Public Class SellConfigEditor
    ' Caminhos dos arquivos
    Private ReadOnly _filePaths As String()

    ' Valores padrão
    Private ReadOnly DEFAULT_POOL_ADDRESS As String = "0xE592427A0AEce92De3Edee1F18E0157C05861564"
    Private ReadOnly DEFAULT_SWAP_ROUTER As String = "0x6A000F20005980200259B80c5102003040001068"

    ' Controles do formulário
    Public Property PoolAddressTextBox As TextBox
    Public Property SwapRouterTextBox As TextBox
    Public Property SaveButton As Button
    Public Property ResetButton As Button
    Public Property PoolAddressComboBox As ComboBox
    Public Property LoadPoolsButton As Button
    Public Property LoadingLabel As Label
    Public Property LoadingProgressBar As ProgressBar

    ' Construtor
    Public Sub New(workingDirectory As String)
        _filePaths = {
            Path.Combine(workingDirectory, "aproveToken0.js"),
            Path.Combine(workingDirectory, "aproveToken1.js")
        }
    End Sub

    ' Inicialização
    Public Sub Initialize()
        AddHandler SaveButton.Click, AddressOf btnSave_Click
        AddHandler ResetButton.Click, AddressOf btnReset_Click
        AddHandler PoolAddressComboBox.SelectedIndexChanged, AddressOf PoolAddressComboBox_SelectedIndexChanged
        AddHandler LoadPoolsButton.Click, AddressOf btnLoadPools_Click

        ' Configura controles de loading
        If LoadingLabel IsNot Nothing Then
            LoadingLabel.Visible = False
            LoadingLabel.Text = "Carregando pools..."
        End If

        If LoadingProgressBar IsNot Nothing Then
            LoadingProgressBar.Visible = False
            LoadingProgressBar.Style = ProgressBarStyle.Marquee
        End If

        ' Carrega valores iniciais
        LoadCurrentValues()
    End Sub

    ' Evento do botão para carregar pools
    Private Async Sub btnLoadPools_Click(sender As Object, e As EventArgs)
        ToggleLoadingState(True)

        Try
            Await LoadPoolAddresses()
        Catch ex As Exception
            MessageBox.Show($"Erro ao carregar pools: {ex.Message}", "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            ToggleLoadingState(False)
        End Try
    End Sub

    ' Ativa/desativa estado de loading
    Private Sub ToggleLoadingState(isLoading As Boolean)
        If LoadPoolsButton.InvokeRequired Then
            LoadPoolsButton.Invoke(Sub() ToggleLoadingState(isLoading))
            Return
        End If

        LoadPoolsButton.Enabled = Not isLoading
        If LoadingLabel IsNot Nothing Then
            LoadingLabel.Visible = isLoading
        End If
        If LoadingProgressBar IsNot Nothing Then
            LoadingProgressBar.Visible = isLoading
        End If
    End Sub

    ' Carrega os pool addresses do JSON
    Private Async Function LoadPoolAddresses() As Task
        Try
            Dim url = "https://typofx.ie/plataforma/panel/lp-contracts/lp_contracts.json"
            Using client As New HttpClient()
                Dim response = Await client.GetAsync(url)
                response.EnsureSuccessStatusCode()

                Dim json = Await response.Content.ReadAsStringAsync()
                Dim options = New JsonSerializerOptions With {
                .PropertyNameCaseInsensitive = True,
                .AllowTrailingCommas = True
            }
                Dim contracts = JsonSerializer.Deserialize(Of List(Of LpContract))(json, options)

                If contracts IsNot Nothing Then
                    Dim excludedExchanges = New List(Of String) From {"CetoEX", "CoinInn", "AAVE"}
                    Dim validContracts As New List(Of LpContract)

                    For Each contract In contracts
                        If contract IsNot Nothing AndAlso
                       contract.id > 0 AndAlso
                       contract.exchange IsNot Nothing AndAlso
                       Not excludedExchanges.Contains(contract.exchange) Then
                            validContracts.Add(contract)
                        End If
                    Next

                    validContracts = validContracts.
                    OrderBy(Function(c) c.pair).
                    ThenBy(Function(c) c.exchange).
                    ToList()

                    If PoolAddressComboBox IsNot Nothing Then
                        PoolAddressComboBox.Invoke(Sub()
                                                       PoolAddressComboBox.BeginUpdate()
                                                       PoolAddressComboBox.Items.Clear()

                                                       ' Adiciona item vazio como primeira opção
                                                       PoolAddressComboBox.Items.Add("Selecione um pool...")

                                                       For Each contract In validContracts
                                                           If contract.pair IsNot Nothing AndAlso
                                                          contract.contract IsNot Nothing AndAlso
                                                          contract.exchange IsNot Nothing Then
                                                               PoolAddressComboBox.Items.Add(New PoolAddressItem(
                                                               contract.pair,
                                                               contract.exchange,
                                                               contract.contract))
                                                           End If
                                                       Next

                                                       PoolAddressComboBox.EndUpdate()

                                                       ' **Tenta selecionar o item correspondente ao valor atual**
                                                       If Not String.IsNullOrEmpty(PoolAddressTextBox.Text) Then
                                                           For i As Integer = 0 To PoolAddressComboBox.Items.Count - 1
                                                               If TypeOf PoolAddressComboBox.Items(i) Is PoolAddressItem Then
                                                                   Dim item = DirectCast(PoolAddressComboBox.Items(i), PoolAddressItem)
                                                                   If item.ContractAddress.Equals(PoolAddressTextBox.Text, StringComparison.OrdinalIgnoreCase) Then
                                                                       PoolAddressComboBox.SelectedIndex = i
                                                                       Exit For
                                                                   End If
                                                               End If
                                                           Next
                                                       End If
                                                   End Sub)
                    End If
                End If
            End Using
        Catch ex As Exception
            Throw New Exception("Falha ao carregar pools: " & ex.Message, ex)
        End Try
    End Function

    ' Classe para representar os dados do JSON
    Private Class LpContract
        Public Property id As Integer
        Public Property pair As String
        Public Property contract As String
        Public Property exchange As String
        Public Property liquidity As Decimal
    End Class

    ' Classe para itens do ComboBox
    Private Class PoolAddressItem
        Public Property DisplayText As String
        Public Property ContractAddress As String

        Public Sub New(pair As String, exchange As String, address As String)
            ' Formata o endereço: 6 primeiros caracteres + "..." + 4 últimos caracteres
            Dim formattedAddress = If(address.Length >= 10,
                                   $"{address.Substring(0, 6)}...{address.Substring(address.Length - 4)}",
                                   address) ' Fallback para endereços curtos

            DisplayText = $"{pair} ({exchange}) - {formattedAddress}"
            ContractAddress = address
        End Sub

        Public Overrides Function ToString() As String
            Return DisplayText
        End Function
    End Class

    ' Evento quando seleciona um pool no ComboBox
    Private Sub PoolAddressComboBox_SelectedIndexChanged(sender As Object, e As EventArgs)
        If PoolAddressComboBox.SelectedItem IsNot Nothing AndAlso
           TypeOf PoolAddressComboBox.SelectedItem Is PoolAddressItem Then

            Dim selectedItem = DirectCast(PoolAddressComboBox.SelectedItem, PoolAddressItem)
            PoolAddressTextBox.Text = selectedItem.ContractAddress
        End If
    End Sub

    ' Carrega valores atuais
    Private Sub LoadCurrentValues()
        Dim valuesLoaded As Boolean = False

        For Each filePath In _filePaths
            If File.Exists(filePath) Then
                Try
                    Dim content As String = File.ReadAllText(filePath)

                    ' Extrai poolAddress
                    Dim poolMatch = Regex.Match(content, "const\s+poolAddress\s*=\s*""([^""]+)""")
                    If poolMatch.Success AndAlso String.IsNullOrEmpty(PoolAddressTextBox.Text) Then
                        PoolAddressTextBox.Text = poolMatch.Groups(1).Value

                        ' Seleciona o item correspondente no ComboBox
                        If PoolAddressComboBox IsNot Nothing AndAlso PoolAddressComboBox.Items.Count > 0 Then
                            For i As Integer = 0 To PoolAddressComboBox.Items.Count - 1
                                If TypeOf PoolAddressComboBox.Items(i) Is PoolAddressItem Then
                                    Dim item = DirectCast(PoolAddressComboBox.Items(i), PoolAddressItem)
                                    If item.ContractAddress = PoolAddressTextBox.Text Then
                                        PoolAddressComboBox.SelectedIndex = i
                                        Exit For
                                    End If
                                End If
                            Next
                        End If
                    End If

                    ' Extrai swapRouterAddress
                    Dim routerMatch = Regex.Match(content, "const\s+swapRouterAddress\s*=\s*'([^']+)'")
                    If routerMatch.Success AndAlso String.IsNullOrEmpty(SwapRouterTextBox.Text) Then
                        SwapRouterTextBox.Text = routerMatch.Groups(1).Value
                    End If

                    valuesLoaded = True

                Catch ex As Exception
                    Continue For
                End Try
            End If
        Next

        If Not valuesLoaded Then
            ResetToDefaults()
        End If
    End Sub

    ' Evento de salvar
    Private Sub btnSave_Click(sender As Object, e As EventArgs)
        Try
            ' Validação
            If String.IsNullOrWhiteSpace(PoolAddressTextBox.Text) Then
                MessageBox.Show("Por favor, selecione um pool válido.", "Aviso",
                              MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If

            If String.IsNullOrWhiteSpace(SwapRouterTextBox.Text) Then
                MessageBox.Show("O endereço do roteador de swap é obrigatório.", "Aviso",
                              MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If

            ' Salva em todos os arquivos
            For Each filePath In _filePaths
                Dim content As String = If(File.Exists(filePath), File.ReadAllText(filePath), GetDefaultJsContent())

                ' Atualiza poolAddress
                content = Regex.Replace(content, "const\s+poolAddress\s*=\s*""[^""]+""",
                                    $"const poolAddress = ""{PoolAddressTextBox.Text.Trim()}""")

                ' Atualiza swapRouterAddress
                content = Regex.Replace(content, "const\s+swapRouterAddress\s*=\s*'[^']+'",
                                    $"const swapRouterAddress = '{SwapRouterTextBox.Text.Trim()}'")

                File.WriteAllText(filePath, content, Encoding.UTF8)
            Next

            '   MessageBox.Show("Configurações salvas com sucesso!", "Sucesso",
            ' MessageBoxButtons.OK, MessageBoxIcon.Information)

        Catch ex As Exception
            MessageBox.Show($"Erro ao salvar configurações: {ex.Message}", "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' Reseta para padrão
    Private Sub btnReset_Click(sender As Object, e As EventArgs)
        ResetToDefaults()
    End Sub

    ' Define valores padrão
    Public Sub ResetToDefaults()
        PoolAddressTextBox.Text = DEFAULT_POOL_ADDRESS
        SwapRouterTextBox.Text = DEFAULT_SWAP_ROUTER

        If PoolAddressComboBox IsNot Nothing AndAlso PoolAddressComboBox.Items.Count > 0 Then
            PoolAddressComboBox.SelectedIndex = 0
        End If
    End Sub

    ' Limpeza
    Public Sub Cleanup()
        RemoveHandler SaveButton.Click, AddressOf btnSave_Click
        RemoveHandler ResetButton.Click, AddressOf btnReset_Click
        RemoveHandler PoolAddressComboBox.SelectedIndexChanged, AddressOf PoolAddressComboBox_SelectedIndexChanged
        RemoveHandler LoadPoolsButton.Click, AddressOf btnLoadPools_Click
    End Sub

    ' Conteúdo padrão do arquivo
    Private Function GetDefaultJsContent() As String
        Return $"const poolAddress = ""{DEFAULT_POOL_ADDRESS}"";" & vbCrLf &
               $"const swapRouterAddress = '{DEFAULT_SWAP_ROUTER}';" & vbCrLf &
               "// Outras configurações"
    End Function
End Class