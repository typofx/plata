<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class FormEditar
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
        txtChave = New TextBox()
        txtValor = New TextBox()
        btnSalvar = New Button()
        btnCancelar = New Button()
        porra = New Label()
        SuspendLayout()
        ' 
        ' txtChave
        ' 
        txtChave.Location = New Point(175, 93)
        txtChave.Name = "txtChave"
        txtChave.PlaceholderText = "Chave"
        txtChave.Size = New Size(428, 23)
        txtChave.TabIndex = 0
        ' 
        ' txtValor
        ' 
        txtValor.Location = New Point(175, 141)
        txtValor.Name = "txtValor"
        txtValor.PlaceholderText = "Valor"
        txtValor.Size = New Size(428, 23)
        txtValor.TabIndex = 1
        ' 
        ' btnSalvar
        ' 
        btnSalvar.Location = New Point(301, 180)
        btnSalvar.Name = "btnSalvar"
        btnSalvar.Size = New Size(166, 23)
        btnSalvar.TabIndex = 2
        btnSalvar.Text = "Salvar"
        btnSalvar.UseVisualStyleBackColor = True
        ' 
        ' btnCancelar
        ' 
        btnCancelar.Location = New Point(301, 209)
        btnCancelar.Name = "btnCancelar"
        btnCancelar.Size = New Size(166, 23)
        btnCancelar.TabIndex = 3
        btnCancelar.Text = "Cancelar"
        btnCancelar.UseVisualStyleBackColor = True
        ' 
        ' porra
        ' 
        porra.AutoSize = True
        porra.Location = New Point(208, 51)
        porra.Name = "porra"
        porra.Size = New Size(41, 15)
        porra.TabIndex = 4
        porra.Text = "Label1"
        ' 
        ' FormEditar
        ' 
        AutoScaleDimensions = New SizeF(7F, 15F)
        AutoScaleMode = AutoScaleMode.Font
        ClientSize = New Size(800, 450)
        Controls.Add(porra)
        Controls.Add(btnCancelar)
        Controls.Add(btnSalvar)
        Controls.Add(txtValor)
        Controls.Add(txtChave)
        Name = "FormEditar"
        Text = "FormEditar"
        ResumeLayout(False)
        PerformLayout()
    End Sub

    Friend WithEvents txtChave As TextBox
    Friend WithEvents txtValor As TextBox
    Friend WithEvents btnSalvar As Button
    Friend WithEvents btnCancelar As Button
    Friend WithEvents porra As Label
End Class
