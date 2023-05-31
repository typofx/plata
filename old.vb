Public Class FRMqrCodeSend

    Dim QR_Generator As New MessagingToolkit.QRCode.Codec.QRCodeEncoder
    Dim timeUP As Integer

    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        CloseQRtimer.Enabled = True
        CloseQRtimer.Start()
        FRMbackground.Enabled = False
        FRMbackground.LBcount.Text = 0

        timeUP = 0
        LBtime.Text = timeUP
        PictureBox1.Image = QR_Generator.Encode("ethereum:0xa8881192A70FF985100426D26d6510340b99eDeE?gas=42000&value=10&amount=0.0001")
    End Sub
    Private Sub TextBox1_TextChanged(sender As Object, e As EventArgs) Handles TextBox1.TextChanged
        Try

        Catch ex As Exception
            MsgBox(ex.Message)
        End Try
    End Sub
    Private Sub CloseQRtimer_Tick(sender As Object, e As EventArgs) Handles CloseQRtimer.Tick
        Dim WhenTheTimeIsUp As Integer

        WhenTheTimeIsUp = 5


        If timeUP = 0 Or timeUP < WhenTheTimeIsUp Then
            timeUP = timeUP + 1
        Else
            timeUP = 0
            Close()
            FRMbackground.boxTransactionProgress.Visible = True
        End If

        LBtime.Text = timeUP
    End Sub

    Private Sub Form1_FormClosed(sender As Object, e As FormClosedEventArgs) Handles Me.FormClosed
        FRMbackground.Enabled = True
    End Sub
End Class
