Imports System.Data.SQLite
Imports System.IO
Imports System.Text

Public Class DelayConfigEditor
    Private ReadOnly ConnectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"
    Private ReadOnly _iniFilePath As String

    ' Controles da UI (mantidos exatamente como estava)
    Public Property txtTempo1 As TextBox
    Public Property txtTempo2 As TextBox
    Public Property txtTempo3 As TextBox
    Public Property btnSave As Button
    Public Property btnReset As Button

    Public Sub New()
        ' Configuração com número de níveis para subir (1 nível)
        Dim levelsToGoUp As Integer = 1
        Dim workingDir As String = BuscarWorkingDirectory()
        _iniFilePath = ObterCaminhoINI(workingDir, levelsToGoUp)
    End Sub

    Private Function ObterCaminhoINI(basePath As String, levels As Integer) As String
        If String.IsNullOrEmpty(basePath) Then Return "settings.ini"

        Dim currentPath As String = basePath
        For i As Integer = 1 To levels
            Dim parent As DirectoryInfo = Directory.GetParent(currentPath)
            If parent Is Nothing Then Exit For
            currentPath = parent.FullName
        Next

        Return Path.Combine(currentPath, "settings.ini")
    End Function

    Private Function BuscarWorkingDirectory() As String
        Try
            Using conn As New SQLiteConnection(ConnectionString)
                conn.Open()
                Dim query As String = "SELECT Valor FROM Configuracoes WHERE Chave = 'WorkingDirectory'"
                Using cmd As New SQLiteCommand(query, conn)
                    Dim result = cmd.ExecuteScalar()
                    Return If(result?.ToString(), String.Empty)
                End Using
            End Using
        Catch ex As Exception
            Return String.Empty
        End Try
    End Function

    Public Sub Initialize()

        AddHandler btnReset.Click, AddressOf ResetConfig
        LoadConfig()
    End Sub

    Private Sub LoadConfig()
        Try
            If Not File.Exists(_iniFilePath) Then
                ResetConfig(Nothing, EventArgs.Empty)
                Return
            End If

            Dim iniContent = File.ReadAllText(_iniFilePath)
            Dim settings = ParseIniContent(iniContent)

            txtTempo1.Text = settings.GetValueOrDefault("Timestamp_APPROVE", "2")
            txtTempo3.Text = settings.GetValueOrDefault("Timestamp_CYCLE", "10")
            ' txtTempo2.Text = txtTempo1.Text ' Mantém compatibilidade

        Catch ex As Exception
            MessageBox.Show($"Erro ao carregar configurações: {ex.Message}", "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
            ResetConfig(Nothing, EventArgs.Empty)
        End Try
    End Sub

    Private Function ParseIniContent(iniContent As String) As Dictionary(Of String, String)
        Dim settings = New Dictionary(Of String, String)(StringComparer.OrdinalIgnoreCase)
        Dim currentSection As String = ""

        For Each line In iniContent.Split({vbCrLf, vbLf}, StringSplitOptions.RemoveEmptyEntries)
            Dim trimmedLine = line.Trim()

            If trimmedLine.StartsWith("[") AndAlso trimmedLine.EndsWith("]") Then
                currentSection = trimmedLine.Trim("[", "]") & "_"
            ElseIf trimmedLine.Contains("=") Then
                Dim parts = trimmedLine.Split("="c, 2)
                If parts.Length = 2 Then
                    Dim key = currentSection & parts(0).Trim()
                    Dim value = parts(1).Trim().Trim(""""c)
                    settings(key) = value
                End If
            End If
        Next

        Return settings
    End Function



    Private Function UpdateIniSection(content As String, section As String, values As IEnumerable(Of (String, String))) As String
        Dim sectionStart = $"[{section}]"
        Dim sectionEnd = vbCrLf & vbCrLf
        Dim newSectionContent As New StringBuilder()

        newSectionContent.AppendLine(sectionStart)
        For Each item In values
            newSectionContent.AppendLine($"{item.Item1} = {item.Item2}")
        Next

        If content.Contains(sectionStart) Then
            Dim startIndex = content.IndexOf(sectionStart)
            Dim endIndex = content.IndexOf(sectionEnd, startIndex)
            If endIndex = -1 Then endIndex = content.Length

            Return content.Remove(startIndex, endIndex - startIndex).Insert(startIndex, newSectionContent.ToString())
        Else
            Return content & vbCrLf & newSectionContent.ToString()
        End If
    End Function

    Private Sub ResetConfig(sender As Object, e As EventArgs)
        txtTempo1.Text = "2"
        txtTempo2.Text = "2"
        txtTempo3.Text = "10"
    End Sub

    Public Sub Cleanup()

        RemoveHandler btnReset.Click, AddressOf ResetConfig
    End Sub
End Class