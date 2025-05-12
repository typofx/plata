Imports System.IO
Imports System.Text
Imports System.Data.SQLite

Public Class FormConexao
    Private ReadOnly CONFIG_FILE As String = Path.Combine(GetParentDirectory(4), "config.ini")
    Private ReadOnly _pathAutoRun As String = Path.Combine("data", "config.db")
    Private ReadOnly _workingDirName As String = "UniswapTrader"
    Private _caminhoAtual As String
    Private _workingDirectory As String

    Public Sub New()

        _workingDirectory = VerificarWorkingDirectoryFisico()
        _caminhoAtual = VerificarCaminhoBancoDadosFisico()


        AtualizarTodosOsArmazenamentos()
    End Sub

    Private Function VerificarWorkingDirectoryFisico() As String
        ' 1. Verifica 4 níveis acima
        Dim caminhoPadrao = Path.Combine(GetParentDirectory(4), _workingDirName)
        If Directory.Exists(caminhoPadrao) Then Return caminhoPadrao


        Return caminhoPadrao
    End Function

    Private Function VerificarCaminhoBancoDadosFisico() As String
        ' 1. Verifica 4 níveis acima
        Dim caminhoPadrao = Path.Combine(GetParentDirectory(4), _pathAutoRun)
        If File.Exists(caminhoPadrao) Then Return caminhoPadrao


        Return caminhoPadrao
    End Function

    Private Sub AtualizarTodosOsArmazenamentos()

        Directory.CreateDirectory(Path.GetDirectoryName(CONFIG_FILE))


        SalvarConfigIni()


        If File.Exists(_caminhoAtual) Then
            AtualizarBancoDados()
        End If
    End Sub

    Private Sub SalvarConfigIni()
        Try
            Dim conteudoIni As String = $"[Settings]{vbCrLf}" &
                                      $"SFT_FOLDER=""{_caminhoAtual}""{vbCrLf}" &
                                      $"WORKING_DIRECTORY=""{_workingDirectory}"""

            File.WriteAllText(CONFIG_FILE, conteudoIni, Encoding.UTF8)
        Catch ex As Exception
            Debug.WriteLine("Erro ao salvar arquivo .ini: " & ex.Message)
        End Try
    End Sub

    Private Sub AtualizarBancoDados()
        Try
            Using conn As New SQLiteConnection($"Data Source={_caminhoAtual};Version=3;")
                conn.Open()

                ' Usando MERGE (INSERT OR REPLACE) para garantir que a chave exista
                Dim sql = "INSERT OR REPLACE INTO Configuracoes (Chave, Valor) VALUES ('WorkingDirectory', @Valor)"

                Using cmd As New SQLiteCommand(sql, conn)
                    cmd.Parameters.AddWithValue("@Valor", _workingDirectory)
                    cmd.ExecuteNonQuery()
                End Using
            End Using
        Catch ex As Exception
            Debug.WriteLine("Erro ao atualizar banco de dados: " & ex.Message)
        End Try
    End Sub


    Public Function ObterCaminho() As String
        Return _caminhoAtual
    End Function

    Public Function ObterWorkingDirectory() As String
        Return _workingDirectory
    End Function


    Public Function DefinirNovoCaminho(novoCaminho As String) As Boolean
        If Not File.Exists(novoCaminho) Then Return False

        _caminhoAtual = novoCaminho
        AtualizarTodosOsArmazenamentos()
        Return True
    End Function

    Private Function GetParentDirectory(levels As Integer) As String
        Dim dir As String = Application.StartupPath
        For i As Integer = 1 To levels
            Dim parent = Directory.GetParent(dir)
            If parent Is Nothing Then Return dir
            dir = parent.FullName
        Next
        Return dir
    End Function
End Class