Public Class FRMscreenSaver
    Private Sub FRMscreenSaver_Click(sender As Object, e As EventArgs) Handles Me.Click
        Hide()
        FRMwelcome.Show()
        TimerAnimation.Stop()
    End Sub

    Private Sub FRMscreenSaver_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        FRMcompleted.TimerCompleted.Stop()
        TimerAnimation.Enabled = True
        TimerAnimation.Interval = 1000
        TimerAnimation.Start()
        LB_Animation.Location = New Point((Screen.PrimaryScreen.Bounds.Width - LB_Animation.Width) / 2, (Screen.PrimaryScreen.Bounds.Height - LB_Animation.Height) / 2)
    End Sub

    Private Sub LB_Animation_Click(sender As Object, e As EventArgs) Handles LB_Animation.Click
        Hide()
        FRMwelcome.Show()
        TimerAnimation.Stop()
        TimerAnimation.Enabled = False

    End Sub
    Private Sub TimerAnimation_Tick(sender As Object, e As EventArgs) Handles TimerAnimation.Tick
        If LB_Animation.Visible = True Then LB_Animation.Visible = False Else LB_Animation.Visible = True
    End Sub
End Class
