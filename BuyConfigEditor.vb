Imports System.IO
Imports System.Text.RegularExpressions
Imports System.Text
Imports System.Net.Http
Imports System.Text.Json

Public Class BuyConfigEditor
    ' REMOVI OS CAMINHOS DE JS E ADICIONEI APENAS DO INI
    Private ReadOnly _settingsIniPath As String

    ' VALORES PADRÃO (ALTEREI PARA OS QUE VOCÊ QUER)
    Private ReadOnly DEFAULT_POOL_ADDRESS As String = "0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161"
    Private ReadOnly DEFAULT_SWAP_ROUTER As String = "0xE592427A0AEce92De3Edee1F18E0157C05861564"

    ' CONTROLES (MANTIDOS EXATAMENTE IGUAL)
    Public Property PoolAddressTextBox As TextBox
    Public Property SwapRouterTextBox As TextBox
    Public Property SaveButton As Button
    Public Property ResetButton As Button
    Public Property PoolAddressComboBox As ComboBox
    Public Property LoadPoolsButton As Button
    Public Property LoadingLabel As Label
    Public Property LoadingProgressBar As ProgressBar

    ' CONSTRUTOR SIMPLIFICADO (APENAS INI)
    Public Sub New(workingDirectory As String)
        _settingsIniPath = Path.Combine(Directory.GetParent(workingDirectory).FullName, "settings.ini")
    End Sub

    ' INICIALIZAÇÃO (MANTIDA IGUAL)
    Public Sub Initialize()
        AddHandler SaveButton.Click, AddressOf btnSave_Click
        AddHandler ResetButton.Click, AddressOf btnReset_Click
        AddHandler PoolAddressComboBox.SelectedIndexChanged, AddressOf PoolAddressComboBox_SelectedIndexChanged
        AddHandler LoadPoolsButton.Click, AddressOf btnLoadPools_Click

        If LoadingLabel IsNot Nothing Then
            LoadingLabel.Visible = False
            LoadingLabel.Text = "Carregando pools..."
        End If

        If LoadingProgressBar IsNot Nothing Then
            LoadingProgressBar.Visible = False
            LoadingProgressBar.Style = ProgressBarStyle.Marquee
        End If

        LoadCurrentValues()
    End Sub

    ' MÉTODOS DE CARREGAMENTO DE POOLS (MANTIDOS IGUAIS)
    Private Async Sub btnLoadPools_Click(sender As Object, e As EventArgs)
        ToggleLoadingState(True)
        Try
            Await LoadPoolAddresses()
        Catch ex As Exception
            MessageBox.Show($"Erro ao carregar pools: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        Finally
            ToggleLoadingState(False)
        End Try
    End Sub

    Private Sub ToggleLoadingState(isLoading As Boolean)
        If LoadPoolsButton.InvokeRequired Then
            LoadPoolsButton.Invoke(Sub() ToggleLoadingState(isLoading))
            Return
        End If
        LoadPoolsButton.Enabled = Not isLoading
        If LoadingLabel IsNot Nothing Then LoadingLabel.Visible = isLoading
        If LoadingProgressBar IsNot Nothing Then LoadingProgressBar.Visible = isLoading
    End Sub

    Private Async Function LoadPoolAddresses() As Task
        Try
            Dim url = "https://typofx.ie/plataforma/panel/lp-contracts/lp_contracts.json"
            Using client As New HttpClient()
                Dim response = Await client.GetAsync(url)
                response.EnsureSuccessStatusCode()
                Dim json = Await response.Content.ReadAsStringAsync()
                Dim options = New JsonSerializerOptions With {.PropertyNameCaseInsensitive = True, .AllowTrailingCommas = True}
                Dim contracts = JsonSerializer.Deserialize(Of List(Of LpContract))(json, options)

                If contracts IsNot Nothing Then
                    Dim validContracts = contracts.
                        Where(Function(c) c IsNot Nothing AndAlso c.id > 0 AndAlso
                             c.exchange IsNot Nothing AndAlso Not {"CetoEX", "CoinInn", "AAVE"}.Contains(c.exchange)).
                        OrderBy(Function(c) c.pair).
                        ThenBy(Function(c) c.exchange).
                        ToList()

                    If PoolAddressComboBox IsNot Nothing Then
                        PoolAddressComboBox.Invoke(Sub()
                                                       PoolAddressComboBox.BeginUpdate()
                                                       PoolAddressComboBox.Items.Clear()
                                                       PoolAddressComboBox.Items.Add("Selecione um pool...")

                                                       For Each contract In validContracts
                                                           If contract.pair IsNot Nothing AndAlso contract.contract IsNot Nothing Then
                                                               PoolAddressComboBox.Items.Add(New PoolAddressItem(
                                                                   contract.pair, contract.exchange, contract.contract))
                                                           End If
                                                       Next

                                                       ' Seleciona item correspondente ao valor atual
                                                       Dim currentAddress = PoolAddressTextBox.Text.Trim()
                                                       If Not String.IsNullOrEmpty(currentAddress) Then
                                                           For i = 0 To PoolAddressComboBox.Items.Count - 1
                                                               If TypeOf PoolAddressComboBox.Items(i) Is PoolAddressItem Then
                                                                   Dim item = DirectCast(PoolAddressComboBox.Items(i), PoolAddressItem)
                                                                   If item.ContractAddress.Equals(currentAddress, StringComparison.OrdinalIgnoreCase) Then
                                                                       PoolAddressComboBox.SelectedIndex = i
                                                                       Exit For
                                                                   End If
                                                               End If
                                                           Next
                                                       End If

                                                       If PoolAddressComboBox.SelectedIndex = -1 Then
                                                           PoolAddressComboBox.SelectedIndex = 0
                                                       End If

                                                       PoolAddressComboBox.EndUpdate()
                                                   End Sub)
                    End If
                End If
            End Using
        Catch ex As Exception
            Throw New Exception("Falha ao carregar pools: " & ex.Message, ex)
        End Try
    End Function

    ' CLASSES INTERNAS (MANTIDAS IGUAIS)
    Private Class LpContract
        Public Property id As Integer
        Public Property pair As String
        Public Property contract As String
        Public Property exchange As String
        Public Property liquidity As Decimal
    End Class

    Public Class PoolAddressItem
        Public Property DisplayText As String
        Public Property ContractAddress As String

        Public Sub New(pair As String, exchange As String, address As String)
            Dim formattedAddress = If(address.Length >= 10, $"{address.Substring(0, 6)}...{address.Substring(address.Length - 4)}", address)
            DisplayText = $"{pair} ({exchange}) - {formattedAddress}"
            ContractAddress = address
        End Sub

        Public Overrides Function ToString() As String
            Return DisplayText
        End Function
    End Class

    ' EVENTO DO COMBOBOX (MANTIDO IGUAL)
    Private Sub PoolAddressComboBox_SelectedIndexChanged(sender As Object, e As EventArgs)
        If PoolAddressComboBox.SelectedItem IsNot Nothing AndAlso
           TypeOf PoolAddressComboBox.SelectedItem Is PoolAddressItem Then
            Dim selectedItem = DirectCast(PoolAddressComboBox.SelectedItem, PoolAddressItem)
            PoolAddressTextBox.Text = selectedItem.ContractAddress
        End If
    End Sub

    ' MÉTODO LoadCurrentValues MODIFICADO PARA LER APENAS DO INI
    Private Sub LoadCurrentValues()
        If Not File.Exists(_settingsIniPath) Then
            ResetToDefaults()
            Return
        End If

        Try
            Dim iniContent As String = File.ReadAllText(_settingsIniPath)

            ' Extrai POOL_ADDRESS da seção [Allowance]
            Dim poolMatch = Regex.Match(iniContent, "\[Allowance\][^\[]*POOL_ADDRESS\s*=\s*""([^""]+)""", RegexOptions.IgnoreCase)
            If poolMatch.Success Then
                PoolAddressTextBox.Text = poolMatch.Groups(1).Value.Trim()
            End If

            ' Extrai ROUTER_ADDRESS da seção [Allowance]
            Dim routerMatch = Regex.Match(iniContent, "\[Allowance\][^\[]*ROUTER_ADDRESS\s*=\s*""([^""]+)""", RegexOptions.IgnoreCase)
            If routerMatch.Success Then
                SwapRouterTextBox.Text = routerMatch.Groups(1).Value.Trim()
            End If

            ' Atualiza ComboBox se necessário
            If Not String.IsNullOrEmpty(PoolAddressTextBox.Text) AndAlso PoolAddressComboBox.Items.Count > 0 Then
                For i = 0 To PoolAddressComboBox.Items.Count - 1
                    If TypeOf PoolAddressComboBox.Items(i) Is PoolAddressItem Then
                        Dim item = DirectCast(PoolAddressComboBox.Items(i), PoolAddressItem)
                        If item.ContractAddress.Equals(PoolAddressTextBox.Text, StringComparison.OrdinalIgnoreCase) Then
                            PoolAddressComboBox.SelectedIndex = i
                            Exit For
                        End If
                    End If
                Next
            End If

        Catch ex As Exception
            MessageBox.Show($"Erro ao ler settings.ini: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            ResetToDefaults()
        End Try
    End Sub

    ' MÉTODO btnSave_Click MODIFICADO PARA SALVAR APENAS NO INI
    Private Sub btnSave_Click(sender As Object, e As EventArgs)
        Try
            ' Validação (MANTIDA IGUAL)
            If String.IsNullOrWhiteSpace(PoolAddressTextBox.Text) Then
                MessageBox.Show("Por favor, selecione um pool válido.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If

            If String.IsNullOrWhiteSpace(SwapRouterTextBox.Text) Then
                MessageBox.Show("O endereço do roteador de swap é obrigatório.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If

            ' Lê ou cria o arquivo INI
            Dim iniContent As String = If(File.Exists(_settingsIniPath), File.ReadAllText(_settingsIniPath), "")

            ' Atualiza a seção [Allowance]
            Dim allowanceSection As New StringBuilder()
            allowanceSection.AppendLine("[Allowance]")
            allowanceSection.AppendLine($"POOL_ADDRESS = ""{PoolAddressTextBox.Text.Trim()}""")
            allowanceSection.AppendLine($"ROUTER_ADDRESS = ""{SwapRouterTextBox.Text.Trim()}""")

            ' Substitui ou adiciona a seção [Allowance]
            If Regex.IsMatch(iniContent, "\[Allowance\]", RegexOptions.IgnoreCase) Then
                iniContent = Regex.Replace(iniContent, "\[Allowance\][^\[]*", allowanceSection.ToString())
            Else
                iniContent += vbCrLf & allowanceSection.ToString()
            End If

            ' Salva o arquivo
            File.WriteAllText(_settingsIniPath, iniContent, Encoding.UTF8)
            MessageBox.Show("Configurações salvas com sucesso no settings.ini!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)

        Catch ex As Exception
            MessageBox.Show($"Erro ao salvar settings.ini: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' RESET E CLEANUP (MANTIDOS IGUAIS)
    Private Sub btnReset_Click(sender As Object, e As EventArgs)
        ResetToDefaults()
    End Sub

    Public Sub ResetToDefaults()
        PoolAddressTextBox.Text = DEFAULT_POOL_ADDRESS
        SwapRouterTextBox.Text = DEFAULT_SWAP_ROUTER
        If PoolAddressComboBox IsNot Nothing AndAlso PoolAddressComboBox.Items.Count > 0 Then
            PoolAddressComboBox.SelectedIndex = 0
        End If
    End Sub

    Public Sub Cleanup()
        RemoveHandler SaveButton.Click, AddressOf btnSave_Click
        RemoveHandler ResetButton.Click, AddressOf btnReset_Click
        RemoveHandler PoolAddressComboBox.SelectedIndexChanged, AddressOf PoolAddressComboBox_SelectedIndexChanged
        RemoveHandler LoadPoolsButton.Click, AddressOf btnLoadPools_Click
    End Sub
End Class