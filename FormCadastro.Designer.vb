Imports System.Drawing
Imports System.Windows.Forms

Namespace WinFormsApp1
    Partial Public Class FormCadastro
        Inherits Form
        Private txtWalletSecret As TextBox
        Private txtWalletAddress As TextBox



        Private Sub InitializeComponent()
            txtWalletSecret = New TextBox()
            txtWalletAddress = New TextBox()
            label1 = New Label()
            label2 = New Label()
            btnSalvar = New Button()
            btnSaveAllWallets = New Button()
            GroupBox1 = New GroupBox()
            Label3 = New Label()
            txtWalletTag = New TextBox()
            GroupBox2 = New GroupBox()
            btnLoadCsv = New Button()
            GroupBox1.SuspendLayout()
            GroupBox2.SuspendLayout()
            SuspendLayout()
            ' 
            ' txtWalletSecret
            ' 
            txtWalletSecret.Location = New Point(10, 110)
            txtWalletSecret.MaxLength = 66
            txtWalletSecret.Name = "txtWalletSecret"
            txtWalletSecret.Size = New Size(428, 23)
            txtWalletSecret.TabIndex = 0
            ' 
            ' txtWalletAddress
            ' 
            txtWalletAddress.Location = New Point(10, 71)
            txtWalletAddress.MaxLength = 42
            txtWalletAddress.Name = "txtWalletAddress"
            txtWalletAddress.Size = New Size(428, 23)
            txtWalletAddress.TabIndex = 1
            ' 
            ' label1
            ' 
            label1.AutoSize = True
            label1.Location = New Point(10, 96)
            label1.Name = "label1"
            label1.Size = New Size(75, 15)
            label1.TabIndex = 3
            label1.Text = "Wallet Secret"
            ' 
            ' label2
            ' 
            label2.AutoSize = True
            label2.Location = New Point(10, 56)
            label2.Name = "label2"
            label2.Size = New Size(78, 15)
            label2.TabIndex = 4
            label2.Text = "Wallet Adress"
            ' 
            ' btnSalvar
            ' 
            btnSalvar.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            btnSalvar.FlatStyle = FlatStyle.Flat
            btnSalvar.ForeColor = SystemColors.ButtonHighlight
            btnSalvar.Location = New Point(10, 138)
            btnSalvar.Name = "btnSalvar"
            btnSalvar.Size = New Size(157, 23)
            btnSalvar.TabIndex = 5
            btnSalvar.Text = "Save"
            btnSalvar.UseVisualStyleBackColor = False
            ' 
            ' btnSaveAllWallets
            ' 
            btnSaveAllWallets.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            btnSaveAllWallets.FlatStyle = FlatStyle.Flat
            btnSaveAllWallets.ForeColor = SystemColors.ButtonHighlight
            btnSaveAllWallets.Location = New Point(23, 60)
            btnSaveAllWallets.Name = "btnSaveAllWallets"
            btnSaveAllWallets.Size = New Size(157, 34)
            btnSaveAllWallets.TabIndex = 6
            btnSaveAllWallets.Text = "Save Wallet Setup"
            btnSaveAllWallets.UseVisualStyleBackColor = False
            ' 
            ' GroupBox1
            ' 
            GroupBox1.Controls.Add(Label3)
            GroupBox1.Controls.Add(txtWalletTag)
            GroupBox1.Controls.Add(txtWalletAddress)
            GroupBox1.Controls.Add(txtWalletSecret)
            GroupBox1.Controls.Add(btnSalvar)
            GroupBox1.Controls.Add(label1)
            GroupBox1.Controls.Add(label2)
            GroupBox1.Location = New Point(12, 12)
            GroupBox1.Name = "GroupBox1"
            GroupBox1.Size = New Size(452, 168)
            GroupBox1.TabIndex = 7
            GroupBox1.TabStop = False
            GroupBox1.Text = "Insert wallet"
            ' 
            ' Label3
            ' 
            Label3.AutoSize = True
            Label3.Location = New Point(10, 17)
            Label3.Name = "Label3"
            Label3.Size = New Size(60, 15)
            Label3.TabIndex = 7
            Label3.Text = "Wallet tag"
            ' 
            ' txtWalletTag
            ' 
            txtWalletTag.Location = New Point(10, 33)
            txtWalletTag.MaxLength = 42
            txtWalletTag.Name = "txtWalletTag"
            txtWalletTag.Size = New Size(428, 23)
            txtWalletTag.TabIndex = 6
            ' 
            ' GroupBox2
            ' 
            GroupBox2.Controls.Add(btnLoadCsv)
            GroupBox2.Controls.Add(btnSaveAllWallets)
            GroupBox2.Location = New Point(486, 18)
            GroupBox2.Name = "GroupBox2"
            GroupBox2.Size = New Size(202, 100)
            GroupBox2.TabIndex = 8
            GroupBox2.TabStop = False
            GroupBox2.Text = "Save config"
            ' 
            ' btnLoadCsv
            ' 
            btnLoadCsv.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            btnLoadCsv.FlatStyle = FlatStyle.Flat
            btnLoadCsv.ForeColor = SystemColors.ButtonHighlight
            btnLoadCsv.Location = New Point(23, 20)
            btnLoadCsv.Name = "btnLoadCsv"
            btnLoadCsv.Size = New Size(157, 34)
            btnLoadCsv.TabIndex = 7
            btnLoadCsv.Text = "load"
            btnLoadCsv.UseVisualStyleBackColor = False
            ' 
            ' FormCadastro
            ' 
            AutoScaleDimensions = New SizeF(7F, 15F)
            AutoScaleMode = AutoScaleMode.Font
            ClientSize = New Size(800, 520)
            Controls.Add(GroupBox2)
            Controls.Add(GroupBox1)
            Name = "FormCadastro"
            Text = "Cadastro de Wallets"
            GroupBox1.ResumeLayout(False)
            GroupBox1.PerformLayout()
            GroupBox2.ResumeLayout(False)
            ResumeLayout(False)
        End Sub

        Private label1 As Label
        Private label2 As Label
        Friend WithEvents btnSalvar As Button
        Friend WithEvents btnSaveAllWallets As Button
        Friend WithEvents GroupBox1 As GroupBox
        Friend WithEvents GroupBox2 As GroupBox
        Friend WithEvents btnLoadCsv As Button
        Private WithEvents Label3 As Label
        Private WithEvents txtWalletTag As TextBox
    End Class
End Namespace
