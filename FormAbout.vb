Public Class FormAbout
    Private Sub btnClose_Click(sender As Object, e As EventArgs) Handles btnClose.Click
        Me.Close()
    End Sub

    ' You can also add these additional features for better UX:
    Private Sub FormAbout_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ' Center the form on the screen when it loads
        Me.CenterToScreen()
    End Sub

    Private Sub FormAbout_KeyDown(sender As Object, e As KeyEventArgs) Handles MyBase.KeyDown
        ' Allow closing with ESC key
        If e.KeyCode = Keys.Escape Then
            Me.Close()
        End If
    End Sub
End Class