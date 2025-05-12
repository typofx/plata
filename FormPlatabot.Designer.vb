Namespace WinFormsApp1
    Partial Public Class FormPlatabot
        Inherits Form

        ' Controles do formulário
        Private components As System.ComponentModel.IContainer
        Private WithEvents textBox As TextBox

        <System.Diagnostics.DebuggerNonUserCode()>
        Private Sub InitializeComponent()
            components = New ComponentModel.Container()
            textBox = New TextBox()
            Timer1 = New Timer(components)
            Timer2 = New Timer(components)
            LBLblockA = New Label()
            LBLblockB = New Label()
            LBLblockC = New Label()
            LBLblockD = New Label()
            LBLlimit = New Label()
            LBLrepete = New Label()
            LBLblockPosition = New Label()
            LBLvalueTimelapse = New Label()
            BTNmodify = New Button()
            LBLtimelapse = New Label()
            TXTtimeLapse = New TextBox()
            TXTlimit = New TextBox()
            CKBinfinite = New CheckBox()
            BTNstartStop = New Button()
            BTNChange = New Button()
            Label1 = New Label()
            TXTdelayBTcycles = New TextBox()
            LBLdelayBTcycles = New Label()
            GroupBox2 = New GroupBox()
            lblBinSelecionado = New Label()
            lblCsvSelecionado = New Label()
            GroupBox3 = New GroupBox()
            GroupBox4 = New GroupBox()
            btnSelectBinFile = New Button()
            btnSelectCsvFile = New Button()
            btnSalvarLog = New Button()
            StatusBar = New StatusStrip()
            Strip01 = New ToolStripStatusLabel()
            lblBotId = New Label()
            GroupBox2.SuspendLayout()
            GroupBox3.SuspendLayout()
            GroupBox4.SuspendLayout()
            StatusBar.SuspendLayout()
            SuspendLayout()
            ' 
            ' textBox
            ' 
            textBox.BackColor = SystemColors.MenuText
            textBox.BorderStyle = BorderStyle.FixedSingle
            textBox.Font = New Font("Courier New", 8.25F, FontStyle.Regular, GraphicsUnit.Point, CByte(0))
            textBox.ForeColor = Color.GreenYellow
            textBox.Location = New Point(12, 184)
            textBox.Multiline = True
            textBox.Name = "textBox"
            textBox.ScrollBars = ScrollBars.Vertical
            textBox.Size = New Size(1149, 282)
            textBox.TabIndex = 1
            ' 
            ' Timer1
            ' 
            Timer1.Interval = 1000
            ' 
            ' Timer2
            ' 
            Timer2.Interval = 10000
            ' 
            ' LBLblockA
            ' 
            LBLblockA.BackColor = Color.LightGray
            LBLblockA.BorderStyle = BorderStyle.FixedSingle
            LBLblockA.Font = New Font("Arial", 9.75F, FontStyle.Bold)
            LBLblockA.ForeColor = Color.Black
            LBLblockA.Location = New Point(12, 25)
            LBLblockA.MinimumSize = New Size(120, 25)
            LBLblockA.Name = "LBLblockA"
            LBLblockA.Size = New Size(156, 25)
            LBLblockA.TabIndex = 7
            LBLblockA.Text = "Boot"
            LBLblockA.TextAlign = ContentAlignment.MiddleCenter
            ' 
            ' LBLblockB
            ' 
            LBLblockB.BackColor = Color.LightGray
            LBLblockB.BorderStyle = BorderStyle.FixedSingle
            LBLblockB.Font = New Font("Arial", 9.75F, FontStyle.Bold)
            LBLblockB.Location = New Point(176, 25)
            LBLblockB.MinimumSize = New Size(120, 25)
            LBLblockB.Name = "LBLblockB"
            LBLblockB.Size = New Size(156, 25)
            LBLblockB.TabIndex = 8
            LBLblockB.Text = "Balances"
            LBLblockB.TextAlign = ContentAlignment.MiddleCenter
            ' 
            ' LBLblockC
            ' 
            LBLblockC.BackColor = Color.LightGray
            LBLblockC.BorderStyle = BorderStyle.FixedSingle
            LBLblockC.Font = New Font("Arial", 9.75F, FontStyle.Bold)
            LBLblockC.Location = New Point(340, 25)
            LBLblockC.MinimumSize = New Size(120, 25)
            LBLblockC.Name = "LBLblockC"
            LBLblockC.Size = New Size(156, 25)
            LBLblockC.TabIndex = 9
            LBLblockC.Text = "Approve"
            LBLblockC.TextAlign = ContentAlignment.MiddleCenter
            ' 
            ' LBLblockD
            ' 
            LBLblockD.BackColor = Color.LightGray
            LBLblockD.BorderStyle = BorderStyle.FixedSingle
            LBLblockD.Font = New Font("Arial", 9.75F, FontStyle.Bold)
            LBLblockD.Location = New Point(504, 25)
            LBLblockD.MinimumSize = New Size(120, 25)
            LBLblockD.Name = "LBLblockD"
            LBLblockD.Size = New Size(156, 25)
            LBLblockD.TabIndex = 14
            LBLblockD.Text = "Execute"
            LBLblockD.TextAlign = ContentAlignment.MiddleCenter
            ' 
            ' LBLlimit
            ' 
            LBLlimit.AutoSize = True
            LBLlimit.Location = New Point(6, 118)
            LBLlimit.Name = "LBLlimit"
            LBLlimit.Size = New Size(50, 15)
            LBLlimit.TabIndex = 21
            LBLlimit.Text = "LBLlimit"
            ' 
            ' LBLrepete
            ' 
            LBLrepete.AutoSize = True
            LBLrepete.Location = New Point(6, 100)
            LBLrepete.Name = "LBLrepete"
            LBLrepete.Size = New Size(59, 15)
            LBLrepete.TabIndex = 20
            LBLrepete.Text = "LBLrepete"
            ' 
            ' LBLblockPosition
            ' 
            LBLblockPosition.AutoSize = True
            LBLblockPosition.Location = New Point(6, 85)
            LBLblockPosition.Name = "LBLblockPosition"
            LBLblockPosition.Size = New Size(98, 15)
            LBLblockPosition.TabIndex = 19
            LBLblockPosition.Text = "LBLblockPosition"
            ' 
            ' LBLvalueTimelapse
            ' 
            LBLvalueTimelapse.AutoSize = True
            LBLvalueTimelapse.Location = New Point(6, 70)
            LBLvalueTimelapse.Name = "LBLvalueTimelapse"
            LBLvalueTimelapse.Size = New Size(106, 15)
            LBLvalueTimelapse.TabIndex = 18
            LBLvalueTimelapse.Text = "LBLvalueTimelapse"
            ' 
            ' BTNmodify
            ' 
            BTNmodify.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            BTNmodify.FlatStyle = FlatStyle.Flat
            BTNmodify.ForeColor = Color.White
            BTNmodify.Location = New Point(112, 38)
            BTNmodify.Name = "BTNmodify"
            BTNmodify.Size = New Size(90, 23)
            BTNmodify.TabIndex = 17
            BTNmodify.Text = "&Modify"
            BTNmodify.UseVisualStyleBackColor = False
            ' 
            ' LBLtimelapse
            ' 
            LBLtimelapse.AutoSize = True
            LBLtimelapse.Location = New Point(6, 20)
            LBLtimelapse.Name = "LBLtimelapse"
            LBLtimelapse.Size = New Size(60, 15)
            LBLtimelapse.TabIndex = 16
            LBLtimelapse.Text = "Timelapse"
            ' 
            ' TXTtimeLapse
            ' 
            TXTtimeLapse.Location = New Point(6, 38)
            TXTtimeLapse.Name = "TXTtimeLapse"
            TXTtimeLapse.ReadOnly = True
            TXTtimeLapse.Size = New Size(100, 23)
            TXTtimeLapse.TabIndex = 15
            ' 
            ' TXTlimit
            ' 
            TXTlimit.Location = New Point(6, 40)
            TXTlimit.Name = "TXTlimit"
            TXTlimit.Size = New Size(100, 23)
            TXTlimit.TabIndex = 24
            ' 
            ' CKBinfinite
            ' 
            CKBinfinite.AutoSize = True
            CKBinfinite.Checked = True
            CKBinfinite.CheckState = CheckState.Checked
            CKBinfinite.Location = New Point(112, 42)
            CKBinfinite.Name = "CKBinfinite"
            CKBinfinite.Size = New Size(63, 19)
            CKBinfinite.TabIndex = 23
            CKBinfinite.Text = "Infinite"
            CKBinfinite.UseVisualStyleBackColor = True
            ' 
            ' BTNstartStop
            ' 
            BTNstartStop.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            BTNstartStop.FlatStyle = FlatStyle.Flat
            BTNstartStop.ForeColor = Color.White
            BTNstartStop.Location = New Point(6, 130)
            BTNstartStop.Name = "BTNstartStop"
            BTNstartStop.Size = New Size(129, 30)
            BTNstartStop.TabIndex = 22
            BTNstartStop.Text = "&Start"
            BTNstartStop.UseVisualStyleBackColor = False
            ' 
            ' BTNChange
            ' 
            BTNChange.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            BTNChange.FlatStyle = FlatStyle.Flat
            BTNChange.ForeColor = Color.White
            BTNChange.Location = New Point(132, 93)
            BTNChange.Name = "BTNChange"
            BTNChange.Size = New Size(90, 23)
            BTNChange.TabIndex = 27
            BTNChange.Text = "&Change"
            BTNChange.UseVisualStyleBackColor = False
            ' 
            ' Label1
            ' 
            Label1.AutoSize = True
            Label1.Location = New Point(6, 75)
            Label1.Name = "Label1"
            Label1.Size = New Size(84, 15)
            Label1.TabIndex = 26
            Label1.Text = "BT Cycles (sec)"
            ' 
            ' TXTdelayBTcycles
            ' 
            TXTdelayBTcycles.Location = New Point(6, 93)
            TXTdelayBTcycles.Name = "TXTdelayBTcycles"
            TXTdelayBTcycles.ReadOnly = True
            TXTdelayBTcycles.Size = New Size(120, 23)
            TXTdelayBTcycles.TabIndex = 25
            ' 
            ' LBLdelayBTcycles
            ' 
            LBLdelayBTcycles.BackColor = Color.LightGray
            LBLdelayBTcycles.BorderStyle = BorderStyle.FixedSingle
            LBLdelayBTcycles.Font = New Font("Arial", 9.75F, FontStyle.Bold)
            LBLdelayBTcycles.Location = New Point(668, 25)
            LBLdelayBTcycles.MinimumSize = New Size(120, 25)
            LBLdelayBTcycles.Name = "LBLdelayBTcycles"
            LBLdelayBTcycles.Size = New Size(214, 25)
            LBLdelayBTcycles.TabIndex = 34
            LBLdelayBTcycles.Text = "Break (Between Cycles)"
            LBLdelayBTcycles.TextAlign = ContentAlignment.MiddleCenter
            ' 
            ' GroupBox2
            ' 
            GroupBox2.Controls.Add(lblBinSelecionado)
            GroupBox2.Controls.Add(lblCsvSelecionado)
            GroupBox2.Controls.Add(LBLtimelapse)
            GroupBox2.Controls.Add(TXTtimeLapse)
            GroupBox2.Controls.Add(BTNmodify)
            GroupBox2.Controls.Add(LBLvalueTimelapse)
            GroupBox2.Controls.Add(LBLblockPosition)
            GroupBox2.Controls.Add(LBLrepete)
            GroupBox2.Controls.Add(LBLlimit)
            GroupBox2.Location = New Point(12, 5)
            GroupBox2.Name = "GroupBox2"
            GroupBox2.Size = New Size(213, 170)
            GroupBox2.TabIndex = 36
            GroupBox2.TabStop = False
            GroupBox2.Text = "Time Settings"
            ' 
            ' lblBinSelecionado
            ' 
            lblBinSelecionado.AutoSize = True
            lblBinSelecionado.Location = New Point(6, 145)
            lblBinSelecionado.Name = "lblBinSelecionado"
            lblBinSelecionado.Size = New Size(37, 15)
            lblBinSelecionado.TabIndex = 23
            lblBinSelecionado.Text = "Setup"
            ' 
            ' lblCsvSelecionado
            ' 
            lblCsvSelecionado.AutoSize = True
            lblCsvSelecionado.Location = New Point(6, 133)
            lblCsvSelecionado.Name = "lblCsvSelecionado"
            lblCsvSelecionado.Size = New Size(28, 15)
            lblCsvSelecionado.TabIndex = 22
            lblCsvSelecionado.Text = "CSV"
            ' 
            ' GroupBox3
            ' 
            GroupBox3.Controls.Add(LBLblockA)
            GroupBox3.Controls.Add(LBLblockB)
            GroupBox3.Controls.Add(LBLblockC)
            GroupBox3.Controls.Add(LBLblockD)
            GroupBox3.Controls.Add(LBLdelayBTcycles)
            GroupBox3.Location = New Point(12, 479)
            GroupBox3.Name = "GroupBox3"
            GroupBox3.Size = New Size(890, 63)
            GroupBox3.TabIndex = 37
            GroupBox3.TabStop = False
            GroupBox3.Text = "Status Blocks"
            ' 
            ' GroupBox4
            ' 
            GroupBox4.Controls.Add(lblBotId)
            GroupBox4.Controls.Add(btnSelectBinFile)
            GroupBox4.Controls.Add(btnSelectCsvFile)
            GroupBox4.Controls.Add(btnSalvarLog)
            GroupBox4.Controls.Add(TXTlimit)
            GroupBox4.Controls.Add(CKBinfinite)
            GroupBox4.Controls.Add(BTNstartStop)
            GroupBox4.Controls.Add(Label1)
            GroupBox4.Controls.Add(TXTdelayBTcycles)
            GroupBox4.Controls.Add(BTNChange)
            GroupBox4.Location = New Point(479, 5)
            GroupBox4.Name = "GroupBox4"
            GroupBox4.Size = New Size(423, 170)
            GroupBox4.TabIndex = 38
            GroupBox4.TabStop = False
            GroupBox4.Text = "Advanced Settings"
            ' 
            ' btnSelectBinFile
            ' 
            btnSelectBinFile.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            btnSelectBinFile.FlatStyle = FlatStyle.Flat
            btnSelectBinFile.ForeColor = Color.White
            btnSelectBinFile.Location = New Point(276, 130)
            btnSelectBinFile.Name = "btnSelectBinFile"
            btnSelectBinFile.Size = New Size(129, 30)
            btnSelectBinFile.TabIndex = 41
            btnSelectBinFile.Text = "&Select Config Setup"
            btnSelectBinFile.UseVisualStyleBackColor = False
            ' 
            ' btnSelectCsvFile
            ' 
            btnSelectCsvFile.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            btnSelectCsvFile.FlatStyle = FlatStyle.Flat
            btnSelectCsvFile.ForeColor = Color.White
            btnSelectCsvFile.Location = New Point(141, 130)
            btnSelectCsvFile.Name = "btnSelectCsvFile"
            btnSelectCsvFile.Size = New Size(129, 30)
            btnSelectCsvFile.TabIndex = 40
            btnSelectCsvFile.Text = "&Select Wallet Setup"
            btnSelectCsvFile.UseVisualStyleBackColor = False
            ' 
            ' btnSalvarLog
            ' 
            btnSalvarLog.BackColor = Color.FromArgb(CByte(50), CByte(50), CByte(80))
            btnSalvarLog.FlatStyle = FlatStyle.Flat
            btnSalvarLog.ForeColor = Color.White
            btnSalvarLog.Location = New Point(337, 94)
            btnSalvarLog.Name = "btnSalvarLog"
            btnSalvarLog.Size = New Size(68, 30)
            btnSalvarLog.TabIndex = 28
            btnSalvarLog.Text = "Save log"
            btnSalvarLog.UseVisualStyleBackColor = False
            ' 
            ' StatusBar
            ' 
            StatusBar.Items.AddRange(New ToolStripItem() {Strip01})
            StatusBar.Location = New Point(0, 545)
            StatusBar.Name = "StatusBar"
            StatusBar.Size = New Size(1173, 22)
            StatusBar.TabIndex = 39
            StatusBar.Text = "StatusStrip1"
            ' 
            ' Strip01
            ' 
            Strip01.Name = "Strip01"
            Strip01.Size = New Size(39, 17)
            Strip01.Text = "Status"
            ' 
            ' lblBotId
            ' 
            lblBotId.AutoSize = True
            lblBotId.Font = New Font("Segoe UI", 20.25F, FontStyle.Regular, GraphicsUnit.Point, CByte(0))
            lblBotId.Location = New Point(349, 19)
            lblBotId.Name = "lblBotId"
            lblBotId.Size = New Size(56, 37)
            lblBotId.TabIndex = 24
            lblBotId.Text = "ID: "
            ' 
            ' FormPlatabot
            ' 
            ClientSize = New Size(1173, 567)
            Controls.Add(StatusBar)
            Controls.Add(GroupBox2)
            Controls.Add(GroupBox3)
            Controls.Add(GroupBox4)
            Controls.Add(textBox)
            Name = "FormPlatabot"
            Text = "PlataBot"
            GroupBox2.ResumeLayout(False)
            GroupBox2.PerformLayout()
            GroupBox3.ResumeLayout(False)
            GroupBox4.ResumeLayout(False)
            GroupBox4.PerformLayout()
            StatusBar.ResumeLayout(False)
            StatusBar.PerformLayout()
            ResumeLayout(False)
            PerformLayout()
        End Sub

        Protected Overrides Sub Dispose(ByVal disposing As Boolean)
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
            MyBase.Dispose(disposing)
        End Sub
        Friend WithEvents Timer1 As Timer
        Friend WithEvents Timer2 As Timer
        Friend WithEvents LBLblockA As Label
        Friend WithEvents LBLblockB As Label
        Friend WithEvents LBLblockC As Label
        Friend WithEvents LBLblockD As Label
        Friend WithEvents LBLlimit As Label
        Friend WithEvents LBLrepete As Label
        Friend WithEvents LBLblockPosition As Label
        Friend WithEvents LBLvalueTimelapse As Label
        Friend WithEvents BTNmodify As Button
        Friend WithEvents LBLtimelapse As Label
        Friend WithEvents TXTtimeLapse As TextBox
        Friend WithEvents TXTlimit As TextBox
        Friend WithEvents CKBinfinite As CheckBox
        Friend WithEvents BTNstartStop As Button
        Friend WithEvents BTNChange As Button
        Friend WithEvents Label1 As Label
        Friend WithEvents TXTdelayBTcycles As TextBox
        Friend WithEvents LBLdelayBTcycles As Label
        Friend WithEvents GroupBox2 As GroupBox
        Friend WithEvents GroupBox3 As GroupBox
        Friend WithEvents GroupBox4 As GroupBox
        Friend WithEvents btnSalvarLog As Button
        Friend WithEvents StatusBar As StatusStrip
        Friend WithEvents Strip01 As ToolStripStatusLabel
        Friend WithEvents btnSelectCsvFile As Button
        Friend WithEvents lblCsvSelecionado As Label
        Friend WithEvents btnSelectBinFile As Button
        Friend WithEvents lblBinSelecionado As Label
        Friend WithEvents lblBotId As Label
    End Class
End Namespace