Imports System.Drawing
Imports System.Windows.Forms

Namespace WinFormsApp1
    Partial Public Class Scriptsp0
        Inherits Form

        ''' <summary>
        ''' Required designer variable.
        ''' </summary>
        Private components As System.ComponentModel.IContainer = Nothing

        ''' <summary>
        ''' Clean up any resources being used.
        ''' </summary>
        ''' <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        Protected Overrides Sub Dispose(disposing As Boolean)
            If disposing AndAlso (components IsNot Nothing) Then
                components.Dispose()
            End If
            MyBase.Dispose(disposing)
        End Sub

#Region "Windows Form Designer generated code"

        ''' <summary>
        ''' Required method for Designer support - do not modify
        ''' the contents of this method with the code editor.
        ''' </summary>
        Private Sub InitializeComponent()
            SuspendLayout()
            ' 
            ' Scriptsp0
            ' 
            AutoScaleDimensions = New SizeF(7.0F, 15.0F)
            AutoScaleMode = AutoScaleMode.Font
            ClientSize = New Size(800, 450)
            Name = "Scriptsp0"
            Text = "Scriptsp0"
            AddHandler Load, AddressOf Scriptsp0_Load
            ResumeLayout(False)
        End Sub

#End Region
    End Class
End Namespace