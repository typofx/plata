Imports System.IO
Imports System.Data.SQLite
Imports System.Text

Public Class FormAproveToken
    Inherits Form

    ' Dados da carteira
    Private ReadOnly _walletAddress As String
    Private ReadOnly _walletSecret As String
    Private ReadOnly _workingDir As String

    ' Editor unificado e aprovador de tokens
    Private _unifiedEditor As UnifiedConfigEditor
    Private _tokenApprover As AproveTokenIndividual

    ' Construtor
    Public Sub New(walletAddress As String, walletSecret As String)
        InitializeComponent()
        _walletAddress = walletAddress
        _walletSecret = walletSecret
        _workingDir = BuscarConfiguracao("WorkingDirectory")
        InitializeTerminal()
        _tokenApprover = New AproveTokenIndividual(txtApproveTokenStatus)


    End Sub


    Private Sub InitializeTerminal()
        ' Configura o TextBox para parecer um terminal CMD
        txtApproveTokenStatus.BackColor = Color.Black
        txtApproveTokenStatus.ForeColor = Color.Lime
        txtApproveTokenStatus.Font = New Font("Consolas", 10) ' Fonte monoespaçada como CMD
        txtApproveTokenStatus.ReadOnly = True
        txtApproveTokenStatus.ScrollBars = ScrollBars.Vertical
        txtApproveTokenStatus.WordWrap = False
    End Sub
    ' Evento Load do formulário
    Private Sub FormAproveToken_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        InitializeUnifiedEditor()
        btnLoadTokens.PerformClick()
        btnLoadBuyPools.PerformClick()
    End Sub

    Private Sub InitializeUnifiedEditor()
        _unifiedEditor = New UnifiedConfigEditor(_workingDir)

        ' Configuração dos controles (associados aos controles do Designer)
        With _unifiedEditor
            ' Configurações do Token0
            .Token0ComboBox = cmbTokens
            .Token0LoadButton = btnLoadTokens
            .Token0SymbolTextBox = txtTokenSymbol
            .Token0DecimalsTextBox = txtTokenDecimals
            .Token0AddressTextBox = txtTokenAddress
            .Token0ResetButton = btnResetTokenConfig

            ' Configurações de compra
            .PoolAddressTextBox = txtBuyPoolAddress
            .SwapRouterTextBox = txtBuySwapRouter
            .SaveButton = btnSaveBuyAddresses
            .ResetButton = btnResetBuyAddresses
            .PoolAddressComboBox = cmbBuyPoolAddresses
            .LoadPoolsButton = btnLoadBuyPools
        End With

        _unifiedEditor.Initialize()
    End Sub

    ' Método para aprovar o token selecionado
    Public Function ApproveSelectedToken(selectedTokenIndex As Integer) As String
        If cmbTokens.Items.Count = 0 OrElse selectedTokenIndex < 0 Then
            Return "Por favor, selecione um token válido da lista."
        End If

        Dim selectedToken = DirectCast(cmbTokens.Items(selectedTokenIndex), UnifiedConfigEditor.Asset)

        Dim tokenSelecionado = DirectCast(cmbTokens.Items(cmbTokens.SelectedIndex), UnifiedConfigEditor.Asset)
        Dim poolAddress = txtBuyPoolAddress.Text
        Dim swapRouter = txtBuySwapRouter.Text

        Try
            ' Chama o método de aprovação da classe AproveTokenIndividual
            Return _tokenApprover.AtualizarVariaveisEJSRun(
    selectedToken.name,          ' tokenName
    selectedToken.ticker,         ' tokenSymbol
    selectedToken.decimals,       ' tokenDecimals
    selectedToken.contract,       ' tokenAddress
    _walletAddress,               ' walletAddress (pegue do campo privado da classe)
    _walletSecret,                ' walletSecret (pegue do campo privado da classe)
    poolAddress,                  ' poolAddress
    swapRouter)                   ' swapRouterAddress

        Catch ex As Exception
            Return $"Erro ao aprovar token: {ex.Message}"
        End Try
    End Function

    ' Evento do botão Aprovar
    Private Sub btnApproveToken_Click(sender As Object, e As EventArgs) Handles btnApproveToken.Click

        WriteToTerminal(">>> Iniciando processo de aprovação...")
        If cmbTokens.SelectedIndex < 0 Then
            MessageBox.Show("Selecione um token primeiro", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        ' Obtém todos os valores necessários
        Dim selectedToken = DirectCast(cmbTokens.Items(cmbTokens.SelectedIndex), UnifiedConfigEditor.Asset)
        Dim tokenName = selectedToken.name
        Dim tokenSymbol = txtTokenSymbol.Text
        Dim tokenDecimals As Integer
        Dim poolAddress = txtBuyPoolAddress.Text
        Dim swapRouter = txtBuySwapRouter.Text

        If Not Integer.TryParse(txtTokenDecimals.Text, tokenDecimals) Then
            MessageBox.Show("Valor inválido para casas decimais", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return
        End If

        ' Verifica se os campos de pool e router estão preenchidos
        If String.IsNullOrWhiteSpace(poolAddress) OrElse String.IsNullOrWhiteSpace(swapRouter) Then
            MessageBox.Show("Preencha os endereços do Pool e do Router", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        Try
            ' ENVIA TODOS OS DADOS PARA AproveTokenIndividual
            Dim resultado = _tokenApprover.AtualizarVariaveisEJSRun(
                tokenName,
                tokenSymbol,
                tokenDecimals,
                selectedToken.contract,
                _walletAddress,
                _walletSecret,
                poolAddress,       ' NOVO PARÂMETRO
                swapRouter)        ' NOVO PARÂMETRO

            'MessageBox.Show(resultado, "Resultado da Aprovação", MessageBoxButtons.OK, MessageBoxIcon.Information)

        Catch ex As Exception
            MessageBox.Show($"Erro ao aprovar token: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' Método para buscar configurações no banco de dados
    Private Shared Function BuscarConfiguracao(chaveBusca As String) As String
        Try
            Dim connectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"
            Using connection As New SQLiteConnection(connectionString)
                connection.Open()
                Dim query As String = "SELECT Valor FROM Configuracoes WHERE Chave = @ChaveBusca"
                Using command As New SQLiteCommand(query, connection)
                    command.Parameters.AddWithValue("@ChaveBusca", chaveBusca)
                    Dim resultado As Object = command.ExecuteScalar()
                    Return If(resultado IsNot Nothing, resultado.ToString(), String.Empty)
                End Using
            End Using
        Catch ex As Exception
            Return String.Empty
        End Try
    End Function

    Public Sub WriteToTerminal(text As String)
        ' Verifica se precisa invocar na thread da UI
        If txtApproveTokenStatus.InvokeRequired Then
            txtApproveTokenStatus.Invoke(Sub() WriteToTerminal(text))
            Return
        End If

        ' Adiciona o texto e move o cursor para o final
        txtApproveTokenStatus.AppendText(text & Environment.NewLine)
        txtApproveTokenStatus.SelectionStart = txtApproveTokenStatus.TextLength
        txtApproveTokenStatus.ScrollToCaret()
    End Sub

    Private Sub btnSaveLog_Click(sender As Object, e As EventArgs) Handles btnSaveLog.Click
        Try
            ' Configura o diálogo para salvar arquivo
            Using saveDialog As New SaveFileDialog()
                saveDialog.Filter = "Arquivos de Texto (*.txt)|*.txt|Todos os Arquivos (*.*)|*.*"
                saveDialog.Title = "Salvar Log de Aprovação"
                saveDialog.FileName = $"TokenApprovalLog_{DateTime.Now:yyyyMMdd_HHmmss}.txt"
                saveDialog.DefaultExt = "txt"
                saveDialog.AddExtension = True

                ' Sugere a pasta de documentos como local inicial
                saveDialog.InitialDirectory = Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments)

                ' Mostra o diálogo e verifica se o usuário confirmou
                If saveDialog.ShowDialog() = DialogResult.OK Then
                    ' Adiciona um cabeçalho informativo ao log
                    Dim logContent As New StringBuilder()
                    logContent.AppendLine($"Log de Aprovação de Token - {DateTime.Now.ToString("dd/MM/yyyy HH:mm:ss")}")
                    logContent.AppendLine($"Endereço da Carteira: {_walletAddress}")
                    logContent.AppendLine(New String("="c, 50)) ' Linha de 50 caracteres "="
                    logContent.AppendLine()

                    ' Adiciona o conteúdo atual do terminal
                    logContent.Append(txtApproveTokenStatus.Text)

                    ' Salva o arquivo
                    File.WriteAllText(saveDialog.FileName, logContent.ToString())

                    ' Atualiza o terminal com o status
                    WriteToTerminal($"Log salvo com sucesso em: {saveDialog.FileName}")
                End If
            End Using
        Catch ex As Exception
            ' Mostra o erro no terminal e em uma mensagem
            Dim errorMsg = $"Erro ao salvar log: {ex.Message}"
            WriteToTerminal(errorMsg)
            MessageBox.Show(errorMsg, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' Limpeza ao fechar o formulário
    Protected Overrides Sub OnFormClosing(e As FormClosingEventArgs)
        If _unifiedEditor IsNot Nothing Then
            _unifiedEditor.Cleanup()
        End If
        MyBase.OnFormClosing(e)
    End Sub
End Class