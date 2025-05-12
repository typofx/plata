Imports System.Data.SQLite
Imports System.IO
Imports System.Runtime
Imports System.Text

Public Class FormConfiguracao
    ' String de conexão com o SQLite
    Private Shared ReadOnly ConnectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"

    ' Declaração dos helpers
    Private _buyHelper As BuyConfigHelper
    Private _sellHelper As SellConfigHelper
    ' Private _sellConfigEditor As SellConfigEditor
    ' Private _buyConfigEditor As BuyConfigEditor
    ' Private _tokenConfigEditor As TokenConfigEditor
    'Private _token1ConfigEditor As Token1ConfigEditor
    Private _delayEditor As DelayConfigEditor
    Private _machineRegistry As MachineRegistry
    Private _settingsManager As SettingsIniManager
    Private _unifiedConfigEditor As UnifiedConfigEditor


    ' Método para carregar os caminhos no DataGridView
    Private Sub CarregarCaminhos()
        Dim caminhos As DataTable = ListarCaminhos()
        dgvCaminhos.DataSource = caminhos
    End Sub

    ' Método para listar todos os caminhos
    Private Function ListarCaminhos() As DataTable
        Dim dataTable As New DataTable()

        Using connection As New SQLiteConnection(ConnectionString)
            connection.Open()
            Dim query As String = "SELECT Id, Chave, Valor FROM Configuracoes"
            Using command As New SQLiteCommand(query, connection)
                Using adapter As New SQLiteDataAdapter(command)
                    adapter.Fill(dataTable)
                End Using
            End Using
        End Using

        Return dataTable
    End Function

    ' Método para adicionar um novo caminho
    Private Sub AdicionarCaminho(chave As String, valor As String)
        Using connection As New SQLiteConnection(ConnectionString)
            connection.Open()
            Dim query As String = "INSERT INTO Configuracoes (Chave, Valor) VALUES (@Chave, @Valor)"
            Using command As New SQLiteCommand(query, connection)
                command.Parameters.AddWithValue("@Chave", chave)
                command.Parameters.AddWithValue("@Valor", valor)
                command.ExecuteNonQuery()
            End Using
        End Using
    End Sub

    ' Método para editar um caminho existente
    Private Sub EditarCaminho(id As Integer, chave As String, novoValor As String)
        Using connection As New SQLiteConnection(ConnectionString)
            connection.Open()
            Dim query As String = "UPDATE Configuracoes SET Chave = @Chave, Valor = @Valor WHERE Id = @Id"
            Using command As New SQLiteCommand(query, connection)
                command.Parameters.AddWithValue("@Id", id)
                command.Parameters.AddWithValue("@Chave", chave)
                command.Parameters.AddWithValue("@Valor", novoValor)
                command.ExecuteNonQuery()
            End Using
        End Using
    End Sub

    ' Método para excluir um caminho
    Private Sub ExcluirCaminho(id As Integer)
        Using connection As New SQLiteConnection(ConnectionString)
            connection.Open()
            Dim query As String = "DELETE FROM Configuracoes WHERE Id = @Id"
            Using command As New SQLiteCommand(query, connection)
                command.Parameters.AddWithValue("@Id", id)
                command.ExecuteNonQuery()
            End Using
        End Using
    End Sub

    ' Evento Load do formulário
    Private Sub FormConfiguracao_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        PreencherComboNetwork()
        cmbTokens.DropDownStyle = ComboBoxStyle.Simple ' Impede a abertura da lista
        cmbTokens.Enabled = False

        cmbToken1List.DropDownStyle = ComboBoxStyle.Simple ' Impede a abertura da lista
        cmbToken1List.Enabled = False

        ' 1. Inicializa primeiro a instância
        _machineRegistry = MachineRegistry.Instance

        ' 2. Configurações de layout
        AjustarLayoutComponentes()
        Me.AutoScroll = True
        Me.AutoScrollMinSize = New Size(0, 1500)

        ' 3. Carrega os países no ComboBox
        _machineRegistry.LoadCountries(cmbPaisMaquina)
        MachineRegistry.Instance.LoadBeOuTeeOptions(cmbBeOuTee)

        ' 4. Carrega os dados da máquina nos controles
        _machineRegistry.LoadMachineToControls(
        txtNomeMaquina, txtNumeroSerie, txtLocalizacao,
        cmbPaisMaquina, txtCidade, txtResponsavel, txtObservacoes, nudScriptNumber, cmbBeOuTee, nudBotEnable)

        ' 5. Carrega outras configurações
        CarregarCaminhos()

        ' 6. Obtém diretório de trabalho
        Dim workingDir As String = BuscarConfiguracao("WorkingDirectory")

        ' 7. Inicializa o SettingsManager ANTES de tentar ler as configurações
        Try
            _settingsManager = New SettingsIniManager(workingDir)

            ' Agora carrega o estado do checkbox
            Try
                Dim maintainRatioValue As String = _settingsManager.GetSetting("Ratio", "MAINTAIN_RATIO")
                ' MessageBox.Show($"Valor lido do INI para MAINTAIN_RATIO: {maintainRatioValue}") ' Debug
                If Not String.IsNullOrEmpty(maintainRatioValue) Then
                    CHKmaintainRatio.Checked = (maintainRatioValue = "1")
                    ' MessageBox.Show($"Checkbox será marcado? {CHKmaintainRatio.Checked}") ' Debug
                End If
            Catch ex As Exception
                'MessageBox.Show($"Erro ao ler maintainRatio: {ex.Message}") ' Debug
                CHKmaintainRatio.Checked = False
            End Try


            Try
                ' Carrega o estado do checkbox de volatilidade
                Dim volatilityCheckValue As String = _settingsManager.GetSetting("VolatilityCheck", "VOLATILITY_CHECK")
                If Not String.IsNullOrEmpty(volatilityCheckValue) Then
                    chkVolatility.Checked = (volatilityCheckValue = "1")
                End If
            Catch ex As Exception
                chkVolatility.Checked = False ' Valor padrão se ocorrer erro
            End Try

        Catch ex As Exception
            MessageBox.Show($"Erro ao inicializar gerenciador de configurações: {ex.Message}", "Erro",
                  MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        ' 8. Inicializa os demais helpers
        Try
            InitializeBuyHelper(workingDir)
            InitializeSellHelper(workingDir)
            InitializeBuyConfigEditor(workingDir)
            InitializeDelayEditor()
        Catch ex As Exception
            MessageBox.Show($"Erro ao inicializar configurações: {ex.Message}", "Erro",
                  MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try

        ' 9. Carrega os dados
        btnLoadBuyPools.PerformClick()
        btnLoadToken1List.PerformClick()
        btnLoadTokens.PerformClick()
    End Sub

    Private Sub SalvarConfiguracoesIni()
        ' Obtém os valores numéricos da volatilidade
        Dim buyVolValue As String = ObterValorVolatilidade(cmbBuyVolatility.SelectedIndex)
        Dim sellVolValue As String = ObterValorVolatilidade(cmbSellVolatility.SelectedIndex)

        ' Obtém nomes dos tokens dos ComboBoxes
        Dim token0Name As String = If(cmbTokens.SelectedItem IsNot Nothing AndAlso TypeOf cmbTokens.SelectedItem Is UnifiedConfigEditor.Asset,
                                 DirectCast(cmbTokens.SelectedItem, UnifiedConfigEditor.Asset).name, "Wrapped Matic")

        Dim token1Name As String = If(cmbToken1List.SelectedItem IsNot Nothing AndAlso TypeOf cmbToken1List.SelectedItem Is UnifiedConfigEditor.Asset,
                                 DirectCast(cmbToken1List.SelectedItem, UnifiedConfigEditor.Asset).name, "Plata Token")


        ' Adiciona o estado do checkbox (1 para marcado, 0 para desmarcado)
        Dim maintainRatioValue As String = If(CHKmaintainRatio.Checked, "1", "0")
        Dim chkVolatilityValue As String = If(chkVolatility.Checked, "1", "0")


        Dim networkUrl As String = ""
        If cmbNetwork.SelectedItem IsNot Nothing Then
            Dim selectedNetwork = DirectCast(cmbNetwork.SelectedItem, KeyValuePair(Of String, String))
            networkUrl = selectedNetwork.Value
        End If

        _settingsManager.UpdateSettings(
        buyAmount:=txtBuyAmount.Text,
        buyVolatility:=buyVolValue,
        sellAmount:=txtSellBaseAmount.Text,
        sellVolatility:=sellVolValue,
        poolAddress:=txtBuyPoolAddress.Text,
        routerAddress:=txtBuySwapRouter.Text,
        token0Name:=token0Name,
        token0Symbol:=txtTokenSymbol.Text,
        token0Decimals:=txtTokenDecimals.Text,
        token0Address:=txtTokenAddress.Text,
        token1Name:=token1Name,
        token1Symbol:=txtToken1Symbol.Text,
        token1Decimals:=txtToken1Decimals.Text,
        token1Address:=txtToken1Address.Text,
        approveDelay:=txtTempo1.Text,
        cycleDelay:=txtTempo3.Text,
        maintainRatio:=maintainRatioValue,
         volatilityCheck:=chkVolatilityValue,
        networkUrl:=networkUrl
    )
    End Sub
    Private Function ObterValorVolatilidade(index As Integer) As String
        Select Case index
            Case 0 : Return "0.1" ' Low (10%)
            Case 1 : Return "0.2" ' Medium (20%)
            Case 2 : Return "0.3" ' High (30%)
            Case 3 : Return "0.4" ' Very High (40%)
            Case 4 : Return "0.5" ' Extreme (50%)
            Case Else : Return "0.2" ' Default (Medium)
        End Select
    End Function

    Private Sub cmbPaisMaquina_SelectedIndexChanged(sender As Object, e As EventArgs) Handles cmbPaisMaquina.SelectedIndexChanged
        Dim selectedCountry = _machineRegistry.GetSelectedCountry(cmbPaisMaquina)
        If selectedCountry IsNot Nothing Then
            ' Você pode usar os dados do país aqui se necessário
            ' Exemplo: txtCodigoPais.Text = selectedCountry.PhoneCode
        End If
    End Sub




    Private Sub EnableDelayControls()
        txtTempo1.Enabled = True

        txtTempo3.Enabled = True
        btnSaveDelays.Enabled = True
        btnResetDelays.Enabled = True
    End Sub

    Private Sub DisableDelayControls()
        txtTempo1.Enabled = False

        txtTempo3.Enabled = False
        btnSaveDelays.Enabled = False
        btnResetDelays.Enabled = False
    End Sub


    Private Sub InitializeDelayEditor()
        Try
            _delayEditor = New DelayConfigEditor()

            ' Associação dos controles
            With _delayEditor
                .txtTempo1 = txtTempo1

                .txtTempo3 = txtTempo3
                .btnSave = btnSaveDelays
                .btnReset = btnResetDelays
            End With

            ' Inicializa valores
            _delayEditor.Initialize()
        Catch ex As Exception
            MessageBox.Show($"Erro ao inicializar editor de tempos: {ex.Message}", "Erro",
                      MessageBoxButtons.OK, MessageBoxIcon.Error)
            DisableDelayControls()
        End Try
    End Sub


    'Private Sub InitializeTokenConfigEditor(workingDir As String)
    '   _tokenConfigEditor = New TokenConfigEditor(workingDir)

    ' Associação dos controles
    ' With _tokenConfigEditor

    '.SymbolTextBox = txtTokenSymbol
    '.DecimalsTextBox = txtTokenAddress
    '.AddressTextBox = txtTokenDecimals
    '.SaveButton = btnSaveTokenConfig
    '.ResetButton = btnResetTokenConfig
    'kensComboBox = cmbTokens
    '.LoadTokensButton = btnLoadTokens
    ' End With

    ' Inicializa valores
    '     _tokenConfigEditor.Initialize()
    '  End Sub

    '  Private Sub InitializeToken1ConfigEditor(workingDir As String)
    '   _token1ConfigEditor = New Token1ConfigEditor(workingDir)
    '
    ' With _token1ConfigEditor
    ' .txtToken1Name = txtToken1Name
    'xtToken1Symbol = txtToken1Symbol
    '.txtToken1Decimals = txtToken1Decimals
    '.txtToken1Address = txtToken1Address
    '   .btnSaveToken1Config = btnSaveToken1Config
    '  .btnResetToken1Config = btnResetToken1Config
    ' .cmbToken1List = cmbToken1List
    ' .btnLoadToken1List = btnLoadToken1List
    ' End With

    '    _token1ConfigEditor.Initialize()
    ' End Sub

    'Private Sub InitializeSellConfigEditor(workingDir As String)
    ' _sellConfigEditor = New SellConfigEditor(workingDir)

    ' Associação dos controles
    'With _sellConfigEditor
    ' .PoolAddressTextBox = txtPoolAddress
    ' .SwapRouterTextBox = txtSwapRouter
    '  .SaveButton = btnSaveSellAddresses
    ' .ResetButton = btnResetSellAddresses
    ' .PoolAddressComboBox = cmbPoolAddresses
    '.LoadPoolsButton = btnLoadPools

    'End With

    ' Inicializa valores
    '  _sellConfigEditor.Initialize()
    'End Sub

    '   Private Sub InitializeBuyConfigEditor(workingDir As String)
    '    _buyConfigEditor = New BuyConfigEditor(workingDir)

    ' Associação dos controles
    '  With _buyConfigEditor
    '         .PoolAddressTextBox = txtBuyPoolAddress
    '   .SwapRouterTextBox = txtBuySwapRouter
    '   .SaveButton = btnSaveBuyAddresses
    '   .ResetButton = btnResetBuyAddresses
    '   .PoolAddressComboBox = cmbBuyPoolAddresses
    '   .LoadPoolsButton = btnLoadBuyPools
    '  End With

    ' Inicializa valores
    '     _buyConfigEditor.Initialize()
    '  End Sub

    Private Sub InitializeBuyConfigEditor(workingDir As String)
        _unifiedConfigEditor = New UnifiedConfigEditor(workingDir)

        ' Associação dos controles para BuyConfig
        With _unifiedConfigEditor
            ' Configurações de compra
            .PoolAddressTextBox = txtBuyPoolAddress
            .SwapRouterTextBox = txtBuySwapRouter
            .SaveButton = btnSaveBuyAddresses
            .ResetButton = btnResetBuyAddresses
            .PoolAddressComboBox = cmbBuyPoolAddresses
            .LoadPoolsButton = btnLoadBuyPools
            '  .LoadingLabel = lblLoadingBuy
            '  .LoadingProgressBar = pbLoadingBuy

            ' Configurações para Token0 (TokenConfig)
            .Token0SymbolTextBox = txtTokenSymbol
            .Token0DecimalsTextBox = txtTokenDecimals
            .Token0AddressTextBox = txtTokenAddress
            .Token0SaveButton = btnSaveTokenConfig
            .Token0ResetButton = btnResetTokenConfig
            .Token0ComboBox = cmbTokens
            .Token0LoadButton = btnLoadTokens

            ' Configurações para Token1 (Token1Config)
            .Token1SymbolTextBox = txtToken1Symbol
            .Token1DecimalsTextBox = txtToken1Decimals
            .Token1AddressTextBox = txtToken1Address
            .Token1SaveButton = btnSaveToken1Config
            .Token1ResetButton = btnResetToken1Config
            .Token1ComboBox = cmbToken1List
            .Token1LoadButton = btnLoadToken1List
        End With

        ' Inicializa valores
        _unifiedConfigEditor.Initialize()
    End Sub


    Private Sub InitializeBuyHelper(workingDir As String)
        _buyHelper = New BuyConfigHelper(workingDir)

        ' Configura controles de compra
        With _buyHelper
            .AmountTextBox = txtBuyAmount
            .VolatilityComboBox = cmbBuyVolatility
            .SaveButton = btnSaveBuyConfig
            .ResetButton = btnResetBuyConfig
        End With

        ' Inicializa valores
        _buyHelper.Initialize()
    End Sub

    Private Sub InitializeSellHelper(workingDir As String)
        _sellHelper = New SellConfigHelper(workingDir)

        ' Configura controles de venda
        With _sellHelper
            .BaseAmountTextBox = txtSellBaseAmount
            .VolatilityComboBox = cmbSellVolatility
            .SaveButton = btnSaveSellConfig
            .ResetButton = btnResetSellConfig
        End With

        ' Inicializa valores
        _sellHelper.Initialize()
    End Sub

    ' Evento FormClosing unificado
    Private Sub FormConfiguracao_FormClosing(sender As Object, e As FormClosingEventArgs) Handles MyBase.FormClosing
        ' Limpa recursos de ambos helpers
        _buyHelper?.Cleanup()
        _sellHelper?.Cleanup()
        '_sellConfigEditor?.Cleanup()
        '   _buyConfigEditor?.Cleanup()
        '   _tokenConfigEditor?.Cleanup()
        '   _token1ConfigEditor?.Cleanup()
        _unifiedConfigEditor?.Cleanup()
        _delayEditor?.Cleanup()
        _machineRegistry = Nothing
    End Sub

    ' Botão para adicionar um novo caminho
    Private Sub btnAdicionar_Click(sender As Object, e As EventArgs) Handles btnAdicionar.Click
        Dim chave As String = txtChave.Text.Trim()
        Dim valor As String = txtValor.Text.Trim()

        If String.IsNullOrEmpty(chave) Or String.IsNullOrEmpty(valor) Then
            MessageBox.Show("Preencha a chave e o valor.", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return
        End If

        Try
            AdicionarCaminho(chave, valor)
            CarregarCaminhos()
            MessageBox.Show("Caminho adicionado com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
        Catch ex As Exception
            MessageBox.Show("Erro ao adicionar caminho: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' Evento de clique nas células do DataGridView
    Private Sub dgvCaminhos_CellContentClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgvCaminhos.CellContentClick
        ' Verifica se o clique foi em uma célula de botão e se não é o cabeçalho
        If e.RowIndex >= 0 AndAlso (dgvCaminhos.Columns(e.ColumnIndex).Name = "colEditar" OrElse dgvCaminhos.Columns(e.ColumnIndex).Name = "colExcluir") Then
            ' Obtém o Id, a chave e o valor da linha clicada
            Dim id As Integer = Convert.ToInt32(dgvCaminhos.Rows(e.RowIndex).Cells("colId").Value)
            Dim chave As String = dgvCaminhos.Rows(e.RowIndex).Cells("colChave").Value.ToString()
            Dim valor As String = dgvCaminhos.Rows(e.RowIndex).Cells("colValor").Value.ToString()

            ' Verifica se o botão clicado foi "Editar"
            If dgvCaminhos.Columns(e.ColumnIndex).Name = "colEditar" Then
                ' Abre o formulário de edição como um modal
                Using formEditar As New FormEditar()
                    formEditar.Id = id
                    formEditar.Chave = chave
                    formEditar.Valor = valor

                    ' Exibe o modal
                    If formEditar.ShowDialog() = DialogResult.OK Then
                        ' Recarrega os dados no DataGridView após a edição
                        CarregarCaminhos()
                    End If
                End Using
            End If

            ' Verifica se o botão clicado foi "Excluir"
            If dgvCaminhos.Columns(e.ColumnIndex).Name = "colExcluir" Then
                ' Confirma a exclusão
                Dim confirmacao As DialogResult = MessageBox.Show("Tem certeza que deseja excluir a chave '" & chave & "'?", "Confirmar Exclusão", MessageBoxButtons.YesNo, MessageBoxIcon.Question)
                If confirmacao = DialogResult.Yes Then
                    Try
                        ExcluirCaminho(id)
                        CarregarCaminhos()
                        MessageBox.Show("Caminho excluído com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    Catch ex As Exception
                        MessageBox.Show("Erro ao excluir caminho: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    End Try
                End If
            End If
        End If
    End Sub

    Public Shared Function BuscarConfiguracao(chaveBusca As String) As String
        Try
            ' Verifica se a chave é válida
            If String.IsNullOrEmpty(chaveBusca) Then
                MessageBox.Show("A chave de busca não pode ser vazia.", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return String.Empty
            End If

            ' Obtém a connection string
            Dim connectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"

            ' Conecta ao banco de dados
            Using connection As New SQLiteConnection(connectionString)
                connection.Open()

                ' Query para buscar o valor
                Dim query As String = "SELECT Valor FROM Configuracoes WHERE Chave = @ChaveBusca"
                Using command As New SQLiteCommand(query, connection)
                    command.Parameters.AddWithValue("@ChaveBusca", chaveBusca)

                    ' Executa a query
                    Dim resultado As Object = command.ExecuteScalar()

                    If resultado IsNot Nothing Then
                        Return resultado.ToString()
                    Else
                        Return String.Empty
                    End If
                End Using
            End Using
        Catch ex As SQLiteException
            MessageBox.Show($"Erro ao buscar configuração: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return String.Empty
        Catch ex As Exception
            MessageBox.Show($"Erro inesperado: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return String.Empty
        End Try
    End Function

    Public Function ObterValorPorChave(chave As String) As String
        Try
            ' Verifica se a chave é válida
            If String.IsNullOrEmpty(chave) Then
                MessageBox.Show("A chave não pode ser vazia.", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return String.Empty
            End If

            ' Conecta ao banco de dados
            Using connection As New SQLiteConnection(ConnectionString)
                connection.Open()

                ' Query para buscar o valor da chave
                Dim query As String = "SELECT Valor FROM Configuracoes WHERE Chave = @Chave"
                Using command As New SQLiteCommand(query, connection)
                    command.Parameters.AddWithValue("@Chave", chave)

                    ' Executa a query e obtém o resultado
                    Dim resultado As Object = command.ExecuteScalar()

                    ' Verifica se o resultado não é nulo
                    If resultado IsNot Nothing Then
                        Return resultado.ToString()
                    Else
                        MessageBox.Show($"Chave '{chave}' não encontrada na tabela Configuracoes.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                        Return String.Empty
                    End If
                End Using
            End Using
        Catch ex As SQLiteException
            MessageBox.Show($"Erro no banco de dados: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return String.Empty
        Catch ex As Exception
            MessageBox.Show($"Erro ao obter valor por chave: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return String.Empty
        End Try
    End Function
    ' Classe interna para controle de mensagens
    Private Sub btnSalvarIni_Click(sender As Object, e As EventArgs) Handles btnSalvarIni.Click
        Dim result = MessageBox.Show("Deseja salvar TODAS as configurações no setup?",
                                         "Salvar Tudo",
                                         MessageBoxButtons.YesNo,
                                         MessageBoxIcon.Question)

        If result = DialogResult.No Then Return

        ' Desabilita temporariamente o botão
        btnSalvarIni.Enabled = False
        Cursor.Current = Cursors.WaitCursor

        ' Lista de botões na ordem de salvamento (apenas nomes, sem atribuição)
        Dim buttonsToSave As New List(Of Button) From {
        btnSaveDelays,
        btnSalvarMaquina
    }

        Dim successCount = 0
        Dim errorMessages As New List(Of String)

        ' Primeiro executa todos os salvamentos individuais
        For Each btn In buttonsToSave
            Try
                ' Apenas execute o clique, sem tentar atribuir resultado
                btn.PerformClick
                successCount += 1
            Catch ex As Exception
                errorMessages.Add($"{btn.Name.Replace("btnSave", "")}: {ex.Message}")
            End Try
        Next

        ' Depois salva no INI
        Try
            SalvarConfiguracoesIni
            successCount += 1
        Catch ex As Exception
            errorMessages.Add($"settings.ini: {ex.Message}")
        End Try

        ' Restaura UI
        btnSalvarIni.Enabled = True
        Cursor.Current = Cursors.Default

        ' Exibe relatório
        ShowSaveReport(successCount, buttonsToSave.Count + 1, errorMessages)
    End Sub

    Private Sub ShowSaveReport(successCount As Integer, totalOperations As Integer, errorMessages As List(Of String))
        Dim message As New StringBuilder()
        message.AppendLine("Relatório de Salvamento")
        message.AppendLine("-----------------------")
        message.AppendLine($"Operações bem-sucedidas: {successCount}/{totalOperations}")

        If errorMessages.Any() Then
            message.AppendLine()
            message.AppendLine("Erros encontrados:")
            For Each [error] In errorMessages
                message.AppendLine($"- {[error]}")
            Next
        End If

        MessageBox.Show(message.ToString(),
                      "Resultado do Salvamento",
                      MessageBoxButtons.OK,
                      If(errorMessages.Any(), MessageBoxIcon.Warning, MessageBoxIcon.Information))
    End Sub


    Private Sub AjustarLayoutComponentes()

        Dim botoes As New List(Of Button) From {
            btnSaveBuyConfig,
            btnSaveSellConfig,
            btnSaveBuyAddresses,
            btnSaveTokenConfig,
            btnSaveToken1Config,
            btnSaveDelays
        }

        For Each btn In botoes
            If btn IsNot Nothing Then

                btn.Visible = True
                btn.Enabled = True
                btn.Size = New Size(1, 1)
                btn.Location = New Point(-100, -100)
                btn.BackColor = Color.Transparent
                btn.ForeColor = Color.Transparent
                btn.FlatStyle = FlatStyle.Flat
                btn.FlatAppearance.BorderSize = 0
                btn.Text = ""
                btn.TabStop = False
            End If
        Next
    End Sub
    Private Sub btnSalvar_Click(sender As Object, e As EventArgs) Handles btnSalvarMaquina.Click
        Try
            MachineRegistry.Instance.SaveMachineFromControls(
                txtNomeMaquina, txtNumeroSerie, txtLocalizacao,
                cmbPaisMaquina, txtCidade, txtResponsavel, txtObservacoes, nudScriptNumber, cmbBeOuTee, nudBotEnable)

            'MessageBox.Show("Configurações da máquina salvas com sucesso!", "Sucesso",
            'MessageBoxButtons.OK, MessageBoxIcon.Information)
        Catch ex As Exception
            MessageBox.Show(ex.Message, "Erro ao salvar",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub CHKmaintainRatio_CheckedChanged(sender As Object, e As EventArgs) Handles CHKmaintainRatio.CheckedChanged
        If GBXsettings.Enabled = False Then
            GBXsettings.Enabled = True
        Else
            GBXsettings.Enabled = False
        End If
    End Sub

    Private Sub chkVolatility_CheckedChanged(sender As Object, e As EventArgs) Handles chkVolatility.CheckedChanged
        If cmbBuyVolatility.Enabled = False Then
            cmbBuyVolatility.Enabled = True
        Else
            cmbBuyVolatility.Enabled = False
        End If
    End Sub
    Private Sub PreencherComboNetwork()


        cmbNetwork.Items.Add(New KeyValuePair(Of String, String)("Polygon (PoS)", "https://polygon-rpc.com"))
        cmbNetwork.Items.Add(New KeyValuePair(Of String, String)("Ethereum L1", "https://mainnet.infura.io/v3/CHAVE")) ' precisa de chave
        cmbNetwork.Items.Add(New KeyValuePair(Of String, String)("Arbitrum One", "https://arb1.arbitrum.io/rpc"))
        cmbNetwork.Items.Add(New KeyValuePair(Of String, String)("BNB Smart Chain", "https://bsc-dataseed.binance.org"))
        ' Configura o ComboBox para mostrar apenas a chave (nome da rede)
        cmbNetwork.DisplayMember = "Key"
        cmbNetwork.ValueMember = "Value"

        ' Define "Polygon" como item selecionado por padrão
        cmbNetwork.SelectedIndex = 0
    End Sub



    Private Sub btnConvertBinToIni_Click(sender As Object, e As EventArgs) Handles btnConvertBinToIni.Click
        Dim binContent As String = JsConfigUpdater.LoadBinFile()
        If String.IsNullOrEmpty(binContent) Then Return
        Dim iniContent As String = JsConfigUpdater.ConvertBinToIni(binContent)
        Dim workingDir As String = BuscarConfiguracao("WorkingDirectory")
        Dim levelsToGoUp As Integer = 1

        Dim targetDir As String = GetParentDirectoryByLevels(workingDir, levelsToGoUp)

        If String.IsNullOrEmpty(targetDir) Then
            MessageBox.Show($"Não foi possível subir {levelsToGoUp} níveis", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return
        End If

        Dim iniFilePath As String = Path.Combine(targetDir, "settings.ini")

        Try
            File.WriteAllText(iniFilePath, iniContent, Encoding.UTF8)
            MessageBox.Show($"Setup carregado", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
            ' Recarrega o formulário após salvar com sucesso
            Me.Controls.Clear()
            InitializeComponent()
            FormConfiguracao_Load(Nothing, EventArgs.Empty)
        Catch ex As Exception
            MessageBox.Show($"Erro ao salvar: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Function GetParentDirectoryByLevels(currentPath As String, levels As Integer) As String
        If levels <= 0 Then Return currentPath

        Dim parent As DirectoryInfo = Directory.GetParent(currentPath)
        If parent Is Nothing Then Return Nothing

        Return GetParentDirectoryByLevels(parent.FullName, levels - 1)
    End Function


End Class