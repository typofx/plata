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
    
    
    Public Class FRMbackground

    Dim countSec As Integer

    Private Sub FRMbackground_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'LB_Animation.Location = New Point((Screen.PrimaryScreen.Bounds.Width - LB_Animation.Width) / 2, (Screen.PrimaryScreen.Bounds.Height - LB_Animation.Height) / 2)

        TimerForAll.Enabled = True
        TimerForAll.Start()


    End Sub

    Private Sub TimerForAll_Tick(sender As Object, e As EventArgs) Handles TimerForAll.Tick


        If (LB_Num.Text <> "0") Then
            LB_Num.Text = "0"
            LB_Animation.Visible = False

        Else
            LB_Num.Text = "1"
            LB_Animation.Visible = True

        End If

        If GBwelcome.Visible = True Then
            If countSec = 2 Then
                GBwelcome.Visible = False
                boxSelectLanguage.Visible = True
            End If
            countSec = countSec + 1
        Else
            countSec = 0
        End If

        LBcount.Text = countSec

        LB_SelectLanguage.Visible = boxSelectLanguage.Visible
        LB_English.Visible = boxSelectLanguage.Visible
        LB_Espanol.Visible = boxSelectLanguage.Visible
        LB_Portugues.Visible = boxSelectLanguage.Visible
        LB_Francais.Visible = boxSelectLanguage.Visible
        LB_Deutsch.Visible = boxSelectLanguage.Visible
        LB_Italiano.Visible = boxSelectLanguage.Visible

    End Sub

    Private Sub LB_Animation_Click(sender As Object, e As EventArgs) Handles LB_Animation.Click
        GBwelcome.Visible = True
    End Sub
End Class
