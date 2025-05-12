Namespace WinFormsApp1
    Partial Class FormData
        ''' <summary>
        ''' Required designer variable.
        ''' </summary>
        Private components As ComponentModel.IContainer = Nothing

        ''' <summary>
        ''' DataGridView para exibir os dados
        ''' </summary>
        Private dataGridView1 As System.Windows.Forms.DataGridView

        ''' <summary>
        ''' Clean up any resources being used.
        ''' </summary>
        ''' <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        Protected Overrides Sub Dispose(disposing As Boolean)
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
            MyBase.Dispose(disposing)
        End Sub

#Region "Windows Form Designer generated code"

        ''' <summary>
        ''' Required method for Designer support - do not modify
        ''' the contents of this method with the code editor.
        ''' </summary>
        Private Sub InitializeComponent()
            dataGridView1 = New System.Windows.Forms.DataGridView()
            CType(dataGridView1, ComponentModel.ISupportInitialize).BeginInit()
            SuspendLayout()
            ' 
            ' dataGridView1
            ' 
            dataGridView1.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
            dataGridView1.Location = New Drawing.Point(12, 12) ' Posição no formulário
            dataGridView1.Name = "dataGridView1"
            dataGridView1.Size = New Drawing.Size(776, 426) ' Tamanho
            dataGridView1.TabIndex = 0 ' Índice de tabulação
            ' 
            ' FormData
            ' 
            AutoScaleDimensions = New Drawing.SizeF(7.0F, 15.0F)
            AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
            ClientSize = New Drawing.Size(800, 450)
            Controls.Add(dataGridView1)
            Name = "FormData"
            Text = "FormData"
            AddHandler Load, New EventHandler(AddressOf FormData_Load)
            CType(dataGridView1, ComponentModel.ISupportInitialize).EndInit()
            ResumeLayout(False)
        End Sub

#End Region
    End Class
End Namespace