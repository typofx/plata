<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class FormConfiguracao
    Inherits System.Windows.Forms.Form

    'Descartar substituições de formulário para limpar a lista de componentes.
    <System.Diagnostics.DebuggerNonUserCode()>
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Exigido pelo Windows Form Designer
    Private components As System.ComponentModel.IContainer

    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Label1 = New Label()
        dgvCaminhos = New DataGridView()
        colId = New DataGridViewTextBoxColumn()
        colChave = New DataGridViewTextBoxColumn()
        colValor = New DataGridViewTextBoxColumn()
        colEditar = New DataGridViewButtonColumn()
        colExcluir = New DataGridViewButtonColumn()
        txtChave = New TextBox()
        txtValor = New TextBox()
        btnAdicionar = New Button()
        GroupBox1 = New GroupBox()
        Label2 = New Label()
        Label3 = New Label()
        GroupBox2 = New GroupBox()
        chkVolatility = New CheckBox()
        CHKmaintainRatio = New CheckBox()
        Label4 = New Label()
        Label5 = New Label()
        btnResetBuyConfig = New Button()
        btnSaveBuyConfig = New Button()
        cmbBuyVolatility = New ComboBox()
        txtBuyAmount = New TextBox()
        GBXsettings = New GroupBox()
        Label6 = New Label()
        Label7 = New Label()
        btnResetSellConfig = New Button()
        btnSaveSellConfig = New Button()
        cmbSellVolatility = New ComboBox()
        txtSellBaseAmount = New TextBox()
        Label8 = New Label()
        GroupBox5 = New GroupBox()
        cmbNetwork = New ComboBox()
        btnLoadBuyPools = New Button()
        cmbBuyPoolAddresses = New ComboBox()
        Label11 = New Label()
        txtBuySwapRouter = New TextBox()
        Label12 = New Label()
        btnResetBuyAddresses = New Button()
        txtBuyPoolAddress = New TextBox()
        btnSaveBuyAddresses = New Button()
        GroupBox6 = New GroupBox()
        cmbTokens = New ComboBox()
        Label21 = New Label()
        btnLoadTokens = New Button()
        Label16 = New Label()
        Label15 = New Label()
        txtTokenAddress = New TextBox()
        txtTokenDecimals = New TextBox()
        Label13 = New Label()
        txtTokenSymbol = New TextBox()
        btnResetTokenConfig = New Button()
        btnSaveTokenConfig = New Button()
        GroupBox7 = New GroupBox()
        cmbToken1List = New ComboBox()
        btnLoadToken1List = New Button()
        Label17 = New Label()
        Label18 = New Label()
        txtToken1Address = New TextBox()
        txtToken1Decimals = New TextBox()
        Label19 = New Label()
        txtToken1Symbol = New TextBox()
        Label20 = New Label()
        btnResetToken1Config = New Button()
        btnSaveToken1Config = New Button()
        GroupBox8 = New GroupBox()
        Label22 = New Label()
        txtTempo3 = New TextBox()
        Label24 = New Label()
        btnResetDelays = New Button()
        txtTempo1 = New TextBox()
        btnSaveDelays = New Button()
        cmbPaisMaquina = New ComboBox()
        btnSalvarMaquina = New Button()
        txtNomeMaquina = New TextBox()
        txtNumeroSerie = New TextBox()
        txtLocalizacao = New TextBox()
        txtCidade = New TextBox()
        txtResponsavel = New TextBox()
        txtObservacoes = New TextBox()
        GroupBox9 = New GroupBox()
        Label31 = New Label()
        Label30 = New Label()
        Label29 = New Label()
        Label28 = New Label()
        Label27 = New Label()
        Label26 = New Label()
        Label25 = New Label()
        Button2 = New Button()
        Label9 = New Label()
        nudScriptNumber = New NumericUpDown()
        btnSalvarIni = New Button()
        GroupBox4 = New GroupBox()
        Label14 = New Label()
        nudBotEnable = New NumericUpDown()
        cmbBeOuTee = New ComboBox()
        Label10 = New Label()
        Button1 = New Button()
        Button3 = New Button()
        btnConvertBinToIni = New Button()
        CType(dgvCaminhos, ComponentModel.ISupportInitialize).BeginInit()
        GroupBox1.SuspendLayout()
        GroupBox2.SuspendLayout()
        GBXsettings.SuspendLayout()
        GroupBox5.SuspendLayout()
        GroupBox6.SuspendLayout()
        GroupBox7.SuspendLayout()
        GroupBox8.SuspendLayout()
        GroupBox9.SuspendLayout()
        CType(nudScriptNumber, ComponentModel.ISupportInitialize).BeginInit()
        GroupBox4.SuspendLayout()
        CType(nudBotEnable, ComponentModel.ISupportInitialize).BeginInit()
        SuspendLayout()
        ' 
        ' Label1
        ' 
        Label1.AutoSize = True
        Label1.Font = New Font("Segoe UI", 9.75F, FontStyle.Bold)
        Label1.Location = New Point(12, 9)
        Label1.Name = "Label1"
        Label1.Size = New Size(109, 17)
        Label1.TabIndex = 0
        Label1.Text = "General Settings"
        ' 
        ' dgvCaminhos
        ' 
        dgvCaminhos.AllowUserToAddRows = False
        dgvCaminhos.AllowUserToDeleteRows = False
        dgvCaminhos.Anchor = AnchorStyles.Top Or AnchorStyles.Left Or AnchorStyles.Right
        dgvCaminhos.BackgroundColor = SystemColors.Window
        dgvCaminhos.ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize
        dgvCaminhos.Columns.AddRange(New DataGridViewColumn() {colId, colChave, colValor, colEditar, colExcluir})
        dgvCaminhos.Location = New Point(12, 29)
        dgvCaminhos.Name = "dgvCaminhos"
        dgvCaminhos.ReadOnly = True
        dgvCaminhos.RowHeadersVisible = False
        dgvCaminhos.SelectionMode = DataGridViewSelectionMode.FullRowSelect
        dgvCaminhos.Size = New Size(796, 200)
        dgvCaminhos.TabIndex = 1
        ' 
        ' colId
        ' 
        colId.DataPropertyName = "Id"
        colId.HeaderText = "Id"
        colId.Name = "colId"
        colId.ReadOnly = True
        colId.Visible = False
        ' 
        ' colChave
        ' 
        colChave.DataPropertyName = "Chave"
        colChave.HeaderText = "Key"
        colChave.Name = "colChave"
        colChave.ReadOnly = True
        colChave.Width = 150
        ' 
        ' colValor
        ' 
        colValor.DataPropertyName = "Valor"
        colValor.HeaderText = "Directory path"
        colValor.Name = "colValor"
        colValor.ReadOnly = True
        colValor.Width = 545
        ' 
        ' colEditar
        ' 
        colEditar.HeaderText = ""
        colEditar.Name = "colEditar"
        colEditar.ReadOnly = True
        colEditar.Text = "✏️"
        colEditar.UseColumnTextForButtonValue = True
        colEditar.Width = 40
        ' 
        ' colExcluir
        ' 
        colExcluir.HeaderText = ""
        colExcluir.Name = "colExcluir"
        colExcluir.ReadOnly = True
        colExcluir.Text = "🗑️"
        colExcluir.UseColumnTextForButtonValue = True
        colExcluir.Width = 40
        ' 
        ' txtChave
        ' 
        txtChave.Location = New Point(6, 37)
        txtChave.Name = "txtChave"
        txtChave.PlaceholderText = "Enter the key"
        txtChave.Size = New Size(150, 23)
        txtChave.TabIndex = 2
        ' 
        ' txtValor
        ' 
        txtValor.Location = New Point(162, 37)
        txtValor.Name = "txtValor"
        txtValor.PlaceholderText = "Enter the directory"
        txtValor.Size = New Size(250, 23)
        txtValor.TabIndex = 3
        ' 
        ' btnAdicionar
        ' 
        btnAdicionar.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        btnAdicionar.FlatStyle = FlatStyle.Flat
        btnAdicionar.ForeColor = Color.White
        btnAdicionar.Location = New Point(418, 37)
        btnAdicionar.Name = "btnAdicionar"
        btnAdicionar.Size = New Size(90, 23)
        btnAdicionar.TabIndex = 4
        btnAdicionar.Text = "Add"
        btnAdicionar.UseVisualStyleBackColor = False
        ' 
        ' GroupBox1
        ' 
        GroupBox1.Anchor = AnchorStyles.Top Or AnchorStyles.Left Or AnchorStyles.Right
        GroupBox1.Controls.Add(Label2)
        GroupBox1.Controls.Add(Label3)
        GroupBox1.Controls.Add(txtChave)
        GroupBox1.Controls.Add(btnAdicionar)
        GroupBox1.Controls.Add(txtValor)
        GroupBox1.Location = New Point(12, 235)
        GroupBox1.Name = "GroupBox1"
        GroupBox1.Size = New Size(519, 80)
        GroupBox1.TabIndex = 13
        GroupBox1.TabStop = False
        GroupBox1.Text = "Add New Key and Directory Path"
        ' 
        ' Label2
        ' 
        Label2.AutoSize = True
        Label2.Location = New Point(6, 19)
        Label2.Name = "Label2"
        Label2.Size = New Size(26, 15)
        Label2.TabIndex = 5
        Label2.Text = "Key"
        ' 
        ' Label3
        ' 
        Label3.AutoSize = True
        Label3.Location = New Point(162, 19)
        Label3.Name = "Label3"
        Label3.Size = New Size(82, 15)
        Label3.TabIndex = 6
        Label3.Text = "Directory Path"
        ' 
        ' GroupBox2
        ' 
        GroupBox2.Controls.Add(chkVolatility)
        GroupBox2.Controls.Add(CHKmaintainRatio)
        GroupBox2.Controls.Add(Label4)
        GroupBox2.Controls.Add(Label5)
        GroupBox2.Controls.Add(btnResetBuyConfig)
        GroupBox2.Controls.Add(btnSaveBuyConfig)
        GroupBox2.Controls.Add(cmbBuyVolatility)
        GroupBox2.Controls.Add(txtBuyAmount)
        GroupBox2.Location = New Point(12, 321)
        GroupBox2.Name = "GroupBox2"
        GroupBox2.Size = New Size(376, 150)
        GroupBox2.TabIndex = 14
        GroupBox2.TabStop = False
        GroupBox2.Text = "Buy Settings"
        ' 
        ' chkVolatility
        ' 
        chkVolatility.AutoSize = True
        chkVolatility.Location = New Point(189, 65)
        chkVolatility.Name = "chkVolatility"
        chkVolatility.Size = New Size(74, 19)
        chkVolatility.TabIndex = 18
        chkVolatility.Text = "Volatility "
        chkVolatility.UseVisualStyleBackColor = True
        ' 
        ' CHKmaintainRatio
        ' 
        CHKmaintainRatio.AutoSize = True
        CHKmaintainRatio.Location = New Point(189, 40)
        CHKmaintainRatio.Name = "CHKmaintainRatio"
        CHKmaintainRatio.Size = New Size(137, 19)
        CHKmaintainRatio.TabIndex = 17
        CHKmaintainRatio.Text = "Maintain aspect ratio"
        CHKmaintainRatio.UseVisualStyleBackColor = True
        ' 
        ' Label4
        ' 
        Label4.AutoSize = True
        Label4.Location = New Point(6, 22)
        Label4.Name = "Label4"
        Label4.Size = New Size(51, 15)
        Label4.TabIndex = 15
        Label4.Text = "Amount"
        ' 
        ' Label5
        ' 
        Label5.AutoSize = True
        Label5.Location = New Point(6, 70)
        Label5.Name = "Label5"
        Label5.Size = New Size(52, 15)
        Label5.TabIndex = 16
        Label5.Text = "Volatility"
        ' 
        ' btnResetBuyConfig
        ' 
        btnResetBuyConfig.Location = New Point(6, 115)
        btnResetBuyConfig.Name = "btnResetBuyConfig"
        btnResetBuyConfig.Size = New Size(75, 23)
        btnResetBuyConfig.TabIndex = 8
        btnResetBuyConfig.Text = "Reset"
        btnResetBuyConfig.UseVisualStyleBackColor = True
        ' 
        ' btnSaveBuyConfig
        ' 
        btnSaveBuyConfig.BackColor = Color.Transparent
        btnSaveBuyConfig.FlatAppearance.BorderSize = 0
        btnSaveBuyConfig.FlatStyle = FlatStyle.Flat
        btnSaveBuyConfig.ForeColor = Color.Transparent
        btnSaveBuyConfig.Location = New Point(-1000, -1000)
        btnSaveBuyConfig.Name = "btnSaveBuyConfig"
        btnSaveBuyConfig.Size = New Size(1, 1)
        btnSaveBuyConfig.TabIndex = 0
        btnSaveBuyConfig.TabStop = False
        btnSaveBuyConfig.UseVisualStyleBackColor = False
        ' 
        ' cmbBuyVolatility
        ' 
        cmbBuyVolatility.DropDownStyle = ComboBoxStyle.DropDownList
        cmbBuyVolatility.FormattingEnabled = True
        cmbBuyVolatility.Location = New Point(6, 88)
        cmbBuyVolatility.Name = "cmbBuyVolatility"
        cmbBuyVolatility.Size = New Size(156, 23)
        cmbBuyVolatility.TabIndex = 6
        ' 
        ' txtBuyAmount
        ' 
        txtBuyAmount.Location = New Point(6, 40)
        txtBuyAmount.Name = "txtBuyAmount"
        txtBuyAmount.Size = New Size(156, 23)
        txtBuyAmount.TabIndex = 5
        ' 
        ' GBXsettings
        ' 
        GBXsettings.Controls.Add(Label6)
        GBXsettings.Controls.Add(Label7)
        GBXsettings.Controls.Add(btnResetSellConfig)
        GBXsettings.Controls.Add(btnSaveSellConfig)
        GBXsettings.Controls.Add(cmbSellVolatility)
        GBXsettings.Controls.Add(txtSellBaseAmount)
        GBXsettings.Location = New Point(412, 321)
        GBXsettings.Name = "GBXsettings"
        GBXsettings.Size = New Size(376, 150)
        GBXsettings.TabIndex = 15
        GBXsettings.TabStop = False
        GBXsettings.Text = "Sell Settings"
        ' 
        ' Label6
        ' 
        Label6.AutoSize = True
        Label6.Location = New Point(6, 22)
        Label6.Name = "Label6"
        Label6.Size = New Size(51, 15)
        Label6.TabIndex = 15
        Label6.Text = "Amount"
        ' 
        ' Label7
        ' 
        Label7.AutoSize = True
        Label7.Location = New Point(6, 70)
        Label7.Name = "Label7"
        Label7.Size = New Size(52, 15)
        Label7.TabIndex = 16
        Label7.Text = "Volatility"
        ' 
        ' btnResetSellConfig
        ' 
        btnResetSellConfig.Location = New Point(6, 115)
        btnResetSellConfig.Name = "btnResetSellConfig"
        btnResetSellConfig.Size = New Size(75, 23)
        btnResetSellConfig.TabIndex = 12
        btnResetSellConfig.Text = "Reset"
        btnResetSellConfig.UseVisualStyleBackColor = True
        ' 
        ' btnSaveSellConfig
        ' 
        btnSaveSellConfig.BackColor = Color.Transparent
        btnSaveSellConfig.FlatAppearance.BorderSize = 0
        btnSaveSellConfig.FlatStyle = FlatStyle.Flat
        btnSaveSellConfig.ForeColor = Color.Transparent
        btnSaveSellConfig.Location = New Point(-1000, -1000)
        btnSaveSellConfig.Name = "btnSaveSellConfig"
        btnSaveSellConfig.Size = New Size(1, 1)
        btnSaveSellConfig.TabIndex = 0
        btnSaveSellConfig.TabStop = False
        btnSaveSellConfig.UseVisualStyleBackColor = False
        ' 
        ' cmbSellVolatility
        ' 
        cmbSellVolatility.DropDownStyle = ComboBoxStyle.DropDownList
        cmbSellVolatility.FormattingEnabled = True
        cmbSellVolatility.Location = New Point(6, 88)
        cmbSellVolatility.Name = "cmbSellVolatility"
        cmbSellVolatility.Size = New Size(156, 23)
        cmbSellVolatility.TabIndex = 10
        ' 
        ' txtSellBaseAmount
        ' 
        txtSellBaseAmount.Location = New Point(6, 40)
        txtSellBaseAmount.Name = "txtSellBaseAmount"
        txtSellBaseAmount.Size = New Size(156, 23)
        txtSellBaseAmount.TabIndex = 9
        ' 
        ' Label8
        ' 
        Label8.Anchor = AnchorStyles.Bottom Or AnchorStyles.Left
        Label8.AutoSize = True
        Label8.Font = New Font("Segoe UI", 8.25F, FontStyle.Italic)
        Label8.ForeColor = Color.Gray
        Label8.Location = New Point(12, 32767)
        Label8.Name = "Label8"
        Label8.Size = New Size(79, 13)
        Label8.TabIndex = 16
        Label8.Text = "System Settings"
        ' 
        ' GroupBox5
        ' 
        GroupBox5.Controls.Add(cmbNetwork)
        GroupBox5.Controls.Add(btnLoadBuyPools)
        GroupBox5.Controls.Add(cmbBuyPoolAddresses)
        GroupBox5.Controls.Add(Label11)
        GroupBox5.Controls.Add(txtBuySwapRouter)
        GroupBox5.Controls.Add(Label12)
        GroupBox5.Controls.Add(btnResetBuyAddresses)
        GroupBox5.Controls.Add(txtBuyPoolAddress)
        GroupBox5.Controls.Add(btnSaveBuyAddresses)
        GroupBox5.Location = New Point(12, 477)
        GroupBox5.Name = "GroupBox5"
        GroupBox5.Size = New Size(376, 237)
        GroupBox5.TabIndex = 19
        GroupBox5.TabStop = False
        GroupBox5.Text = "Allowance addresses"
        ' 
        ' cmbNetwork
        ' 
        cmbNetwork.DropDownStyle = ComboBoxStyle.DropDownList
        cmbNetwork.FormattingEnabled = True
        cmbNetwork.Location = New Point(9, 45)
        cmbNetwork.Name = "cmbNetwork"
        cmbNetwork.Size = New Size(227, 23)
        cmbNetwork.TabIndex = 31
        ' 
        ' btnLoadBuyPools
        ' 
        btnLoadBuyPools.Location = New Point(289, 80)
        btnLoadBuyPools.Name = "btnLoadBuyPools"
        btnLoadBuyPools.Size = New Size(80, 23)
        btnLoadBuyPools.TabIndex = 30
        btnLoadBuyPools.Text = "Load pools"
        btnLoadBuyPools.UseVisualStyleBackColor = True
        ' 
        ' cmbBuyPoolAddresses
        ' 
        cmbBuyPoolAddresses.DropDownStyle = ComboBoxStyle.DropDownList
        cmbBuyPoolAddresses.FormattingEnabled = True
        cmbBuyPoolAddresses.Location = New Point(8, 80)
        cmbBuyPoolAddresses.Name = "cmbBuyPoolAddresses"
        cmbBuyPoolAddresses.Size = New Size(277, 23)
        cmbBuyPoolAddresses.TabIndex = 30
        ' 
        ' Label11
        ' 
        Label11.AutoSize = True
        Label11.Location = New Point(6, 145)
        Label11.Name = "Label11"
        Label11.Size = New Size(118, 15)
        Label11.TabIndex = 18
        Label11.Text = "Swap Router Address"
        ' 
        ' txtBuySwapRouter
        ' 
        txtBuySwapRouter.Location = New Point(6, 162)
        txtBuySwapRouter.Name = "txtBuySwapRouter"
        txtBuySwapRouter.Size = New Size(364, 23)
        txtBuySwapRouter.TabIndex = 17
        ' 
        ' Label12
        ' 
        Label12.AutoSize = True
        Label12.Location = New Point(6, 105)
        Label12.Name = "Label12"
        Label12.Size = New Size(76, 15)
        Label12.TabIndex = 15
        Label12.Text = "Pool Address"
        ' 
        ' btnResetBuyAddresses
        ' 
        btnResetBuyAddresses.Location = New Point(5, 189)
        btnResetBuyAddresses.Name = "btnResetBuyAddresses"
        btnResetBuyAddresses.Size = New Size(75, 23)
        btnResetBuyAddresses.TabIndex = 8
        btnResetBuyAddresses.Text = "Reset"
        btnResetBuyAddresses.UseVisualStyleBackColor = True
        ' 
        ' txtBuyPoolAddress
        ' 
        txtBuyPoolAddress.Location = New Point(6, 120)
        txtBuyPoolAddress.Name = "txtBuyPoolAddress"
        txtBuyPoolAddress.ReadOnly = True
        txtBuyPoolAddress.Size = New Size(364, 23)
        txtBuyPoolAddress.TabIndex = 5
        ' 
        ' btnSaveBuyAddresses
        ' 
        btnSaveBuyAddresses.BackColor = Color.Transparent
        btnSaveBuyAddresses.FlatAppearance.BorderSize = 0
        btnSaveBuyAddresses.FlatStyle = FlatStyle.Flat
        btnSaveBuyAddresses.ForeColor = Color.Transparent
        btnSaveBuyAddresses.Location = New Point(-1000, -1000)
        btnSaveBuyAddresses.Name = "btnSaveBuyAddresses"
        btnSaveBuyAddresses.Size = New Size(1, 1)
        btnSaveBuyAddresses.TabIndex = 0
        btnSaveBuyAddresses.TabStop = False
        btnSaveBuyAddresses.UseVisualStyleBackColor = False
        ' 
        ' GroupBox6
        ' 
        GroupBox6.Controls.Add(cmbTokens)
        GroupBox6.Controls.Add(Label21)
        GroupBox6.Controls.Add(btnLoadTokens)
        GroupBox6.Controls.Add(Label16)
        GroupBox6.Controls.Add(Label15)
        GroupBox6.Controls.Add(txtTokenAddress)
        GroupBox6.Controls.Add(txtTokenDecimals)
        GroupBox6.Controls.Add(Label13)
        GroupBox6.Controls.Add(txtTokenSymbol)
        GroupBox6.Controls.Add(btnResetTokenConfig)
        GroupBox6.Controls.Add(btnSaveTokenConfig)
        GroupBox6.Location = New Point(12, 718)
        GroupBox6.Name = "GroupBox6"
        GroupBox6.Size = New Size(376, 285)
        GroupBox6.TabIndex = 20
        GroupBox6.TabStop = False
        GroupBox6.Text = "Token 0"
        ' 
        ' cmbTokens
        ' 
        cmbTokens.DropDownStyle = ComboBoxStyle.DropDownList
        cmbTokens.FormattingEnabled = True
        cmbTokens.Location = New Point(6, 41)
        cmbTokens.Name = "cmbTokens"
        cmbTokens.Size = New Size(148, 23)
        cmbTokens.TabIndex = 29
        ' 
        ' Label21
        ' 
        Label21.AutoSize = True
        Label21.Location = New Point(8, 23)
        Label21.Name = "Label21"
        Label21.Size = New Size(73, 15)
        Label21.TabIndex = 27
        Label21.Text = "Token Name"
        ' 
        ' btnLoadTokens
        ' 
        btnLoadTokens.Location = New Point(245, 213)
        btnLoadTokens.Name = "btnLoadTokens"
        btnLoadTokens.Size = New Size(124, 23)
        btnLoadTokens.TabIndex = 24
        btnLoadTokens.Text = "Load Tokens"
        btnLoadTokens.UseVisualStyleBackColor = True
        ' 
        ' Label16
        ' 
        Label16.AutoSize = True
        Label16.Location = New Point(6, 153)
        Label16.Name = "Label16"
        Label16.Size = New Size(83, 15)
        Label16.TabIndex = 23
        Label16.Text = "Token Address"
        ' 
        ' Label15
        ' 
        Label15.AutoSize = True
        Label15.Location = New Point(6, 111)
        Label15.Name = "Label15"
        Label15.Size = New Size(89, 15)
        Label15.TabIndex = 22
        Label15.Text = "Token Decimals"
        ' 
        ' txtTokenAddress
        ' 
        txtTokenAddress.Location = New Point(5, 171)
        txtTokenAddress.Name = "txtTokenAddress"
        txtTokenAddress.ReadOnly = True
        txtTokenAddress.Size = New Size(364, 23)
        txtTokenAddress.TabIndex = 19
        ' 
        ' txtTokenDecimals
        ' 
        txtTokenDecimals.Location = New Point(5, 127)
        txtTokenDecimals.Name = "txtTokenDecimals"
        txtTokenDecimals.ReadOnly = True
        txtTokenDecimals.Size = New Size(364, 23)
        txtTokenDecimals.TabIndex = 21
        ' 
        ' Label13
        ' 
        Label13.AutoSize = True
        Label13.Location = New Point(6, 67)
        Label13.Name = "Label13"
        Label13.Size = New Size(81, 15)
        Label13.TabIndex = 18
        Label13.Text = "Token Symbol"
        ' 
        ' txtTokenSymbol
        ' 
        txtTokenSymbol.Location = New Point(5, 85)
        txtTokenSymbol.Name = "txtTokenSymbol"
        txtTokenSymbol.ReadOnly = True
        txtTokenSymbol.Size = New Size(364, 23)
        txtTokenSymbol.TabIndex = 17
        ' 
        ' btnResetTokenConfig
        ' 
        btnResetTokenConfig.Location = New Point(5, 213)
        btnResetTokenConfig.Name = "btnResetTokenConfig"
        btnResetTokenConfig.Size = New Size(75, 23)
        btnResetTokenConfig.TabIndex = 8
        btnResetTokenConfig.Text = "Reset"
        btnResetTokenConfig.UseVisualStyleBackColor = True
        ' 
        ' btnSaveTokenConfig
        ' 
        btnSaveTokenConfig.BackColor = Color.Transparent
        btnSaveTokenConfig.FlatAppearance.BorderSize = 0
        btnSaveTokenConfig.FlatStyle = FlatStyle.Flat
        btnSaveTokenConfig.ForeColor = Color.Transparent
        btnSaveTokenConfig.Location = New Point(-1000, -1000)
        btnSaveTokenConfig.Name = "btnSaveTokenConfig"
        btnSaveTokenConfig.Size = New Size(1, 1)
        btnSaveTokenConfig.TabIndex = 0
        btnSaveTokenConfig.TabStop = False
        btnSaveTokenConfig.UseVisualStyleBackColor = False
        ' 
        ' GroupBox7
        ' 
        GroupBox7.Controls.Add(cmbToken1List)
        GroupBox7.Controls.Add(btnLoadToken1List)
        GroupBox7.Controls.Add(Label17)
        GroupBox7.Controls.Add(Label18)
        GroupBox7.Controls.Add(txtToken1Address)
        GroupBox7.Controls.Add(txtToken1Decimals)
        GroupBox7.Controls.Add(Label19)
        GroupBox7.Controls.Add(txtToken1Symbol)
        GroupBox7.Controls.Add(Label20)
        GroupBox7.Controls.Add(btnResetToken1Config)
        GroupBox7.Controls.Add(btnSaveToken1Config)
        GroupBox7.Location = New Point(412, 718)
        GroupBox7.Name = "GroupBox7"
        GroupBox7.Size = New Size(376, 285)
        GroupBox7.TabIndex = 24
        GroupBox7.TabStop = False
        GroupBox7.Text = "Token 1"
        ' 
        ' cmbToken1List
        ' 
        cmbToken1List.DropDownStyle = ComboBoxStyle.DropDownList
        cmbToken1List.FormattingEnabled = True
        cmbToken1List.Location = New Point(6, 41)
        cmbToken1List.Name = "cmbToken1List"
        cmbToken1List.Size = New Size(148, 23)
        cmbToken1List.TabIndex = 28
        ' 
        ' btnLoadToken1List
        ' 
        btnLoadToken1List.Location = New Point(246, 213)
        btnLoadToken1List.Name = "btnLoadToken1List"
        btnLoadToken1List.Size = New Size(124, 23)
        btnLoadToken1List.TabIndex = 28
        btnLoadToken1List.Text = "Load Tokens"
        btnLoadToken1List.UseVisualStyleBackColor = True
        ' 
        ' Label17
        ' 
        Label17.AutoSize = True
        Label17.Location = New Point(4, 153)
        Label17.Name = "Label17"
        Label17.Size = New Size(83, 15)
        Label17.TabIndex = 23
        Label17.Text = "Token Address"
        ' 
        ' Label18
        ' 
        Label18.AutoSize = True
        Label18.Location = New Point(4, 111)
        Label18.Name = "Label18"
        Label18.Size = New Size(89, 15)
        Label18.TabIndex = 22
        Label18.Text = "Token Decimals"
        ' 
        ' txtToken1Address
        ' 
        txtToken1Address.Location = New Point(4, 171)
        txtToken1Address.Name = "txtToken1Address"
        txtToken1Address.ReadOnly = True
        txtToken1Address.Size = New Size(364, 23)
        txtToken1Address.TabIndex = 19
        ' 
        ' txtToken1Decimals
        ' 
        txtToken1Decimals.Location = New Point(4, 127)
        txtToken1Decimals.Name = "txtToken1Decimals"
        txtToken1Decimals.ReadOnly = True
        txtToken1Decimals.Size = New Size(364, 23)
        txtToken1Decimals.TabIndex = 21
        ' 
        ' Label19
        ' 
        Label19.AutoSize = True
        Label19.Location = New Point(4, 67)
        Label19.Name = "Label19"
        Label19.Size = New Size(81, 15)
        Label19.TabIndex = 18
        Label19.Text = "Token Symbol"
        ' 
        ' txtToken1Symbol
        ' 
        txtToken1Symbol.Location = New Point(4, 85)
        txtToken1Symbol.Name = "txtToken1Symbol"
        txtToken1Symbol.ReadOnly = True
        txtToken1Symbol.Size = New Size(364, 23)
        txtToken1Symbol.TabIndex = 17
        ' 
        ' Label20
        ' 
        Label20.AutoSize = True
        Label20.Location = New Point(4, 23)
        Label20.Name = "Label20"
        Label20.Size = New Size(73, 15)
        Label20.TabIndex = 15
        Label20.Text = "Token Name"
        ' 
        ' btnResetToken1Config
        ' 
        btnResetToken1Config.Location = New Point(4, 213)
        btnResetToken1Config.Name = "btnResetToken1Config"
        btnResetToken1Config.Size = New Size(75, 23)
        btnResetToken1Config.TabIndex = 8
        btnResetToken1Config.Text = "Reset"
        btnResetToken1Config.UseVisualStyleBackColor = True
        ' 
        ' btnSaveToken1Config
        ' 
        btnSaveToken1Config.BackColor = Color.Transparent
        btnSaveToken1Config.FlatAppearance.BorderSize = 0
        btnSaveToken1Config.FlatStyle = FlatStyle.Flat
        btnSaveToken1Config.ForeColor = Color.Transparent
        btnSaveToken1Config.Location = New Point(-1000, -1000)
        btnSaveToken1Config.Name = "btnSaveToken1Config"
        btnSaveToken1Config.Size = New Size(1, 1)
        btnSaveToken1Config.TabIndex = 0
        btnSaveToken1Config.TabStop = False
        btnSaveToken1Config.UseVisualStyleBackColor = False
        ' 
        ' GroupBox8
        ' 
        GroupBox8.Controls.Add(Label22)
        GroupBox8.Controls.Add(txtTempo3)
        GroupBox8.Controls.Add(Label24)
        GroupBox8.Controls.Add(btnResetDelays)
        GroupBox8.Controls.Add(txtTempo1)
        GroupBox8.Controls.Add(btnSaveDelays)
        GroupBox8.Location = New Point(12, 1012)
        GroupBox8.Name = "GroupBox8"
        GroupBox8.Size = New Size(376, 192)
        GroupBox8.TabIndex = 25
        GroupBox8.TabStop = False
        GroupBox8.Text = "TimeStamp Platabot Config"
        ' 
        ' Label22
        ' 
        Label22.AutoSize = True
        Label22.Location = New Point(5, 66)
        Label22.Name = "Label22"
        Label22.Size = New Size(119, 15)
        Label22.TabIndex = 22
        Label22.Text = "Delay between cycles"
        ' 
        ' txtTempo3
        ' 
        txtTempo3.Location = New Point(5, 82)
        txtTempo3.Name = "txtTempo3"
        txtTempo3.Size = New Size(364, 23)
        txtTempo3.TabIndex = 21
        ' 
        ' Label24
        ' 
        Label24.AutoSize = True
        Label24.Location = New Point(6, 22)
        Label24.Name = "Label24"
        Label24.Size = New Size(77, 15)
        Label24.TabIndex = 15
        Label24.Text = "Aprove Delay"
        ' 
        ' btnResetDelays
        ' 
        btnResetDelays.Location = New Point(6, 155)
        btnResetDelays.Name = "btnResetDelays"
        btnResetDelays.Size = New Size(75, 23)
        btnResetDelays.TabIndex = 8
        btnResetDelays.Text = "Reset"
        btnResetDelays.UseVisualStyleBackColor = True
        ' 
        ' txtTempo1
        ' 
        txtTempo1.Location = New Point(6, 40)
        txtTempo1.Name = "txtTempo1"
        txtTempo1.Size = New Size(364, 23)
        txtTempo1.TabIndex = 5
        ' 
        ' btnSaveDelays
        ' 
        btnSaveDelays.BackColor = Color.Transparent
        btnSaveDelays.FlatAppearance.BorderSize = 0
        btnSaveDelays.FlatStyle = FlatStyle.Flat
        btnSaveDelays.ForeColor = Color.Transparent
        btnSaveDelays.Location = New Point(-1000, -1000)
        btnSaveDelays.Name = "btnSaveDelays"
        btnSaveDelays.Size = New Size(1, 1)
        btnSaveDelays.TabIndex = 0
        btnSaveDelays.TabStop = False
        btnSaveDelays.UseVisualStyleBackColor = False
        ' 
        ' cmbPaisMaquina
        ' 
        cmbPaisMaquina.DropDownStyle = ComboBoxStyle.DropDownList
        cmbPaisMaquina.FormattingEnabled = True
        cmbPaisMaquina.Location = New Point(18, 38)
        cmbPaisMaquina.Name = "cmbPaisMaquina"
        cmbPaisMaquina.Size = New Size(171, 23)
        cmbPaisMaquina.TabIndex = 26
        ' 
        ' btnSalvarMaquina
        ' 
        btnSalvarMaquina.BackColor = Color.Transparent
        btnSalvarMaquina.FlatAppearance.BorderSize = 0
        btnSalvarMaquina.FlatStyle = FlatStyle.Flat
        btnSalvarMaquina.ForeColor = Color.Transparent
        btnSalvarMaquina.Location = New Point(-1000, -1000)
        btnSalvarMaquina.Name = "btnSalvarMaquina"
        btnSalvarMaquina.Size = New Size(1, 1)
        btnSalvarMaquina.TabIndex = 0
        btnSalvarMaquina.TabStop = False
        btnSalvarMaquina.UseVisualStyleBackColor = False
        ' 
        ' txtNomeMaquina
        ' 
        txtNomeMaquina.Location = New Point(19, 77)
        txtNomeMaquina.Name = "txtNomeMaquina"
        txtNomeMaquina.ReadOnly = True
        txtNomeMaquina.Size = New Size(170, 23)
        txtNomeMaquina.TabIndex = 23
        ' 
        ' txtNumeroSerie
        ' 
        txtNumeroSerie.Location = New Point(19, 117)
        txtNumeroSerie.Name = "txtNumeroSerie"
        txtNumeroSerie.ReadOnly = True
        txtNumeroSerie.Size = New Size(170, 23)
        txtNumeroSerie.TabIndex = 30
        ' 
        ' txtLocalizacao
        ' 
        txtLocalizacao.Location = New Point(19, 157)
        txtLocalizacao.Name = "txtLocalizacao"
        txtLocalizacao.Size = New Size(170, 23)
        txtLocalizacao.TabIndex = 31
        ' 
        ' txtCidade
        ' 
        txtCidade.Location = New Point(205, 37)
        txtCidade.Name = "txtCidade"
        txtCidade.Size = New Size(159, 23)
        txtCidade.TabIndex = 32
        ' 
        ' txtResponsavel
        ' 
        txtResponsavel.Location = New Point(205, 78)
        txtResponsavel.Name = "txtResponsavel"
        txtResponsavel.ReadOnly = True
        txtResponsavel.Size = New Size(159, 23)
        txtResponsavel.TabIndex = 33
        ' 
        ' txtObservacoes
        ' 
        txtObservacoes.Location = New Point(205, 117)
        txtObservacoes.Name = "txtObservacoes"
        txtObservacoes.Size = New Size(159, 23)
        txtObservacoes.TabIndex = 34
        ' 
        ' GroupBox9
        ' 
        GroupBox9.Controls.Add(Label31)
        GroupBox9.Controls.Add(Label30)
        GroupBox9.Controls.Add(Label29)
        GroupBox9.Controls.Add(Label28)
        GroupBox9.Controls.Add(Label27)
        GroupBox9.Controls.Add(Label26)
        GroupBox9.Controls.Add(btnSalvarMaquina)
        GroupBox9.Controls.Add(Label25)
        GroupBox9.Controls.Add(Button2)
        GroupBox9.Controls.Add(txtObservacoes)
        GroupBox9.Controls.Add(txtNomeMaquina)
        GroupBox9.Controls.Add(txtResponsavel)
        GroupBox9.Controls.Add(cmbPaisMaquina)
        GroupBox9.Controls.Add(txtCidade)
        GroupBox9.Controls.Add(txtLocalizacao)
        GroupBox9.Controls.Add(txtNumeroSerie)
        GroupBox9.Location = New Point(406, 1012)
        GroupBox9.Name = "GroupBox9"
        GroupBox9.Size = New Size(376, 192)
        GroupBox9.TabIndex = 26
        GroupBox9.TabStop = False
        GroupBox9.Text = "Machine Config"
        ' 
        ' Label31
        ' 
        Label31.AutoSize = True
        Label31.Location = New Point(205, 101)
        Label31.Name = "Label31"
        Label31.Size = New Size(38, 15)
        Label31.TabIndex = 40
        Label31.Text = "Notes"
        ' 
        ' Label30
        ' 
        Label30.AutoSize = True
        Label30.Location = New Point(205, 61)
        Label30.Name = "Label30"
        Label30.Size = New Size(157, 15)
        Label30.TabIndex = 39
        Label30.Text = "Windows System user (auto)"
        ' 
        ' Label29
        ' 
        Label29.AutoSize = True
        Label29.Location = New Point(205, 22)
        Label29.Name = "Label29"
        Label29.Size = New Size(63, 15)
        Label29.TabIndex = 38
        Label29.Text = "City Name"
        ' 
        ' Label28
        ' 
        Label28.AutoSize = True
        Label28.Location = New Point(21, 140)
        Label28.Name = "Label28"
        Label28.Size = New Size(60, 15)
        Label28.TabIndex = 37
        Label28.Text = "Bot Name"
        ' 
        ' Label27
        ' 
        Label27.AutoSize = True
        Label27.Location = New Point(21, 100)
        Label27.Name = "Label27"
        Label27.Size = New Size(117, 15)
        Label27.TabIndex = 36
        Label27.Text = "Serial Number (auto)"
        ' 
        ' Label26
        ' 
        Label26.AutoSize = True
        Label26.Location = New Point(21, 61)
        Label26.Name = "Label26"
        Label26.Size = New Size(123, 15)
        Label26.TabIndex = 35
        Label26.Text = "Machine Name (auto)"
        ' 
        ' Label25
        ' 
        Label25.AutoSize = True
        Label25.Location = New Point(19, 22)
        Label25.Name = "Label25"
        Label25.Size = New Size(50, 15)
        Label25.TabIndex = 23
        Label25.Text = "Country"
        ' 
        ' Button2
        ' 
        Button2.BackColor = Color.Transparent
        Button2.FlatAppearance.BorderSize = 0
        Button2.FlatStyle = FlatStyle.Flat
        Button2.ForeColor = Color.Transparent
        Button2.Location = New Point(-1000, -1000)
        Button2.Name = "Button2"
        Button2.Size = New Size(1, 1)
        Button2.TabIndex = 0
        Button2.TabStop = False
        Button2.UseVisualStyleBackColor = False
        ' 
        ' Label9
        ' 
        Label9.AutoSize = True
        Label9.Location = New Point(205, 14)
        Label9.Name = "Label9"
        Label9.Size = New Size(89, 15)
        Label9.TabIndex = 42
        Label9.Text = "Scripts Number"
        ' 
        ' nudScriptNumber
        ' 
        nudScriptNumber.Location = New Point(205, 31)
        nudScriptNumber.Name = "nudScriptNumber"
        nudScriptNumber.Size = New Size(157, 23)
        nudScriptNumber.TabIndex = 41
        ' 
        ' btnSalvarIni
        ' 
        btnSalvarIni.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        btnSalvarIni.FlatStyle = FlatStyle.Flat
        btnSalvarIni.ForeColor = Color.White
        btnSalvarIni.Location = New Point(156, 1216)
        btnSalvarIni.Name = "btnSalvarIni"
        btnSalvarIni.Size = New Size(81, 35)
        btnSalvarIni.TabIndex = 27
        btnSalvarIni.Text = "Save Setup"
        btnSalvarIni.UseVisualStyleBackColor = False
        ' 
        ' GroupBox4
        ' 
        GroupBox4.Controls.Add(Label14)
        GroupBox4.Controls.Add(nudBotEnable)
        GroupBox4.Controls.Add(cmbBeOuTee)
        GroupBox4.Controls.Add(Label10)
        GroupBox4.Controls.Add(Label9)
        GroupBox4.Controls.Add(nudScriptNumber)
        GroupBox4.Controls.Add(Button1)
        GroupBox4.Controls.Add(Button3)
        GroupBox4.Location = New Point(406, 1210)
        GroupBox4.Name = "GroupBox4"
        GroupBox4.Size = New Size(376, 101)
        GroupBox4.TabIndex = 43
        GroupBox4.TabStop = False
        GroupBox4.Text = "Apps Config"
        ' 
        ' Label14
        ' 
        Label14.AutoSize = True
        Label14.BackColor = Color.Transparent
        Label14.Location = New Point(205, 57)
        Label14.Name = "Label14"
        Label14.Size = New Size(105, 15)
        Label14.TabIndex = 45
        Label14.Text = "Bot MAX NUMBER"
        ' 
        ' nudBotEnable
        ' 
        nudBotEnable.Location = New Point(205, 72)
        nudBotEnable.Maximum = New Decimal(New Integer() {10, 0, 0, 0})
        nudBotEnable.Name = "nudBotEnable"
        nudBotEnable.Size = New Size(157, 23)
        nudBotEnable.TabIndex = 44
        nudBotEnable.Value = New Decimal(New Integer() {1, 0, 0, 0})
        ' 
        ' cmbBeOuTee
        ' 
        cmbBeOuTee.DropDownStyle = ComboBoxStyle.DropDownList
        cmbBeOuTee.FormattingEnabled = True
        cmbBeOuTee.Location = New Point(18, 30)
        cmbBeOuTee.Name = "cmbBeOuTee"
        cmbBeOuTee.Size = New Size(171, 23)
        cmbBeOuTee.TabIndex = 41
        ' 
        ' Label10
        ' 
        Label10.AutoSize = True
        Label10.BackColor = Color.Transparent
        Label10.Location = New Point(21, 14)
        Label10.Name = "Label10"
        Label10.Size = New Size(53, 15)
        Label10.TabIndex = 42
        Label10.Text = "BeOuTee"
        ' 
        ' Button1
        ' 
        Button1.BackColor = Color.Transparent
        Button1.FlatAppearance.BorderSize = 0
        Button1.FlatStyle = FlatStyle.Flat
        Button1.ForeColor = Color.Transparent
        Button1.Location = New Point(-1000, -1000)
        Button1.Name = "Button1"
        Button1.Size = New Size(1, 1)
        Button1.TabIndex = 0
        Button1.TabStop = False
        Button1.UseVisualStyleBackColor = False
        ' 
        ' Button3
        ' 
        Button3.BackColor = Color.Transparent
        Button3.FlatAppearance.BorderSize = 0
        Button3.FlatStyle = FlatStyle.Flat
        Button3.ForeColor = Color.Transparent
        Button3.Location = New Point(-1000, -1000)
        Button3.Name = "Button3"
        Button3.Size = New Size(1, 1)
        Button3.TabIndex = 0
        Button3.TabStop = False
        Button3.UseVisualStyleBackColor = False
        ' 
        ' btnConvertBinToIni
        ' 
        btnConvertBinToIni.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        btnConvertBinToIni.FlatStyle = FlatStyle.Flat
        btnConvertBinToIni.ForeColor = Color.White
        btnConvertBinToIni.Location = New Point(156, 1257)
        btnConvertBinToIni.Name = "btnConvertBinToIni"
        btnConvertBinToIni.Size = New Size(81, 35)
        btnConvertBinToIni.TabIndex = 45
        btnConvertBinToIni.Text = "Load Setup"
        btnConvertBinToIni.UseVisualStyleBackColor = False
        ' 
        ' FormConfiguracao
        ' 
        AutoScaleDimensions = New SizeF(7F, 15F)
        AutoScaleMode = AutoScaleMode.Font
        AutoScroll = True
        BackColor = Color.White
        ClientSize = New Size(802, 749)
        Controls.Add(btnConvertBinToIni)
        Controls.Add(GroupBox4)
        Controls.Add(btnSalvarIni)
        Controls.Add(GroupBox9)
        Controls.Add(GroupBox8)
        Controls.Add(GroupBox7)
        Controls.Add(GroupBox6)
        Controls.Add(GroupBox5)
        Controls.Add(Label8)
        Controls.Add(GBXsettings)
        Controls.Add(GroupBox2)
        Controls.Add(GroupBox1)
        Controls.Add(dgvCaminhos)
        Controls.Add(Label1)
        MinimumSize = New Size(500, 726)
        Name = "FormConfiguracao"
        StartPosition = FormStartPosition.CenterScreen
        Text = "System Settings"
        CType(dgvCaminhos, ComponentModel.ISupportInitialize).EndInit()
        GroupBox1.ResumeLayout(False)
        GroupBox1.PerformLayout()
        GroupBox2.ResumeLayout(False)
        GroupBox2.PerformLayout()
        GBXsettings.ResumeLayout(False)
        GBXsettings.PerformLayout()
        GroupBox5.ResumeLayout(False)
        GroupBox5.PerformLayout()
        GroupBox6.ResumeLayout(False)
        GroupBox6.PerformLayout()
        GroupBox7.ResumeLayout(False)
        GroupBox7.PerformLayout()
        GroupBox8.ResumeLayout(False)
        GroupBox8.PerformLayout()
        GroupBox9.ResumeLayout(False)
        GroupBox9.PerformLayout()
        CType(nudScriptNumber, ComponentModel.ISupportInitialize).EndInit()
        GroupBox4.ResumeLayout(False)
        GroupBox4.PerformLayout()
        CType(nudBotEnable, ComponentModel.ISupportInitialize).EndInit()
        ResumeLayout(False)
        PerformLayout()



    End Sub

    Friend WithEvents Label1 As Label
    Friend WithEvents dgvCaminhos As DataGridView
    Friend WithEvents txtChave As TextBox
    Friend WithEvents txtValor As TextBox
    Friend WithEvents btnAdicionar As Button
    Friend WithEvents txtBuyAmount As TextBox
    Friend WithEvents cmbBuyVolatility As ComboBox
    Friend WithEvents btnResetBuyConfig As Button
    Friend WithEvents txtSellBaseAmount As TextBox
    Friend WithEvents cmbSellVolatility As ComboBox
    Friend WithEvents btnResetSellConfig As Button
    Friend WithEvents GroupBox1 As GroupBox
    Friend WithEvents Label2 As Label
    Friend WithEvents Label3 As Label
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents Label4 As Label
    Friend WithEvents Label5 As Label
    Friend WithEvents GBXsettings As GroupBox
    Friend WithEvents Label6 As Label
    Friend WithEvents Label7 As Label
    Friend WithEvents Label8 As Label
    Friend WithEvents colId As DataGridViewTextBoxColumn
    Friend WithEvents colChave As DataGridViewTextBoxColumn
    Friend WithEvents colValor As DataGridViewTextBoxColumn
    Friend WithEvents colEditar As DataGridViewButtonColumn
    Friend WithEvents colExcluir As DataGridViewButtonColumn
    Friend WithEvents GroupBox5 As GroupBox
    Friend WithEvents Label11 As Label
    Friend WithEvents txtBuySwapRouter As TextBox
    Friend WithEvents Label12 As Label
    Friend WithEvents btnResetBuyAddresses As Button
    Friend WithEvents txtBuyPoolAddress As TextBox
    Friend WithEvents GroupBox6 As GroupBox
    Friend WithEvents Label13 As Label
    Friend WithEvents txtTokenSymbol As TextBox
    Friend WithEvents btnResetTokenConfig As Button
    Friend WithEvents txtTokenAddress As TextBox
    Friend WithEvents txtTokenDecimals As TextBox
    Friend WithEvents Label16 As Label
    Friend WithEvents Label15 As Label
    Friend WithEvents GroupBox7 As GroupBox
    Friend WithEvents Label17 As Label
    Friend WithEvents Label18 As Label
    Friend WithEvents txtToken1Address As TextBox
    Friend WithEvents txtToken1Decimals As TextBox
    Friend WithEvents Label19 As Label
    Friend WithEvents txtToken1Symbol As TextBox
    Friend WithEvents Label20 As Label
    Friend WithEvents btnResetToken1Config As Button
    Friend WithEvents GroupBox8 As GroupBox
    Friend WithEvents Label22 As Label
    Friend WithEvents txtTempo3 As TextBox
    Friend WithEvents Label24 As Label
    Friend WithEvents btnResetDelays As Button
    Friend WithEvents txtTempo1 As TextBox
    Friend WithEvents btnSaveBuyConfig As Button
    Friend WithEvents btnSaveSellConfig As Button
    Friend WithEvents btnSaveBuyAddresses As Button
    Friend WithEvents btnSaveTokenConfig As Button
    Friend WithEvents btnSaveToken1Config As Button
    Friend WithEvents btnSaveDelays As Button
    Friend WithEvents btnLoadTokens As Button
    Friend WithEvents Label21 As Label
    Friend WithEvents cmbToken1List As ComboBox
    Friend WithEvents btnLoadToken1List As Button
    Friend WithEvents cmbPaisMaquina As ComboBox
    Friend WithEvents btnSalvarMaquina As Button
    Friend WithEvents txtNomeMaquina As TextBox
    Friend WithEvents txtNumeroSerie As TextBox
    Friend WithEvents txtLocalizacao As TextBox
    Friend WithEvents txtCidade As TextBox
    Friend WithEvents txtResponsavel As TextBox
    Friend WithEvents txtObservacoes As TextBox
    Friend WithEvents GroupBox9 As GroupBox
    Friend WithEvents Button2 As Button
    Friend WithEvents Label26 As Label
    Friend WithEvents Label25 As Label
    Friend WithEvents Label27 As Label
    Friend WithEvents Label31 As Label
    Friend WithEvents Label30 As Label
    Friend WithEvents Label29 As Label
    Friend WithEvents Label28 As Label
    Friend WithEvents btnLoadBuyPools As Button
    Friend WithEvents cmbBuyPoolAddresses As ComboBox
    Friend WithEvents btnSalvarIni As Button
    Friend WithEvents cmbTokens As ComboBox
    Friend WithEvents nudScriptNumber As NumericUpDown
    Friend WithEvents Label9 As Label
    Friend WithEvents GroupBox4 As GroupBox
    Friend WithEvents Button1 As Button
    Friend WithEvents Button3 As Button
    Friend WithEvents Label10 As Label
    Friend WithEvents cmbBeOuTee As ComboBox
    Friend WithEvents CHKmaintainRatio As CheckBox
    Friend WithEvents cmbNetwork As ComboBox
    Friend WithEvents nudBotEnable As NumericUpDown
    Friend WithEvents chkVolatility As CheckBox
    Friend WithEvents Label14 As Label
    Friend WithEvents btnConvertBinToIni As Button




End Class