<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class FormIndex
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
        components = New ComponentModel.Container()
        MenuStrip1 = New MenuStrip()
        FilesToolStripMenuItem = New ToolStripMenuItem()
        Form2ToolStripMenuItem = New ToolStripMenuItem()
        Form3ToolStripMenuItem = New ToolStripMenuItem()
        CadastroToolStripMenuItem = New ToolStripMenuItem()
        PlatabotToolStripMenuItem = New ToolStripMenuItem()
        DatabaseScriptsP0ToolStripMenuItem = New ToolStripMenuItem()
        DatabaseScriptsP0ToolStripMenuItem1 = New ToolStripMenuItem()
        LiquidityPositionsToolStripMenuItem = New ToolStripMenuItem()
        ConfigToolStripMenuItem = New ToolStripMenuItem()
        PriceToolStripMenuItem = New ToolStripMenuItem()
        AboutToolStripMenuItem = New ToolStripMenuItem()
        ExitToolStripMenuItem = New ToolStripMenuItem()
        Panel1 = New Panel()
        Button3 = New Button()
        Button2 = New Button()
        Button1 = New Button()
        StatusStrip1 = New StatusStrip()
        ToolStripStatusLabel1 = New ToolStripStatusLabel()
        lblStatus = New ToolStripStatusLabel()
        PanelMain = New Panel()
        lblTotalBots = New Label()
        lstBots = New ListBox()
        lblWelcome = New Label()
        TimerAtualizacao = New Timer(components)
        lblBotLimit = New Label()
        MenuStrip1.SuspendLayout()
        Panel1.SuspendLayout()
        StatusStrip1.SuspendLayout()
        PanelMain.SuspendLayout()
        SuspendLayout()
        ' 
        ' MenuStrip1
        ' 
        MenuStrip1.BackColor = Color.WhiteSmoke
        MenuStrip1.Items.AddRange(New ToolStripItem() {FilesToolStripMenuItem, ConfigToolStripMenuItem, PriceToolStripMenuItem, AboutToolStripMenuItem, ExitToolStripMenuItem})
        MenuStrip1.Location = New Point(0, 0)
        MenuStrip1.Name = "MenuStrip1"
        MenuStrip1.Size = New Size(900, 24)
        MenuStrip1.TabIndex = 0
        MenuStrip1.Text = "MenuStrip1"
        ' 
        ' FilesToolStripMenuItem
        ' 
        FilesToolStripMenuItem.DropDownItems.AddRange(New ToolStripItem() {Form2ToolStripMenuItem, Form3ToolStripMenuItem, CadastroToolStripMenuItem, PlatabotToolStripMenuItem, DatabaseScriptsP0ToolStripMenuItem, DatabaseScriptsP0ToolStripMenuItem1, LiquidityPositionsToolStripMenuItem})
        FilesToolStripMenuItem.Font = New Font("Segoe UI", 9F, FontStyle.Regular, GraphicsUnit.Point, CByte(0))
        FilesToolStripMenuItem.Name = "FilesToolStripMenuItem"
        FilesToolStripMenuItem.Size = New Size(42, 20)
        FilesToolStripMenuItem.Text = "Files"
        ' 
        ' Form2ToolStripMenuItem
        ' 
        Form2ToolStripMenuItem.Name = "Form2ToolStripMenuItem"
        Form2ToolStripMenuItem.Size = New Size(176, 22)
        Form2ToolStripMenuItem.Text = "TOKENS"
        ' 
        ' Form3ToolStripMenuItem
        ' 
        Form3ToolStripMenuItem.Name = "Form3ToolStripMenuItem"
        Form3ToolStripMenuItem.Size = New Size(176, 22)
        Form3ToolStripMenuItem.Text = "DATABASE SCRIPTS"
        ' 
        ' CadastroToolStripMenuItem
        ' 
        CadastroToolStripMenuItem.Name = "CadastroToolStripMenuItem"
        CadastroToolStripMenuItem.Size = New Size(176, 22)
        CadastroToolStripMenuItem.Text = "REGISTER"
        ' 
        ' PlatabotToolStripMenuItem
        ' 
        PlatabotToolStripMenuItem.Name = "PlatabotToolStripMenuItem"
        PlatabotToolStripMenuItem.Size = New Size(176, 22)
        PlatabotToolStripMenuItem.Text = "Platabot"
        ' 
        ' DatabaseScriptsP0ToolStripMenuItem
        ' 
        DatabaseScriptsP0ToolStripMenuItem.Name = "DatabaseScriptsP0ToolStripMenuItem"
        DatabaseScriptsP0ToolStripMenuItem.Size = New Size(176, 22)
        DatabaseScriptsP0ToolStripMenuItem.Text = "Database Scripts P0"
        ' 
        ' DatabaseScriptsP0ToolStripMenuItem1
        ' 
        DatabaseScriptsP0ToolStripMenuItem1.Name = "DatabaseScriptsP0ToolStripMenuItem1"
        DatabaseScriptsP0ToolStripMenuItem1.Size = New Size(176, 22)
        DatabaseScriptsP0ToolStripMenuItem1.Text = "Database Scripts E0"
        ' 
        ' LiquidityPositionsToolStripMenuItem
        ' 
        LiquidityPositionsToolStripMenuItem.Name = "LiquidityPositionsToolStripMenuItem"
        LiquidityPositionsToolStripMenuItem.Size = New Size(176, 22)
        LiquidityPositionsToolStripMenuItem.Text = "Liquidity Positions"
        ' 
        ' ConfigToolStripMenuItem
        ' 
        ConfigToolStripMenuItem.Name = "ConfigToolStripMenuItem"
        ConfigToolStripMenuItem.Size = New Size(55, 20)
        ConfigToolStripMenuItem.Text = "Config"
        ' 
        ' PriceToolStripMenuItem
        ' 
        PriceToolStripMenuItem.Name = "PriceToolStripMenuItem"
        PriceToolStripMenuItem.Size = New Size(45, 20)
        PriceToolStripMenuItem.Text = "Price"
        ' 
        ' AboutToolStripMenuItem
        ' 
        AboutToolStripMenuItem.Name = "AboutToolStripMenuItem"
        AboutToolStripMenuItem.Size = New Size(52, 20)
        AboutToolStripMenuItem.Text = "About"
        ' 
        ' ExitToolStripMenuItem
        ' 
        ExitToolStripMenuItem.Name = "ExitToolStripMenuItem"
        ExitToolStripMenuItem.Size = New Size(38, 20)
        ExitToolStripMenuItem.Text = "Exit"
        ' 
        ' Panel1
        ' 
        Panel1.BackColor = Color.WhiteSmoke
        Panel1.Controls.Add(Button3)
        Panel1.Controls.Add(Button2)
        Panel1.Controls.Add(Button1)
        Panel1.Dock = DockStyle.Bottom
        Panel1.Location = New Point(0, 542)
        Panel1.Name = "Panel1"
        Panel1.Size = New Size(900, 50)
        Panel1.TabIndex = 1
        ' 
        ' Button3
        ' 
        Button3.Anchor = AnchorStyles.Top Or AnchorStyles.Right
        Button3.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        Button3.FlatAppearance.BorderSize = 0
        Button3.FlatStyle = FlatStyle.Flat
        Button3.Font = New Font("Segoe UI", 9F, FontStyle.Bold, GraphicsUnit.Point, CByte(0))
        Button3.ForeColor = Color.White
        Button3.ImageAlign = ContentAlignment.MiddleLeft
        Button3.Location = New Point(690, 10)
        Button3.Name = "Button3"
        Button3.Size = New Size(200, 30)
        Button3.TabIndex = 2
        Button3.Text = "WALLETS"
        Button3.UseVisualStyleBackColor = False
        ' 
        ' Button2
        ' 
        Button2.Anchor = AnchorStyles.Top Or AnchorStyles.Right
        Button2.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        Button2.FlatAppearance.BorderSize = 0
        Button2.FlatStyle = FlatStyle.Flat
        Button2.Font = New Font("Segoe UI", 9F, FontStyle.Bold, GraphicsUnit.Point, CByte(0))
        Button2.ForeColor = Color.White
        Button2.ImageAlign = ContentAlignment.MiddleLeft
        Button2.Location = New Point(480, 10)
        Button2.Name = "Button2"
        Button2.Size = New Size(200, 30)
        Button2.TabIndex = 1
        Button2.Text = "TOKENS"
        Button2.UseVisualStyleBackColor = False
        ' 
        ' Button1
        ' 
        Button1.Anchor = AnchorStyles.Top Or AnchorStyles.Right
        Button1.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        Button1.FlatAppearance.BorderSize = 0
        Button1.FlatStyle = FlatStyle.Flat
        Button1.Font = New Font("Segoe UI", 9F, FontStyle.Bold, GraphicsUnit.Point, CByte(0))
        Button1.ForeColor = Color.White
        Button1.ImageAlign = ContentAlignment.MiddleLeft
        Button1.Location = New Point(270, 10)
        Button1.Name = "Button1"
        Button1.Size = New Size(200, 30)
        Button1.TabIndex = 0
        Button1.Text = "DATABASE BOT"
        Button1.UseVisualStyleBackColor = False
        ' 
        ' StatusStrip1
        ' 
        StatusStrip1.BackColor = Color.WhiteSmoke
        StatusStrip1.Items.AddRange(New ToolStripItem() {ToolStripStatusLabel1, lblStatus})
        StatusStrip1.Location = New Point(0, 520)
        StatusStrip1.Name = "StatusStrip1"
        StatusStrip1.Size = New Size(900, 22)
        StatusStrip1.TabIndex = 2
        StatusStrip1.Text = "StatusStrip1"
        ' 
        ' ToolStripStatusLabel1
        ' 
        ToolStripStatusLabel1.Font = New Font("Segoe UI", 9F, FontStyle.Bold, GraphicsUnit.Point, CByte(0))
        ToolStripStatusLabel1.Name = "ToolStripStatusLabel1"
        ToolStripStatusLabel1.Size = New Size(45, 17)
        ToolStripStatusLabel1.Text = "Status:"
        ' 
        ' lblStatus
        ' 
        lblStatus.Name = "lblStatus"
        lblStatus.Size = New Size(39, 17)
        lblStatus.Text = "Ready"
        ' 
        ' PanelMain
        ' 
        PanelMain.BackColor = Color.White
        PanelMain.Controls.Add(lblBotLimit)
        PanelMain.Controls.Add(lblTotalBots)
        PanelMain.Controls.Add(lstBots)
        PanelMain.Controls.Add(lblWelcome)
        PanelMain.Dock = DockStyle.Fill
        PanelMain.Location = New Point(0, 24)
        PanelMain.Name = "PanelMain"
        PanelMain.Padding = New Padding(20)
        PanelMain.Size = New Size(900, 496)
        PanelMain.TabIndex = 3
        ' 
        ' lblTotalBots
        ' 
        lblTotalBots.AutoSize = True
        lblTotalBots.Location = New Point(480, 81)
        lblTotalBots.Name = "lblTotalBots"
        lblTotalBots.Size = New Size(61, 15)
        lblTotalBots.TabIndex = 2
        lblTotalBots.Text = "TotalBots3"
        ' 
        ' lstBots
        ' 
        lstBots.BorderStyle = BorderStyle.FixedSingle
        lstBots.FormattingEnabled = True
        lstBots.ItemHeight = 15
        lstBots.Location = New Point(37, 81)
        lstBots.Name = "lstBots"
        lstBots.Size = New Size(410, 77)
        lstBots.TabIndex = 1
        ' 
        ' lblWelcome
        ' 
        lblWelcome.AutoSize = True
        lblWelcome.Font = New Font("Segoe UI", 18F, FontStyle.Bold, GraphicsUnit.Point, CByte(0))
        lblWelcome.ForeColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        lblWelcome.Location = New Point(20, 20)
        lblWelcome.Name = "lblWelcome"
        lblWelcome.Size = New Size(151, 32)
        lblWelcome.TabIndex = 0
        lblWelcome.Text = "TypoFX BOT"
        ' 
        ' TimerAtualizacao
        ' 
        ' 
        ' lblBotLimit
        ' 
        lblBotLimit.AutoSize = True
        lblBotLimit.Location = New Point(484, 105)
        lblBotLimit.Name = "lblBotLimit"
        lblBotLimit.Size = New Size(34, 15)
        lblBotLimit.TabIndex = 3
        lblBotLimit.Text = "Limit"
        ' 
        ' FormIndex
        ' 
        AutoScaleDimensions = New SizeF(7F, 15F)
        AutoScaleMode = AutoScaleMode.Font
        BackColor = Color.White
        ClientSize = New Size(900, 592)
        Controls.Add(PanelMain)
        Controls.Add(StatusStrip1)
        Controls.Add(Panel1)
        Controls.Add(MenuStrip1)
        Font = New Font("Segoe UI", 9F, FontStyle.Regular, GraphicsUnit.Point, CByte(0))
        MainMenuStrip = MenuStrip1
        MinimumSize = New Size(916, 631)
        Name = "FormIndex"
        StartPosition = FormStartPosition.CenterScreen
        Text = "M0 - HOME - Main Dashboard"
        MenuStrip1.ResumeLayout(False)
        MenuStrip1.PerformLayout()
        Panel1.ResumeLayout(False)
        StatusStrip1.ResumeLayout(False)
        StatusStrip1.PerformLayout()
        PanelMain.ResumeLayout(False)
        PanelMain.PerformLayout()
        ResumeLayout(False)
        PerformLayout()
    End Sub

    Friend WithEvents MenuStrip1 As MenuStrip
    Friend WithEvents FilesToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents Form2ToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents Form3ToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents CadastroToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents PlatabotToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents ConfigToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents DatabaseScriptsP0ToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents DatabaseScriptsP0ToolStripMenuItem1 As ToolStripMenuItem
    Friend WithEvents ExitToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents Panel1 As Panel
    Friend WithEvents Button3 As Button
    Friend WithEvents Button2 As Button
    Friend WithEvents Button1 As Button
    Friend WithEvents PriceToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents AboutToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents StatusStrip1 As StatusStrip
    Friend WithEvents ToolStripStatusLabel1 As ToolStripStatusLabel
    Friend WithEvents lblStatus As ToolStripStatusLabel
    Friend WithEvents PanelMain As Panel
    Friend WithEvents lblWelcome As Label
    Friend WithEvents LiquidityPositionsToolStripMenuItem As ToolStripMenuItem
    Friend WithEvents lstBots As ListBox
    Friend WithEvents lblTotalBots As Label
    Friend WithEvents TimerAtualizacao As Timer
    Friend WithEvents lblBotLimit As Label
End Class