Imports System.Data.SQLite

Public Module TempoExecucaoHelper
    Private ReadOnly ConnectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"

    Public Function GetTempo1() As Integer
        Return GetTempoFromDB("tempo1")
    End Function

    Public Function GetTempo2() As Integer
        Return GetTempoFromDB("tempo2")
    End Function

    Public Function GetTempo3() As Integer
        Return GetTempoFromDB("tempo3")
    End Function

    Private Function GetTempoFromDB(tempoName As String) As Integer
        Dim valorRetorno As Integer = 1000 ' Valor padrão

        Try
            Using conn As New SQLiteConnection(ConnectionString)
                conn.Open()
                Dim query = "SELECT Valor FROM TemposExecucao WHERE Nome = @Nome"

                Using cmd As New SQLiteCommand(query, conn)
                    cmd.Parameters.AddWithValue("@Nome", tempoName)
                    Dim result = cmd.ExecuteScalar()

                    If result IsNot Nothing Then
                        If Integer.TryParse(result.ToString(), valorRetorno) Then
                            ' Verifica se o valor é válido (maior que 0)
                            If valorRetorno <= 0 Then
                                valorRetorno = 1000
                                Debug.WriteLine($"Valor inválido para {tempoName}. Usando padrão.")
                            End If
                        Else
                            Debug.WriteLine($"Valor não numérico para {tempoName}. Usando padrão.")
                        End If
                    Else
                        Debug.WriteLine($"Nenhum resultado encontrado para {tempoName}. Usando padrão.")
                    End If
                End Using
            End Using
        Catch ex As Exception
            Debug.WriteLine($"ERRO ao buscar {tempoName}: {ex.Message}")
        End Try

        Return valorRetorno
    End Function
End Module