<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class FormAproveToken
    Inherits System.Windows.Forms.Form

    'Descartar substituições de formulário para limpar a lista de componentes.
    <System.Diagnostics.DebuggerNonUserCode()> _
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

    'OBSERVAÇÃO: o procedimento a seguir é exigido pelo Windows Form Designer
    'Pode ser modificado usando o Windows Form Designer.  
    'Não o modifique usando o editor de códigos.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
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
        GroupBox5 = New GroupBox()
        btnSaveLog = New Button()
        btnLoadBuyPools = New Button()
        cmbBuyPoolAddresses = New ComboBox()
        Label11 = New Label()
        txtBuySwapRouter = New TextBox()
        Label12 = New Label()
        btnResetBuyAddresses = New Button()
        txtBuyPoolAddress = New TextBox()
        btnSaveBuyAddresses = New Button()
        GroupBox3 = New GroupBox()
        Label6 = New Label()
        btnApproveToken = New Button()
        btnSaveSellConfig = New Button()
        txtSellBaseAmount = New TextBox()
        txtApproveTokenStatus = New TextBox()
        GroupBox6.SuspendLayout()
        GroupBox5.SuspendLayout()
        GroupBox3.SuspendLayout()
        SuspendLayout()
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
        GroupBox6.Location = New Point(12, 40)
        GroupBox6.Name = "GroupBox6"
        GroupBox6.Size = New Size(376, 224)
        GroupBox6.TabIndex = 23
        GroupBox6.TabStop = False
        GroupBox6.Text = "Token 0"
        ' 
        ' cmbTokens
        ' 
        cmbTokens.DropDownStyle = ComboBoxStyle.DropDownList
        cmbTokens.FormattingEnabled = True
        cmbTokens.Location = New Point(6, 41)
        cmbTokens.Name = "cmbTokens"
        cmbTokens.Size = New Size(363, 23)
        cmbTokens.TabIndex = 29
        ' 
        ' Label21
        ' 
        Label21.AutoSize = True
        Label21.ImeMode = ImeMode.NoControl
        Label21.Location = New Point(8, 23)
        Label21.Name = "Label21"
        Label21.Size = New Size(73, 15)
        Label21.TabIndex = 27
        Label21.Text = "Token Name"
        ' 
        ' btnLoadTokens
        ' 
        btnLoadTokens.ImeMode = ImeMode.NoControl
        btnLoadTokens.Location = New Point(245, 15)
        btnLoadTokens.Name = "btnLoadTokens"
        btnLoadTokens.Size = New Size(124, 23)
        btnLoadTokens.TabIndex = 24
        btnLoadTokens.Text = "Load Tokens"
        btnLoadTokens.UseVisualStyleBackColor = True
        ' 
        ' Label16
        ' 
        Label16.AutoSize = True
        Label16.ImeMode = ImeMode.NoControl
        Label16.Location = New Point(6, 153)
        Label16.Name = "Label16"
        Label16.Size = New Size(83, 15)
        Label16.TabIndex = 23
        Label16.Text = "Token Address"
        ' 
        ' Label15
        ' 
        Label15.AutoSize = True
        Label15.ImeMode = ImeMode.NoControl
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
        Label13.ImeMode = ImeMode.NoControl
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
        btnResetTokenConfig.ImeMode = ImeMode.NoControl
        btnResetTokenConfig.Location = New Point(164, 15)
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
        btnSaveTokenConfig.ImeMode = ImeMode.NoControl
        btnSaveTokenConfig.Location = New Point(-1000, -1000)
        btnSaveTokenConfig.Name = "btnSaveTokenConfig"
        btnSaveTokenConfig.Size = New Size(1, 1)
        btnSaveTokenConfig.TabIndex = 0
        btnSaveTokenConfig.TabStop = False
        btnSaveTokenConfig.UseVisualStyleBackColor = False
        ' 
        ' GroupBox5
        ' 
        GroupBox5.Controls.Add(btnSaveLog)
        GroupBox5.Controls.Add(btnLoadBuyPools)
        GroupBox5.Controls.Add(cmbBuyPoolAddresses)
        GroupBox5.Controls.Add(Label11)
        GroupBox5.Controls.Add(txtBuySwapRouter)
        GroupBox5.Controls.Add(Label12)
        GroupBox5.Controls.Add(btnResetBuyAddresses)
        GroupBox5.Controls.Add(txtBuyPoolAddress)
        GroupBox5.Controls.Add(btnSaveBuyAddresses)
        GroupBox5.Location = New Point(394, 40)
        GroupBox5.Name = "GroupBox5"
        GroupBox5.Size = New Size(376, 190)
        GroupBox5.TabIndex = 30
        GroupBox5.TabStop = False
        GroupBox5.Text = "Allowance addresses"
        ' 
        ' btnSaveLog
        ' 
        btnSaveLog.ImeMode = ImeMode.NoControl
        btnSaveLog.Location = New Point(172, 161)
        btnSaveLog.Name = "btnSaveLog"
        btnSaveLog.Size = New Size(80, 23)
        btnSaveLog.TabIndex = 31
        btnSaveLog.Text = "Save log"
        btnSaveLog.UseVisualStyleBackColor = True
        ' 
        ' btnLoadBuyPools
        ' 
        btnLoadBuyPools.ImeMode = ImeMode.NoControl
        btnLoadBuyPools.Location = New Point(86, 161)
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
        cmbBuyPoolAddresses.Location = New Point(8, 40)
        cmbBuyPoolAddresses.Name = "cmbBuyPoolAddresses"
        cmbBuyPoolAddresses.Size = New Size(362, 23)
        cmbBuyPoolAddresses.TabIndex = 30
        ' 
        ' Label11
        ' 
        Label11.AutoSize = True
        Label11.ImeMode = ImeMode.NoControl
        Label11.Location = New Point(6, 114)
        Label11.Name = "Label11"
        Label11.Size = New Size(118, 15)
        Label11.TabIndex = 18
        Label11.Text = "Swap Router Address"
        ' 
        ' txtBuySwapRouter
        ' 
        txtBuySwapRouter.Location = New Point(6, 132)
        txtBuySwapRouter.Name = "txtBuySwapRouter"
        txtBuySwapRouter.Size = New Size(364, 23)
        txtBuySwapRouter.TabIndex = 17
        ' 
        ' Label12
        ' 
        Label12.AutoSize = True
        Label12.ImeMode = ImeMode.NoControl
        Label12.Location = New Point(6, 70)
        Label12.Name = "Label12"
        Label12.Size = New Size(76, 15)
        Label12.TabIndex = 15
        Label12.Text = "Pool Address"
        ' 
        ' btnResetBuyAddresses
        ' 
        btnResetBuyAddresses.ImeMode = ImeMode.NoControl
        btnResetBuyAddresses.Location = New Point(5, 161)
        btnResetBuyAddresses.Name = "btnResetBuyAddresses"
        btnResetBuyAddresses.Size = New Size(75, 23)
        btnResetBuyAddresses.TabIndex = 8
        btnResetBuyAddresses.Text = "Reset"
        btnResetBuyAddresses.UseVisualStyleBackColor = True
        ' 
        ' txtBuyPoolAddress
        ' 
        txtBuyPoolAddress.Location = New Point(6, 88)
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
        btnSaveBuyAddresses.ImeMode = ImeMode.NoControl
        btnSaveBuyAddresses.Location = New Point(-1000, -1000)
        btnSaveBuyAddresses.Name = "btnSaveBuyAddresses"
        btnSaveBuyAddresses.Size = New Size(1, 1)
        btnSaveBuyAddresses.TabIndex = 0
        btnSaveBuyAddresses.TabStop = False
        btnSaveBuyAddresses.UseVisualStyleBackColor = False
        ' 
        ' GroupBox3
        ' 
        GroupBox3.Controls.Add(Label6)
        GroupBox3.Controls.Add(btnApproveToken)
        GroupBox3.Controls.Add(btnSaveSellConfig)
        GroupBox3.Controls.Add(txtSellBaseAmount)
        GroupBox3.Location = New Point(12, 270)
        GroupBox3.Name = "GroupBox3"
        GroupBox3.Size = New Size(376, 81)
        GroupBox3.TabIndex = 31
        GroupBox3.TabStop = False
        GroupBox3.Text = "Sell Settings"
        ' 
        ' Label6
        ' 
        Label6.AutoSize = True
        Label6.ImeMode = ImeMode.NoControl
        Label6.Location = New Point(6, 22)
        Label6.Name = "Label6"
        Label6.Size = New Size(51, 15)
        Label6.TabIndex = 15
        Label6.Text = "Amount"
        ' 
        ' btnApproveToken
        ' 
        btnApproveToken.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        btnApproveToken.FlatStyle = FlatStyle.Flat
        btnApproveToken.ForeColor = Color.White
        btnApproveToken.ImeMode = ImeMode.NoControl
        btnApproveToken.Location = New Point(229, 30)
        btnApproveToken.Name = "btnApproveToken"
        btnApproveToken.Size = New Size(128, 33)
        btnApproveToken.TabIndex = 23
        btnApproveToken.Text = "&Approve"
        btnApproveToken.UseVisualStyleBackColor = False
        ' 
        ' btnSaveSellConfig
        ' 
        btnSaveSellConfig.BackColor = Color.Transparent
        btnSaveSellConfig.FlatAppearance.BorderSize = 0
        btnSaveSellConfig.FlatStyle = FlatStyle.Flat
        btnSaveSellConfig.ForeColor = Color.Transparent
        btnSaveSellConfig.ImeMode = ImeMode.NoControl
        btnSaveSellConfig.Location = New Point(-1000, -1000)
        btnSaveSellConfig.Name = "btnSaveSellConfig"
        btnSaveSellConfig.Size = New Size(1, 1)
        btnSaveSellConfig.TabIndex = 0
        btnSaveSellConfig.TabStop = False
        btnSaveSellConfig.UseVisualStyleBackColor = False
        ' 
        ' txtSellBaseAmount
        ' 
        txtSellBaseAmount.Location = New Point(6, 40)
        txtSellBaseAmount.Name = "txtSellBaseAmount"
        txtSellBaseAmount.Size = New Size(156, 23)
        txtSellBaseAmount.TabIndex = 9
        ' 
        ' txtApproveTokenStatus
        ' 
        txtApproveTokenStatus.BackColor = Color.Black
        txtApproveTokenStatus.Font = New Font("Consolas", 10F)
        txtApproveTokenStatus.ForeColor = Color.LimeGreen
        txtApproveTokenStatus.Location = New Point(12, 357)
        txtApproveTokenStatus.Multiline = True
        txtApproveTokenStatus.Name = "txtApproveTokenStatus"
        txtApproveTokenStatus.ReadOnly = True
        txtApproveTokenStatus.ScrollBars = ScrollBars.Vertical
        txtApproveTokenStatus.Size = New Size(758, 148)
        txtApproveTokenStatus.TabIndex = 26
        ' 
        ' FormAproveToken
        ' 
        AutoScaleDimensions = New SizeF(7F, 15F)
        AutoScaleMode = AutoScaleMode.Font
        ClientSize = New Size(800, 511)
        Controls.Add(txtApproveTokenStatus)
        Controls.Add(GroupBox3)
        Controls.Add(GroupBox5)
        Controls.Add(GroupBox6)
        Name = "FormAproveToken"
        Text = "FormAproveToken"
        GroupBox6.ResumeLayout(False)
        GroupBox6.PerformLayout()
        GroupBox5.ResumeLayout(False)
        GroupBox5.PerformLayout()
        GroupBox3.ResumeLayout(False)
        GroupBox3.PerformLayout()
        ResumeLayout(False)
        PerformLayout()
    End Sub

    Friend WithEvents GroupBox6 As GroupBox
    Friend WithEvents cmbTokens As ComboBox
    Friend WithEvents Label21 As Label
    Friend WithEvents btnLoadTokens As Button
    Friend WithEvents Label16 As Label
    Friend WithEvents Label15 As Label
    Friend WithEvents txtTokenAddress As TextBox
    Friend WithEvents txtTokenDecimals As TextBox
    Friend WithEvents Label13 As Label
    Friend WithEvents txtTokenSymbol As TextBox
    Friend WithEvents btnResetTokenConfig As Button
    Friend WithEvents btnSaveTokenConfig As Button
    Friend WithEvents GroupBox5 As GroupBox
    Friend WithEvents btnLoadBuyPools As Button
    Friend WithEvents cmbBuyPoolAddresses As ComboBox
    Friend WithEvents Label11 As Label
    Friend WithEvents txtBuySwapRouter As TextBox
    Friend WithEvents Label12 As Label
    Friend WithEvents btnResetBuyAddresses As Button
    Friend WithEvents txtBuyPoolAddress As TextBox
    Friend WithEvents btnSaveBuyAddresses As Button
    Friend WithEvents GroupBox3 As GroupBox
    Friend WithEvents Label6 As Label
    Friend WithEvents btnApproveToken As Button
    Friend WithEvents btnSaveSellConfig As Button
    Friend WithEvents txtSellBaseAmount As TextBox
    Friend WithEvents txtApproveTokenStatus As TextBox
    Friend WithEvents btnSaveLog As Button
End Class
