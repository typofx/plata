Imports System.IO
Imports System.Text.RegularExpressions
Imports System.Text
Imports System.Net.Http
Imports System.Text.Json
Imports System.Linq

Public Class UnifiedConfigEditor
    ' Configurações de caminhos
    Private ReadOnly _workingDirectory As String
    Private ReadOnly _settingsIniPath As String

    ' Constantes para BuyConfig
    Private ReadOnly DEFAULT_POOL_ADDRESS As String = "0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161"
    Private ReadOnly DEFAULT_SWAP_ROUTER As String = "0xE592427A0AEce92De3Edee1F18E0157C05861564"

    ' Constantes para TokenConfig (Token0)
    Private ReadOnly DEFAULT_TOKEN0_NAME As String = "Wrapped Polygon"
    Private ReadOnly DEFAULT_TOKEN0_SYMBOL As String = "WPOL"
    Private ReadOnly DEFAULT_TOKEN0_DECIMALS As Integer = 18
    Private ReadOnly DEFAULT_TOKEN0_ADDRESS As String = "0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270"

    ' Constantes para Token1Config
    Private ReadOnly DEFAULT_TOKEN1_NAME As String = "Plata"
    Private ReadOnly DEFAULT_TOKEN1_SYMBOL As String = "PLT"
    Private ReadOnly DEFAULT_TOKEN1_DECIMALS As Integer = 4
    Private ReadOnly DEFAULT_TOKEN1_ADDRESS As String = "0xc298812164bd558268f51cc6e3b8b5daaf0b6341"

    ' URLs para APIs
    Private ReadOnly POOLS_JSON_URL As String = "https://typofx.ie/plataforma/panel/lp-contracts/lp_contracts.json"
    Private ReadOnly TOKENS_JSON_URL As String = "https://typofx.ie/plataforma/panel/asset/assets_data.json"

    ' Controles para BuyConfig
    Public Property PoolAddressTextBox As TextBox
    Public Property SwapRouterTextBox As TextBox
    Public Property SaveButton As Button
    Public Property ResetButton As Button
    Public Property PoolAddressComboBox As ComboBox
    Public Property LoadPoolsButton As Button
    Public Property LoadingLabel As Label
    Public Property LoadingProgressBar As ProgressBar

    ' Controles para TokenConfig (Token0)
    Public Property Token0SymbolTextBox As TextBox
    Public Property Token0DecimalsTextBox As TextBox
    Public Property Token0AddressTextBox As TextBox
    Public Property Token0SaveButton As Button
    Public Property Token0ResetButton As Button
    Public Property Token0ComboBox As ComboBox
    Public Property Token0LoadButton As Button

    ' Controles para Token1Config
    Public Property Token1SymbolTextBox As TextBox
    Public Property Token1DecimalsTextBox As TextBox
    Public Property Token1AddressTextBox As TextBox
    Public Property Token1SaveButton As Button
    Public Property Token1ResetButton As Button
    Public Property Token1ComboBox As ComboBox
    Public Property Token1LoadButton As Button

    ' Variáveis internas para nomes dos tokens
    Private _token0Name As String = DEFAULT_TOKEN0_NAME
    Private _token1Name As String = DEFAULT_TOKEN1_NAME

    ' Classes para deserialização JSON
    Private Class LpContract
        Public Property id As Integer
        Public Property pair As String
        Public Property contract As String
        Public Property exchange As String
        Public Property liquidity As Decimal
    End Class

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

    Public Sub New(workingDirectory As String)
        _workingDirectory = workingDirectory
        _settingsIniPath = Path.Combine(Directory.GetParent(workingDirectory).FullName, "settings.ini")
    End Sub

    Public Sub Initialize()
        ' Configura handlers para BuyConfig
        If SaveButton IsNot Nothing Then AddHandler SaveButton.Click, AddressOf SaveBuyConfig
        If ResetButton IsNot Nothing Then AddHandler ResetButton.Click, AddressOf ResetBuyConfig
        If LoadPoolsButton IsNot Nothing Then AddHandler LoadPoolsButton.Click, AddressOf LoadPools
        If PoolAddressComboBox IsNot Nothing Then AddHandler PoolAddressComboBox.SelectedIndexChanged, AddressOf PoolAddressComboBox_SelectedIndexChanged

        ' Configura handlers para Token0
        If Token0SaveButton IsNot Nothing Then AddHandler Token0SaveButton.Click, AddressOf SaveToken0Config
        If Token0ResetButton IsNot Nothing Then AddHandler Token0ResetButton.Click, AddressOf ResetToken0Config
        If Token0LoadButton IsNot Nothing Then AddHandler Token0LoadButton.Click, AddressOf LoadToken0List
        If Token0ComboBox IsNot Nothing Then AddHandler Token0ComboBox.SelectedIndexChanged, AddressOf Token0Selected

        ' Configura handlers para Token1
        If Token1SaveButton IsNot Nothing Then AddHandler Token1SaveButton.Click, AddressOf SaveToken1Config
        If Token1ResetButton IsNot Nothing Then AddHandler Token1ResetButton.Click, AddressOf ResetToken1Config
        If Token1LoadButton IsNot Nothing Then AddHandler Token1LoadButton.Click, AddressOf LoadToken1List
        If Token1ComboBox IsNot Nothing Then AddHandler Token1ComboBox.SelectedIndexChanged, AddressOf Token1Selected

        ' Configura elementos de loading
        If LoadingLabel IsNot Nothing Then
            LoadingLabel.Visible = False
            LoadingLabel.Text = "Carregando..."
        End If

        If LoadingProgressBar IsNot Nothing Then
            LoadingProgressBar.Visible = False
            LoadingProgressBar.Style = ProgressBarStyle.Marquee
        End If

        ' Carrega valores atuais
        LoadAllSettings()
    End Sub

    Private Sub LoadAllSettings()
        If Not File.Exists(_settingsIniPath) Then
            ResetAllToDefaults()
            Return
        End If

        Try
            Dim iniContent As String = File.ReadAllText(_settingsIniPath)

            ' Carrega configurações de Buy (Allowance)
            Dim poolMatch = Regex.Match(iniContent, "\[Allowance\][^\[]*POOL_ADDRESS\s*=\s*""([^""]+)""", RegexOptions.IgnoreCase)
            If poolMatch.Success AndAlso PoolAddressTextBox IsNot Nothing Then
                PoolAddressTextBox.Text = poolMatch.Groups(1).Value.Trim()
            End If

            Dim routerMatch = Regex.Match(iniContent, "\[Allowance\][^\[]*ROUTER_ADDRESS\s*=\s*""([^""]+)""", RegexOptions.IgnoreCase)
            If routerMatch.Success AndAlso SwapRouterTextBox IsNot Nothing Then
                SwapRouterTextBox.Text = routerMatch.Groups(1).Value.Trim()
            End If

            ' Carrega configurações de Token0
            Dim token0SectionMatch = Regex.Match(iniContent, "\[Token0\](.*?)(?=\[|$)", RegexOptions.Singleline)
            If token0SectionMatch.Success Then
                Dim tokenSection = token0SectionMatch.Groups(1).Value

                Dim nameMatch = Regex.Match(tokenSection, "TOKEN0_NAME\s*=\s*""([^""]*)""")
                If nameMatch.Success Then _token0Name = nameMatch.Groups(1).Value

                If Token0SymbolTextBox IsNot Nothing Then
                    Dim symbolMatch = Regex.Match(tokenSection, "TOKEN0_SYMBOL\s*=\s*""([^""]*)""")
                    If symbolMatch.Success Then Token0SymbolTextBox.Text = symbolMatch.Groups(1).Value
                End If

                If Token0DecimalsTextBox IsNot Nothing Then
                    Dim decimalsMatch = Regex.Match(tokenSection, "TOKEN0_DECIMALS\s*=\s*(\d+)")
                    If decimalsMatch.Success Then Token0DecimalsTextBox.Text = decimalsMatch.Groups(1).Value
                End If

                If Token0AddressTextBox IsNot Nothing Then
                    Dim addressMatch = Regex.Match(tokenSection, "TOKEN0_ADDRESS\s*=\s*""([^""]*)""")
                    If addressMatch.Success Then Token0AddressTextBox.Text = addressMatch.Groups(1).Value
                End If
            End If

            ' Carrega configurações de Token1
            Dim token1SectionMatch = Regex.Match(iniContent, "\[Token1\](.*?)(?=\[|$)", RegexOptions.Singleline)
            If token1SectionMatch.Success Then
                Dim tokenSection = token1SectionMatch.Groups(1).Value

                Dim nameMatch = Regex.Match(tokenSection, "TOKEN1_NAME\s*=\s*""([^""]*)""")
                If nameMatch.Success Then _token1Name = nameMatch.Groups(1).Value

                If Token1SymbolTextBox IsNot Nothing Then
                    Dim symbolMatch = Regex.Match(tokenSection, "TOKEN1_SYMBOL\s*=\s*""([^""]*)""")
                    If symbolMatch.Success Then Token1SymbolTextBox.Text = symbolMatch.Groups(1).Value
                End If

                If Token1DecimalsTextBox IsNot Nothing Then
                    Dim decimalsMatch = Regex.Match(tokenSection, "TOKEN1_DECIMALS\s*=\s*(\d+)")
                    If decimalsMatch.Success Then Token1DecimalsTextBox.Text = decimalsMatch.Groups(1).Value
                End If

                If Token1AddressTextBox IsNot Nothing Then
                    Dim addressMatch = Regex.Match(tokenSection, "TOKEN1_ADDRESS\s*=\s*""([^""]*)""")
                    If addressMatch.Success Then Token1AddressTextBox.Text = addressMatch.Groups(1).Value
                End If
            End If

        Catch ex As Exception
            MessageBox.Show($"Erro ao ler settings.ini: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            ResetAllToDefaults()
        End Try
    End Sub

    Private Sub SaveAllSettings()
        Try
            Dim existingContent As String = If(File.Exists(_settingsIniPath), File.ReadAllText(_settingsIniPath), "")
            Dim newContent As New StringBuilder()

            ' Processa seção Allowance
            UpdateIniSection(newContent, existingContent, "[Allowance]", Function(sb)
                                                                             sb.AppendLine($"POOL_ADDRESS = ""{If(PoolAddressTextBox IsNot Nothing, PoolAddressTextBox.Text.Trim(), DEFAULT_POOL_ADDRESS)}""")
                                                                             sb.AppendLine($"ROUTER_ADDRESS = ""{If(SwapRouterTextBox IsNot Nothing, SwapRouterTextBox.Text.Trim(), DEFAULT_SWAP_ROUTER)}""")
                                                                         End Function)

            ' Processa seção Token0
            UpdateIniSection(newContent, existingContent, "[Token0]", Function(sb)
                                                                          sb.AppendLine($"TOKEN0_NAME = ""{_token0Name}""")
                                                                          sb.AppendLine($"TOKEN0_SYMBOL = ""{If(Token0SymbolTextBox IsNot Nothing, Token0SymbolTextBox.Text.Trim(), DEFAULT_TOKEN0_SYMBOL)}""")
                                                                          sb.AppendLine($"TOKEN0_DECIMALS = {If(Token0DecimalsTextBox IsNot Nothing, Token0DecimalsTextBox.Text.Trim(), DEFAULT_TOKEN0_DECIMALS)}")
                                                                          sb.AppendLine($"TOKEN0_ADDRESS = ""{If(Token0AddressTextBox IsNot Nothing, Token0AddressTextBox.Text.Trim(), DEFAULT_TOKEN0_ADDRESS)}""")
                                                                      End Function)

            ' Processa seção Token1
            UpdateIniSection(newContent, existingContent, "[Token1]", Function(sb)
                                                                          sb.AppendLine($"TOKEN1_NAME = ""{_token1Name}""")
                                                                          sb.AppendLine($"TOKEN1_SYMBOL = ""{If(Token1SymbolTextBox IsNot Nothing, Token1SymbolTextBox.Text.Trim(), DEFAULT_TOKEN1_SYMBOL)}""")
                                                                          sb.AppendLine($"TOKEN1_DECIMALS = {If(Token1DecimalsTextBox IsNot Nothing, Token1DecimalsTextBox.Text.Trim(), DEFAULT_TOKEN1_DECIMALS)}")
                                                                          sb.AppendLine($"TOKEN1_ADDRESS = ""{If(Token1AddressTextBox IsNot Nothing, Token1AddressTextBox.Text.Trim(), DEFAULT_TOKEN1_ADDRESS)}""")
                                                                      End Function)

            ' Escreve no arquivo
            File.WriteAllText(_settingsIniPath, newContent.ToString().Trim(), Encoding.UTF8)

        Catch ex As Exception
            MessageBox.Show($"Erro ao salvar settings.ini: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub UpdateIniSection(ByRef newContent As StringBuilder, existingContent As String, sectionName As String, addSectionContent As Action(Of StringBuilder))
        Dim sectionMatch = Regex.Match(existingContent, $"{Regex.Escape(sectionName)}(.*?)(?=\[|$)", RegexOptions.Singleline)

        If sectionMatch.Success Then
            ' Adiciona todo o conteúdo antes da seção
            newContent.Append(existingContent.Substring(0, sectionMatch.Index))

            ' Adiciona a seção com novo conteúdo
            newContent.AppendLine(sectionName)
            Dim sectionSb As New StringBuilder()
            addSectionContent(sectionSb)
            newContent.Append(sectionSb.ToString())

            ' Adiciona o restante do conteúdo
            newContent.Append(existingContent.Substring(sectionMatch.Index + sectionMatch.Length))
        Else
            ' Se a seção não existe, adiciona no final
            newContent.Append(existingContent)
            If Not String.IsNullOrEmpty(existingContent) AndAlso Not existingContent.EndsWith(Environment.NewLine) Then
                newContent.AppendLine()
            End If
            newContent.AppendLine(sectionName)
            addSectionContent(newContent)
        End If
    End Sub

    Private Sub ResetAllToDefaults()
        ' BuyConfig defaults
        If PoolAddressTextBox IsNot Nothing Then PoolAddressTextBox.Text = DEFAULT_POOL_ADDRESS
        If SwapRouterTextBox IsNot Nothing Then SwapRouterTextBox.Text = DEFAULT_SWAP_ROUTER
        If PoolAddressComboBox IsNot Nothing AndAlso PoolAddressComboBox.Items.Count > 0 Then
            PoolAddressComboBox.SelectedIndex = 0
        End If

        ' Token0 defaults
        _token0Name = DEFAULT_TOKEN0_NAME
        If Token0SymbolTextBox IsNot Nothing Then Token0SymbolTextBox.Text = DEFAULT_TOKEN0_SYMBOL
        If Token0DecimalsTextBox IsNot Nothing Then Token0DecimalsTextBox.Text = DEFAULT_TOKEN0_DECIMALS.ToString()
        If Token0AddressTextBox IsNot Nothing Then Token0AddressTextBox.Text = DEFAULT_TOKEN0_ADDRESS
        If Token0ComboBox IsNot Nothing AndAlso Token0ComboBox.Items.Count > 0 Then
            Token0ComboBox.SelectedIndex = 0
        End If

        ' Token1 defaults
        _token1Name = DEFAULT_TOKEN1_NAME
        If Token1SymbolTextBox IsNot Nothing Then Token1SymbolTextBox.Text = DEFAULT_TOKEN1_SYMBOL
        If Token1DecimalsTextBox IsNot Nothing Then Token1DecimalsTextBox.Text = DEFAULT_TOKEN1_DECIMALS.ToString()
        If Token1AddressTextBox IsNot Nothing Then Token1AddressTextBox.Text = DEFAULT_TOKEN1_ADDRESS
        If Token1ComboBox IsNot Nothing AndAlso Token1ComboBox.Items.Count > 0 Then
            Token1ComboBox.SelectedIndex = 0
        End If
    End Sub

    ' Métodos para BuyConfig
    Private Async Sub LoadPools(sender As Object, e As EventArgs)
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

    Private _allPools As List(Of PoolContract)
    Private Async Function LoadPoolAddresses() As Task
        Try
            Using client As New HttpClient()
                Dim response = Await client.GetAsync(POOLS_JSON_URL)
                response.EnsureSuccessStatusCode()
                Dim json = Await response.Content.ReadAsStringAsync()
                Dim options = New JsonSerializerOptions With {
                .PropertyNameCaseInsensitive = True,
                .AllowTrailingCommas = True
            }

                ' Deserializa para a lista completa de pools
                _allPools = JsonSerializer.Deserialize(Of List(Of PoolContract))(json, options)

                If _allPools IsNot Nothing Then
                    Dim validPools = _allPools.
                    Where(Function(p) p IsNot Nothing AndAlso p.id > 0 AndAlso
                         p.exchange IsNot Nothing AndAlso Not {"CetoEX", "CoinInn", "AAVE"}.Contains(p.exchange)).
                    OrderBy(Function(p) p.pair).
                    ThenBy(Function(p) p.exchange).
                    ToList()

                    If PoolAddressComboBox IsNot Nothing Then
                        PoolAddressComboBox.Invoke(Sub()
                                                       PoolAddressComboBox.BeginUpdate()
                                                       PoolAddressComboBox.Items.Clear()
                                                       PoolAddressComboBox.Items.Add("Selecione um pool...")

                                                       For Each pool In validPools
                                                           If pool.pair IsNot Nothing AndAlso pool.contract IsNot Nothing Then
                                                               PoolAddressComboBox.Items.Add(New PoolAddressItem(
                                    pool.pair, pool.exchange, pool.contract))
                                                           End If
                                                       Next

                                                       ' Seleciona item correspondente ao valor atual
                                                       Dim currentAddress = If(PoolAddressTextBox IsNot Nothing, PoolAddressTextBox.Text.Trim(), "")
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

    Private Sub PoolAddressComboBox_SelectedIndexChanged(sender As Object, e As EventArgs)
        If PoolAddressComboBox.SelectedItem IsNot Nothing AndAlso
       TypeOf PoolAddressComboBox.SelectedItem Is PoolAddressItem Then

            Dim selectedItem = DirectCast(PoolAddressComboBox.SelectedItem, PoolAddressItem)

            ' Atualiza o endereço da pool
            If PoolAddressTextBox IsNot Nothing Then
                PoolAddressTextBox.Text = selectedItem.ContractAddress
            End If

            ' Encontra a pool completa no _allPools
            Dim selectedPool = _allPools?.FirstOrDefault(Function(p) p.contract.Equals(selectedItem.ContractAddress, StringComparison.OrdinalIgnoreCase))

            If selectedPool IsNot Nothing Then
                ' Atualiza os tokens automaticamente
                UpdateTokensFromPool(selectedPool)
            End If
        End If
    End Sub

    Private Sub SaveBuyConfig(sender As Object, e As EventArgs)
        If PoolAddressTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(PoolAddressTextBox.Text) Then
            MessageBox.Show("Por favor, selecione um pool válido.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        If SwapRouterTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(SwapRouterTextBox.Text) Then
            MessageBox.Show("O endereço do roteador de swap é obrigatório.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        SaveAllSettings()
        MessageBox.Show("Configurações de compra salvas com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
    End Sub

    Private Sub ResetBuyConfig(sender As Object, e As EventArgs)
        If PoolAddressTextBox IsNot Nothing Then PoolAddressTextBox.Text = DEFAULT_POOL_ADDRESS
        If SwapRouterTextBox IsNot Nothing Then SwapRouterTextBox.Text = DEFAULT_SWAP_ROUTER
        If PoolAddressComboBox IsNot Nothing AndAlso PoolAddressComboBox.Items.Count > 0 Then
            PoolAddressComboBox.SelectedIndex = 0
        End If
    End Sub

    ' Métodos para Token0
    Private Async Sub LoadToken0List(sender As Object, e As EventArgs)
        Await LoadTokens(Token0ComboBox, Token0AddressTextBox, "Token0")
    End Sub

    Private Sub Token0Selected(sender As Object, e As EventArgs)
        If Token0ComboBox IsNot Nothing AndAlso Token0ComboBox.SelectedItem IsNot Nothing AndAlso
           TypeOf Token0ComboBox.SelectedItem Is Asset AndAlso
           Token0SymbolTextBox IsNot Nothing AndAlso Token0DecimalsTextBox IsNot Nothing AndAlso Token0AddressTextBox IsNot Nothing Then

            Dim selectedAsset = DirectCast(Token0ComboBox.SelectedItem, Asset)
            _token0Name = selectedAsset.name
            Token0SymbolTextBox.Text = selectedAsset.ticker
            Token0DecimalsTextBox.Text = selectedAsset.decimals.ToString()
            Token0AddressTextBox.Text = selectedAsset.contract
        End If
    End Sub

    Private Sub SaveToken0Config(sender As Object, e As EventArgs)
        If Token0SymbolTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(Token0SymbolTextBox.Text) Then
            MessageBox.Show("O símbolo do Token0 é obrigatório.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        If Token0DecimalsTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(Token0DecimalsTextBox.Text) Then
            MessageBox.Show("As casas decimais do Token0 são obrigatórias.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        If Token0AddressTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(Token0AddressTextBox.Text) Then
            MessageBox.Show("O endereço do Token0 é obrigatório.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        SaveAllSettings()
        MessageBox.Show("Configurações do Token0 salvas com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
    End Sub

    Private Sub ResetToken0Config(sender As Object, e As EventArgs)
        _token0Name = DEFAULT_TOKEN0_NAME
        If Token0SymbolTextBox IsNot Nothing Then Token0SymbolTextBox.Text = DEFAULT_TOKEN0_SYMBOL
        If Token0DecimalsTextBox IsNot Nothing Then Token0DecimalsTextBox.Text = DEFAULT_TOKEN0_DECIMALS.ToString()
        If Token0AddressTextBox IsNot Nothing Then Token0AddressTextBox.Text = DEFAULT_TOKEN0_ADDRESS
        If Token0ComboBox IsNot Nothing AndAlso Token0ComboBox.Items.Count > 0 Then
            Token0ComboBox.SelectedIndex = 0
        End If
    End Sub

    ' Métodos para Token1
    Private Async Sub LoadToken1List(sender As Object, e As EventArgs)
        Await LoadTokens(Token1ComboBox, Token1AddressTextBox, "Token1")
    End Sub

    Private Sub Token1Selected(sender As Object, e As EventArgs)
        If Token1ComboBox IsNot Nothing AndAlso Token1ComboBox.SelectedItem IsNot Nothing AndAlso
           TypeOf Token1ComboBox.SelectedItem Is Asset AndAlso
           Token1SymbolTextBox IsNot Nothing AndAlso Token1DecimalsTextBox IsNot Nothing AndAlso Token1AddressTextBox IsNot Nothing Then

            Dim selectedAsset = DirectCast(Token1ComboBox.SelectedItem, Asset)
            _token1Name = selectedAsset.name
            Token1SymbolTextBox.Text = selectedAsset.ticker
            Token1DecimalsTextBox.Text = selectedAsset.decimals.ToString()
            Token1AddressTextBox.Text = selectedAsset.contract
        End If
    End Sub

    Private Sub SaveToken1Config(sender As Object, e As EventArgs)
        If Token1SymbolTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(Token1SymbolTextBox.Text) Then
            MessageBox.Show("O símbolo do Token1 é obrigatório.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        If Token1DecimalsTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(Token1DecimalsTextBox.Text) Then
            MessageBox.Show("As casas decimais do Token1 são obrigatórias.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        If Token1AddressTextBox IsNot Nothing AndAlso String.IsNullOrWhiteSpace(Token1AddressTextBox.Text) Then
            MessageBox.Show("O endereço do Token1 é obrigatório.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        SaveAllSettings()
        MessageBox.Show("Configurações do Token1 salvas com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
    End Sub

    Private Sub ResetToken1Config(sender As Object, e As EventArgs)
        _token1Name = DEFAULT_TOKEN1_NAME
        If Token1SymbolTextBox IsNot Nothing Then Token1SymbolTextBox.Text = DEFAULT_TOKEN1_SYMBOL
        If Token1DecimalsTextBox IsNot Nothing Then Token1DecimalsTextBox.Text = DEFAULT_TOKEN1_DECIMALS.ToString()
        If Token1AddressTextBox IsNot Nothing Then Token1AddressTextBox.Text = DEFAULT_TOKEN1_ADDRESS
        If Token1ComboBox IsNot Nothing AndAlso Token1ComboBox.Items.Count > 0 Then
            Token1ComboBox.SelectedIndex = 0
        End If
    End Sub

    ' Método compartilhado para carregar tokens
    Private Async Function LoadTokens(comboBox As ComboBox, addressTextBox As TextBox, tokenType As String) As Task
        Try
            Using client As New HttpClient()
                Dim response = Await client.GetAsync(TOKENS_JSON_URL)
                response.EnsureSuccessStatusCode()
                Dim json = Await response.Content.ReadAsStringAsync()
                Dim assets = JsonSerializer.Deserialize(Of List(Of Asset))(json)

                If assets IsNot Nothing Then
                    Dim polygonAssets = assets.Where(Function(a) a.network = "polygon").ToList()
                    Dim currentAddress = If(addressTextBox IsNot Nothing, addressTextBox.Text.Trim(), "")

                    If comboBox IsNot Nothing Then
                        comboBox.Invoke(Sub()
                                            comboBox.BeginUpdate()
                                            comboBox.Items.Clear()
                                            comboBox.Items.Add($"Selecione um {tokenType}...")

                                            Dim currentIndex As Integer = -1

                                            For Each asset In polygonAssets
                                                comboBox.Items.Add(asset)

                                                If asset.contract.Equals(currentAddress, StringComparison.OrdinalIgnoreCase) Then
                                                    currentIndex = comboBox.Items.Count - 1
                                                End If
                                            Next

                                            If currentIndex > 0 Then
                                                comboBox.SelectedIndex = currentIndex
                                            Else
                                                comboBox.SelectedIndex = 0
                                            End If

                                            comboBox.DisplayMember = "name"
                                            comboBox.ValueMember = "contract"
                                            comboBox.EndUpdate()
                                        End Sub)
                    End If
                End If
            End Using
        Catch ex As Exception
            MessageBox.Show($"Erro ao carregar {tokenType}: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Function

    Public Sub Cleanup()
        ' Remove handlers para BuyConfig
        If SaveButton IsNot Nothing Then RemoveHandler SaveButton.Click, AddressOf SaveBuyConfig
        If ResetButton IsNot Nothing Then RemoveHandler ResetButton.Click, AddressOf ResetBuyConfig
        If LoadPoolsButton IsNot Nothing Then RemoveHandler LoadPoolsButton.Click, AddressOf LoadPools
        If PoolAddressComboBox IsNot Nothing Then RemoveHandler PoolAddressComboBox.SelectedIndexChanged, AddressOf PoolAddressComboBox_SelectedIndexChanged

        ' Remove handlers para Token0
        If Token0SaveButton IsNot Nothing Then RemoveHandler Token0SaveButton.Click, AddressOf SaveToken0Config
        If Token0ResetButton IsNot Nothing Then RemoveHandler Token0ResetButton.Click, AddressOf ResetToken0Config
        If Token0LoadButton IsNot Nothing Then RemoveHandler Token0LoadButton.Click, AddressOf LoadToken0List
        If Token0ComboBox IsNot Nothing Then RemoveHandler Token0ComboBox.SelectedIndexChanged, AddressOf Token0Selected

        ' Remove handlers para Token1
        If Token1SaveButton IsNot Nothing Then RemoveHandler Token1SaveButton.Click, AddressOf SaveToken1Config
        If Token1ResetButton IsNot Nothing Then RemoveHandler Token1ResetButton.Click, AddressOf ResetToken1Config
        If Token1LoadButton IsNot Nothing Then RemoveHandler Token1LoadButton.Click, AddressOf LoadToken1List
        If Token1ComboBox IsNot Nothing Then RemoveHandler Token1ComboBox.SelectedIndexChanged, AddressOf Token1Selected
    End Sub

    Private Class PoolContract
        Public Property id As Integer
        Public Property pair As String
        Public Property contract_a As String    ' Contrato do Token A (ex: PLT)
        Public Property contract_b As String    ' Contrato do Token B (ex: USDT)
        Public Property contract As String      ' Contrato da pool
        Public Property exchange As String
        Public Property liquidity As Decimal
    End Class
    Private Sub UpdateTokensFromPool(pool As PoolContract)
        ' Verifica se temos os ComboBoxes de tokens carregados
        If Token0ComboBox Is Nothing OrElse Token1ComboBox Is Nothing Then Return

        ' Função para encontrar e selecionar um token no ComboBox
        Dim selectToken = Sub(comboBox As ComboBox, contractAddress As String)
                              If comboBox Is Nothing OrElse String.IsNullOrEmpty(contractAddress) Then Return

                              For i = 0 To comboBox.Items.Count - 1
                                  If TypeOf comboBox.Items(i) Is Asset Then
                                      Dim asset = DirectCast(comboBox.Items(i), Asset)
                                      If asset.contract.Equals(contractAddress, StringComparison.OrdinalIgnoreCase) Then
                                          comboBox.SelectedIndex = i
                                          Exit For
                                      End If
                                  End If
                              Next
                          End Sub

        ' Determina qual token é Token0 e qual é Token1 com base no par (PLT/USDT)
        Dim pairParts = pool.pair.Split("/"c)
        If pairParts.Length = 2 Then
            ' Assume que o primeiro token no par é Token1 (PLT) e o segundo é Token0 (USDT/USDC/etc)
            ' Você pode ajustar esta lógica conforme necessário
            Dim token1Contract = pool.contract_a
            Dim token0Contract = pool.contract_b

            ' Seleciona os tokens nos ComboBoxes
            Token0ComboBox.Invoke(Sub() selectToken(Token0ComboBox, token0Contract))
            Token1ComboBox.Invoke(Sub() selectToken(Token1ComboBox, token1Contract))

            ' Atualiza os TextBoxes se os tokens não estiverem na lista
            If Token0AddressTextBox IsNot Nothing AndAlso
               (Token0ComboBox.SelectedItem Is Nothing OrElse Token0ComboBox.SelectedIndex = 0) Then
                Token0AddressTextBox.Text = token0Contract
            End If

            If Token1AddressTextBox IsNot Nothing AndAlso
               (Token1ComboBox.SelectedItem Is Nothing OrElse Token1ComboBox.SelectedIndex = 0) Then
                Token1AddressTextBox.Text = token1Contract
            End If
        End If
    End Sub
End Class