Public Class FRMbackground

    Dim g, mg As Graphics
    Dim bmp As Bitmap
    Dim countSec As Integer
    Dim pic As Bitmap
    Function PrintValueWithdrew()
        g = CreateGraphics()
        bmp = New Bitmap(Size.Width, Size.Height, g)
        mg = Graphics.FromImage(bmp)
        mg.CopyFromScreen(Location.X, Location.Y, 0, 0, Size)
        PrintDocument1.Print()
        Return 0
    End Function
    Function CheckVisibleItems()


        LB_SelectLanguage.Visible = boxSelectLanguage.Visible
        LB_English.Visible = boxSelectLanguage.Visible
        LB_Espanol.Visible = boxSelectLanguage.Visible
        LB_Portugues.Visible = boxSelectLanguage.Visible
        LB_Francais.Visible = boxSelectLanguage.Visible
        LB_Deutsch.Visible = boxSelectLanguage.Visible
        LB_Italiano.Visible = boxSelectLanguage.Visible

        LBselectService.Visible = boxSelectService.Visible
        LBcash.Visible = boxSelectService.Visible
        LBcashReceipt.Visible = boxSelectService.Visible
        LBcashBalance.Visible = boxSelectService.Visible
        LBstatement.Visible = boxSelectService.Visible
        LBlodgment.Visible = boxSelectService.Visible
        LBotherServices.Visible = boxSelectService.Visible

        LBselectAmount.Visible = boxSelectAmount.Visible
        LB5eur.Visible = boxSelectAmount.Visible
        LB10eur.Visible = boxSelectAmount.Visible
        LB20eur.Visible = boxSelectAmount.Visible
        LB50eur.Visible = boxSelectAmount.Visible
        LB100eur.Visible = boxSelectAmount.Visible
        txtTypeService.Visible = boxSelectAmount.Visible


        LBtransactionProgress.Visible = boxTransactionProgress.Visible

        LBtakeMoney.Visible = boxTakeMoney.Visible

        LB_Welcome.Visible = boxWelcome.Visible

        LBcompleted.Visible = boxCompleted.Visible

        Return 0

    End Function

    Private Sub FRMbackground_Load(sender As Object, e As EventArgs) Handles MyBase.Load



        'Full Screen Mode -> Animation

        Dim SreenWidth, ScreenHeight As Integer

        SreenWidth = Screen.PrimaryScreen.Bounds.Width
        ScreenHeight = Screen.PrimaryScreen.Bounds.Height

        LB_Animation.Location = New Point((SreenWidth - LB_Animation.Width) / 2, (ScreenHeight - LB_Animation.Height) / 2)
        boxScreenSaver.Size = New Point(SreenWidth, ScreenHeight)
        boxScreenSaver.Location = New Point((SreenWidth - boxScreenSaver.Width) / 2, (ScreenHeight - boxScreenSaver.Height) / 2)

        'Full Screen Mode -> Welcome 

        LB_Welcome.Font = New Font("Montserrat", 50, FontStyle.Bold)

        LB_Welcome.Location = New Point((SreenWidth - LB_Welcome.Width) / 2, (ScreenHeight - LB_Welcome.Height) / 2)
        boxWelcome.Size = New Point(SreenWidth, ScreenHeight)
        boxWelcome.Location = New Point((SreenWidth - boxScreenSaver.Width) / 2, (ScreenHeight - boxScreenSaver.Height) / 2)



        '**********

        TimerForAll.Enabled = True
        TimerForAll.Start()
        countSec = 0

    End Sub
    Private Sub TimerForAll_Tick(sender As Object, e As EventArgs) Handles TimerForAll.Tick

        If (LB_Num.Text <> "0") Or boxScreenSaver.Visible = False Then
            LB_Num.Text = "0"
            LB_Animation.Visible = False
        Else
            LB_Num.Text = "1"
            LB_Animation.Visible = True

        End If

        If boxWelcome.Visible = True And countSec = 2 Then
            boxWelcome.Visible = False
            boxSelectLanguage.Visible = True
            countSec = 0
        End If

        If boxTransactionProgress.Visible = True And countSec = 3 Then
            boxTransactionProgress.Visible = False
            boxTakeMoney.Visible = True
            countSec = 0
        End If

        If boxTakeMoney.Visible = True And countSec = 3 Then
            boxTakeMoney.Visible = False
            boxCompleted.Visible = True
            countSec = 0
        End If

        If boxCompleted.Visible = True And countSec = 3 Then
            boxCompleted.Visible = False
            boxScreenSaver.Visible = True
            countSec = 0
        End If

        countSec = countSec + 1

        LBcount.Text = countSec

        CheckVisibleItems()

    End Sub
    Private Sub LB_English_Click(sender As Object, e As EventArgs) Handles LB_English.Click
        boxSelectLanguage.Visible = False
        boxSelectService.Visible = True
        CheckVisibleItems()

    End Sub
    Function MoneyCashOut()

        'PrintValueWithdrew()
        countSec = 0

        boxSelectAmount.Visible = False
        CheckVisibleItems()

        FRMqrCodeSend.Show()

        Return 0

    End Function
    Private Sub LB_5eur_Click(sender As Object, e As EventArgs) Handles LB5eur.Click

        txtTypeService.Text = "5 EUR"

        MoneyCashOut()

    End Sub

    Private Sub LBcashReceipt_Click(sender As Object, e As EventArgs) Handles LBcashReceipt.Click

        boxSelectService.Visible = False
        boxSelectAmount.Visible = True
        CheckVisibleItems()
        countSec = 0

    End Sub

    Private Sub btnExit_Click(sender As Object, e As EventArgs) Handles btnExit.Click
        Application.Exit()
    End Sub

    Private Sub LB_Animation_Click(sender As Object, e As EventArgs) Handles LB_Animation.Click
        boxScreenSaver.Visible = False
        LB_Animation.Visible = False
        boxWelcome.Visible = True
        CheckVisibleItems()
        countSec = 0
    End Sub

    Private Sub LB10eur_Click(sender As Object, e As EventArgs) Handles LB10eur.Click

        txtTypeService.Text = "10 EUR"

        MoneyCashOut()

    End Sub

    Private Sub LB20eur_Click(sender As Object, e As EventArgs) Handles LB20eur.Click

        txtTypeService.Text = "20 EUR"

        MoneyCashOut()

    End Sub

    Private Sub LB50eur_Click(sender As Object, e As EventArgs) Handles LB50eur.Click

        txtTypeService.Text = "50 EUR"

        MoneyCashOut()

    End Sub

    Private Sub LB100eur_Click(sender As Object, e As EventArgs) Handles LB100eur.Click

        txtTypeService.Text = "100 EUR"

        MoneyCashOut()

    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        FRMqrCodeSend.Show()
    End Sub

    Private Sub PrintDocument1_PrintPage(sender As Object, e As Printing.PrintPageEventArgs) Handles PrintDocument1.PrintPage
        e.Graphics.DrawString(txtTypeService.Text, New Font("Segoe Ui", 16, FontStyle.Bold), Brushes.Black, New PointF(100, 100))
    End Sub

    Private Sub FRMbackground_Activated(sender As Object, e As EventArgs) Handles Me.Activated
        countSec = 0
    End Sub
End Class
