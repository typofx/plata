Imports System.Data.SQLite
Imports Org.BouncyCastle.Asn1.Ocsp

Public Class FormEditar
    ' Propriedades para armazenar o Id, a chave e o valor
    Public Property Id As Integer
    Public Property Chave As String
    Public Property Valor As String

    ' String de conexão com o SQLite
    Private ReadOnly ConnectionString As String =
    "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"

    ' Evento Load do formulário: preenche os campos com os valores atuais
    Private Sub FormEditar_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ' Exibe o Id no Label
        porra.Text = "Id: " & Id.ToString()

        ' Preenche os campos de Chave e Valor
        txtChave.Text = Chave
        txtValor.Text = Valor
    End Sub

    ' Botão Salvar: atualiza as propriedades e salva no banco de dados
    Private Sub btnSalvar_Click(sender As Object, e As EventArgs) Handles btnSalvar.Click
        If String.IsNullOrEmpty(txtChave.Text.Trim()) Or String.IsNullOrEmpty(txtValor.Text.Trim()) Then
            MessageBox.Show("Preencha a chave e o valor.", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return
        End If

        ' Atualiza as propriedades com os valores dos campos
        Chave = txtChave.Text.Trim()
        Valor = txtValor.Text.Trim()

        ' Salva as alterações no banco de dados
        Try
            SalvarAlteracoesNoBanco()
            MessageBox.Show("Caminho editado com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Me.DialogResult = DialogResult.OK
            Me.Close()
        Catch ex As Exception
            MessageBox.Show("Erro ao salvar alterações: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' Método para salvar as alterações no banco de dados
    Private Sub SalvarAlteracoesNoBanco()
        Try
            Using connection As New SQLiteConnection(ConnectionString)
                connection.Open()
                Dim query As String = "UPDATE Configuracoes SET Chave = @Chave, Valor = @Valor WHERE Id = @Id"
                Using command As New SQLiteCommand(query, connection)
                    command.Parameters.AddWithValue("@Id", Id)
                    command.Parameters.AddWithValue("@Chave", Chave)
                    command.Parameters.AddWithValue("@Valor", Valor)

                    ' Executa a query e verifica quantas linhas foram afetadas
                    Dim linhasAfetadas As Integer = command.ExecuteNonQuery()
                    If linhasAfetadas = 0 Then
                        MessageBox.Show("Nenhum registro foi atualizado. Verifique o Id.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                    Else
                        MessageBox.Show("Registro atualizado com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    End If
                End Using
            End Using
        Catch ex As Exception
            MessageBox.Show("Erro ao salvar alterações no banco de dados: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Throw ' Relança a exceção para que o método btnSalvar_Click possa tratá-la
        End Try
    End Sub

    ' Botão Cancelar: fecha o modal sem salvar alterações
    Private Sub btnCancelar_Click(sender As Object, e As EventArgs) Handles btnCancelar.Click
        Me.DialogResult = DialogResult.Cancel
        Me.Close()
    End Sub


End Class