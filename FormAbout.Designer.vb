<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class FormAbout
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

    'OBSERVAÇÃO: o procedimento a seguir é exigido pelo Windows Form Designer
    'Pode ser modificado usando o Windows Form Designer.  
    'Não o modifique usando o editor de códigos.
    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Dim resources As System.ComponentModel.ComponentResourceManager = New System.ComponentModel.ComponentResourceManager(GetType(FormAbout))
        Panel1 = New Panel()
        lblTitle = New Label()
        lblVersion = New Label()
        PictureBox1 = New PictureBox()
        lblDescription = New Label()
        lblCopyright = New Label()
        btnClose = New Button()
        Panel1.SuspendLayout()
        CType(PictureBox1, ComponentModel.ISupportInitialize).BeginInit()
        SuspendLayout()
        ' 
        ' Panel1
        ' 
        Panel1.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        Panel1.Controls.Add(lblTitle)
        Panel1.Dock = DockStyle.Top
        Panel1.Location = New Point(0, 0)
        Panel1.Name = "Panel1"
        Panel1.Size = New Size(500, 80)
        Panel1.TabIndex = 0
        ' 
        ' lblTitle
        ' 
        lblTitle.AutoSize = True
        lblTitle.Font = New Font("Segoe UI", 18F, FontStyle.Bold)
        lblTitle.ForeColor = Color.White
        lblTitle.Location = New Point(20, 20)
        lblTitle.Name = "lblTitle"
        lblTitle.Size = New Size(112, 32)
        lblTitle.TabIndex = 0
        lblTitle.Text = "TFX-BOT"
        ' 
        ' lblVersion
        ' 
        lblVersion.AutoSize = True
        lblVersion.Font = New Font("Segoe UI", 12F)
        lblVersion.Location = New Point(20, 100)
        lblVersion.Name = "lblVersion"
        lblVersion.Size = New Size(123, 21)
        lblVersion.TabIndex = 1
        lblVersion.Text = "Version: 1.39.0.0"
        ' 
        ' PictureBox1
        ' 
        PictureBox1.Image = CType(resources.GetObject("PictureBox1.Image"), Image)
        PictureBox1.Location = New Point(350, 90)
        PictureBox1.Name = "PictureBox1"
        PictureBox1.Size = New Size(120, 120)
        PictureBox1.SizeMode = PictureBoxSizeMode.Zoom
        PictureBox1.TabIndex = 2
        PictureBox1.TabStop = False
        ' 
        ' lblDescription
        ' 
        lblDescription.Font = New Font("Segoe UI", 9.75F)
        lblDescription.Location = New Point(20, 140)
        lblDescription.Name = "lblDescription"
        lblDescription.Size = New Size(300, 100)
        lblDescription.TabIndex = 3
        lblDescription.Text = "TFX-BOT is an advanced automation tool designed to streamline your workflow and increase productivity with powerful features and intuitive interface."
        ' 
        ' lblCopyright
        ' 
        lblCopyright.AutoSize = True
        lblCopyright.Font = New Font("Segoe UI", 8.25F)
        lblCopyright.ForeColor = Color.Gray
        lblCopyright.Location = New Point(20, 260)
        lblCopyright.Name = "lblCopyright"
        lblCopyright.Size = New Size(184, 13)
        lblCopyright.TabIndex = 4
        lblCopyright.Text = "© 2025 TypoFX. All rights reserved."
        ' 
        ' btnClose
        ' 
        btnClose.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
        btnClose.FlatAppearance.BorderSize = 0
        btnClose.FlatStyle = FlatStyle.Flat
        btnClose.Font = New Font("Segoe UI", 9.75F)
        btnClose.ForeColor = Color.White
        btnClose.Location = New Point(380, 250)
        btnClose.Name = "btnClose"
        btnClose.Size = New Size(90, 30)
        btnClose.TabIndex = 5
        btnClose.Text = "Close"
        btnClose.UseVisualStyleBackColor = False
        ' 
        ' FormAbout
        ' 
        AutoScaleDimensions = New SizeF(7F, 15F)
        AutoScaleMode = AutoScaleMode.Font
        BackColor = Color.White
        ClientSize = New Size(500, 300)
        Controls.Add(btnClose)
        Controls.Add(lblCopyright)
        Controls.Add(lblDescription)
        Controls.Add(PictureBox1)
        Controls.Add(lblVersion)
        Controls.Add(Panel1)
        FormBorderStyle = FormBorderStyle.FixedDialog
        MaximizeBox = False
        MinimizeBox = False
        Name = "FormAbout"
        StartPosition = FormStartPosition.CenterScreen
        Text = "About TFX-BOT"
        Panel1.ResumeLayout(False)
        Panel1.PerformLayout()
        CType(PictureBox1, ComponentModel.ISupportInitialize).EndInit()
        ResumeLayout(False)
        PerformLayout()
    End Sub

    Friend WithEvents Panel1 As Panel
    Friend WithEvents lblTitle As Label
    Friend WithEvents lblVersion As Label
    Friend WithEvents PictureBox1 As PictureBox
    Friend WithEvents lblDescription As Label
    Friend WithEvents lblCopyright As Label
    Friend WithEvents btnClose As Button
End Class