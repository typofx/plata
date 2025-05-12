Imports System.Data.SQLite
Imports System.Collections.Generic

Public Class MachineLocationData
    Private Shared ReadOnly ConnectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"

    ' Singleton instance
    Private Shared _instance As MachineLocationData
    Public Shared ReadOnly Property Instance() As MachineLocationData
        Get
            If _instance Is Nothing Then
                _instance = New MachineLocationData()
            End If
            Return _instance
        End Get
    End Property

    Private Sub New()
        ' Construtor privado para Singleton
    End Sub

    ' PROPRIEDADES PÚBLICAS (TODOS OS CAMPOS DO BANCO)
    Public ReadOnly Property Id As Integer
        Get
            Return GetIntFieldValue("Id")
        End Get
    End Property

    Public ReadOnly Property MachineName As String
        Get
            Return GetFieldValue("MachineName")
        End Get
    End Property

    Public ReadOnly Property SerialNumber As String
        Get
            Return GetFieldValue("SerialNumber")
        End Get
    End Property

    Public ReadOnly Property Location As String
        Get
            Return GetFieldValue("Location")
        End Get
    End Property

    Public ReadOnly Property Country As String
        Get
            Return GetFieldValue("Country")
        End Get
    End Property

    Public ReadOnly Property CountryCode As String
        Get
            Return GetFieldValue("CountryCode")
        End Get
    End Property

    Public ReadOnly Property City As String
        Get
            Return GetFieldValue("City")
        End Get
    End Property

    Public ReadOnly Property Responsible As String
        Get
            Return GetFieldValue("Responsible")
        End Get
    End Property

    Public ReadOnly Property RegistrationDate As String
        Get
            Return GetFieldValue("RegistrationDate")
        End Get
    End Property

    Public ReadOnly Property LastUpdate As String
        Get
            Return GetFieldValue("LastUpdate")
        End Get
    End Property

    Public ReadOnly Property Notes As String
        Get
            Return GetFieldValue("Notes")
        End Get
    End Property

    Public ReadOnly Property ScriptNumber As Integer
        Get
            Return GetIntFieldValue("ScriptNumber")
        End Get
    End Property

    Public ReadOnly Property BeOuTee As String
        Get
            Return GetFieldValue("BeOuTee")
        End Get
    End Property

    Public ReadOnly Property BotEnable As Integer
        Get
            Return GetIntFieldValue("nudBotEnable")
        End Get
    End Property

    ' MÉTODOS PRIVADOS DE APOIO
    Private Function GetFieldValue(fieldName As String) As String
        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()
            Dim sql As String = $"SELECT {fieldName} FROM Machine WHERE Id = 1"

            Using cmd As New SQLiteCommand(sql, conn)
                Try
                    Dim result = cmd.ExecuteScalar()
                    Return If(result IsNot Nothing AndAlso result IsNot DBNull.Value, result.ToString(), String.Empty)
                Catch ex As Exception
                    Return String.Empty
                End Try
            End Using
        End Using
    End Function

    Private Function GetIntFieldValue(fieldName As String) As Integer
        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()
            Dim sql As String = $"SELECT {fieldName} FROM Machine WHERE Id = 1"

            Using cmd As New SQLiteCommand(sql, conn)
                Try
                    Dim result = cmd.ExecuteScalar()
                    Return If(result IsNot Nothing AndAlso result IsNot DBNull.Value, Convert.ToInt32(result), 0)
                Catch ex As Exception
                    Return 0
                End Try
            End Using
        End Using
    End Function

    ' MÉTODO PARA TODOS OS DADOS (ATUALIZADO PARA INCLUIR A NOVA COLUNA)
    Public Function GetAllData() As Dictionary(Of String, String)
        Dim resultData As New Dictionary(Of String, String)()

        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()
            Dim sql As String = "SELECT * FROM Machine WHERE Id = 1"

            Using cmd As New SQLiteCommand(sql, conn)
                Using reader = cmd.ExecuteReader()
                    If reader.Read() Then
                        resultData.Add("Id", reader("Id").ToString())
                        resultData.Add("MachineName", reader("MachineName").ToString())
                        resultData.Add("SerialNumber", reader("SerialNumber").ToString())
                        resultData.Add("Location", reader("Location").ToString())
                        resultData.Add("Country", reader("Country").ToString())
                        resultData.Add("CountryCode", reader("CountryCode").ToString())
                        resultData.Add("City", reader("City").ToString())
                        resultData.Add("Responsible", reader("Responsible").ToString())
                        resultData.Add("RegistrationDate", reader("RegistrationDate").ToString())
                        resultData.Add("LastUpdate", reader("LastUpdate").ToString())
                        resultData.Add("Notes", If(reader("Notes") IsNot DBNull.Value, reader("Notes").ToString(), String.Empty))
                        resultData.Add("ScriptNumber", reader("ScriptNumber").ToString())
                        resultData.Add("BeOuTee", If(reader("BeOuTee") IsNot DBNull.Value, reader("BeOuTee").ToString(), "A"))
                        resultData.Add("nudBotEnable", If(reader("nudBotEnable") IsNot DBNull.Value, reader("nudBotEnable").ToString(), "0"))
                    End If
                End Using
            End Using
        End Using

        Return resultData
    End Function

    ' MÉTODOS DE VERIFICAÇÃO
    Public Function MachineTableExists() As Boolean
        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()
            Dim sql As String = "SELECT count(*) FROM sqlite_master WHERE type='table' AND name='Machine'"

            Using cmd As New SQLiteCommand(sql, conn)
                Dim count = Convert.ToInt32(cmd.ExecuteScalar())
                Return count > 0
            End Using
        End Using
    End Function

    Public Function MachineRecordExists() As Boolean
        If Not MachineTableExists() Then Return False

        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()
            Dim sql As String = "SELECT count(*) FROM Machine WHERE Id = 1"

            Using cmd As New SQLiteCommand(sql, conn)
                Dim count = Convert.ToInt32(cmd.ExecuteScalar())
                Return count > 0
            End Using
        End Using
    End Function
End Class