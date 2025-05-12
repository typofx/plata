Imports System.IO
Imports System.Text
Imports System.Text.RegularExpressions
Imports System.Diagnostics
Imports System.Data.SQLite
Imports System.Threading

Public Class AproveTokenIndividual
    Private _workingDirectory As String
    Private _jsFilePath As String
    Private _envFilePath As String
    Private _statusControl As TextBox ' Controle para exibir o status

    Public Sub New(Optional statusControl As TextBox = Nothing)
        _workingDirectory = ObterCaminho("WorkingDirectory")
        _jsFilePath = Path.Combine(_workingDirectory, "~varPLT.js")
        _envFilePath = Path.Combine(_workingDirectory, ".env")
        _statusControl = statusControl
    End Sub

    Public Function ObterWorkingDirectory() As String
        Return _workingDirectory
    End Function

    Public Function AtualizarVariaveisEJSRun(tokenName As String, tokenSymbol As String,
                                         tokenDecimals As Integer, tokenAddress As String,
                                         walletAddress As String, walletSecret As String,
                                         poolAddress As String, swapRouterAddress As String) As String
        Try
            ' 1. Atualizar arquivo .env
            UpdateEnvFile(walletAddress, walletSecret)

            ' 2. Atualizar arquivo ~varPLT.js
            UpdateJsFile(tokenName, tokenSymbol, tokenDecimals, tokenAddress, poolAddress, swapRouterAddress)

            ' 3. Executar o script Node.js
            Return ExecuteNodeScript(tokenAddress, walletAddress, walletSecret)

        Catch ex As Exception
            Dim errorMsg = $"Erro no processo completo: {ex.Message}"
            UpdateStatus(errorMsg)
            Throw New Exception(errorMsg)
        End Try
    End Function

    Private Sub UpdateEnvFile(walletAddress As String, walletSecret As String)
        Dim envContent As String = $"POLYGON_CHAIN=""https://polygon-rpc.com""{vbCrLf}" &
                                  $"WALLET_ADDRESS=""{walletAddress}""{vbCrLf}" &
                                  $"WALLET_SECRET=""{walletSecret}""{vbCrLf}"

        SafeWriteFile(_envFilePath, envContent)
        UpdateStatus("Arquivo .env atualizado com sucesso")
    End Sub

    Private Sub UpdateJsFile(tokenName As String, tokenSymbol As String,
                           tokenDecimals As Integer, tokenAddress As String,
                           poolAddress As String, swapRouterAddress As String)
        Dim jsContent As String
        Using fs = New FileStream(_jsFilePath, FileMode.Open, FileAccess.Read, FileShare.ReadWrite)
            Using reader = New StreamReader(fs)
                jsContent = reader.ReadToEnd()
            End Using
        End Using

        ' Atualizar todas as variáveis necessárias
        jsContent = Regex.Replace(jsContent, "let JS_TOKEN0_NAME\s*=\s*"".*""", $"let JS_TOKEN0_NAME = ""{tokenName}""")
        jsContent = Regex.Replace(jsContent, "let JS_TOKEN0_SYMBOL\s*=\s*"".*""", $"let JS_TOKEN0_SYMBOL = ""{tokenSymbol}""")
        jsContent = Regex.Replace(jsContent, "let JS_TOKEN0_DECIMALS\s*=\s*\d+", $"let JS_TOKEN0_DECIMALS = {tokenDecimals}")
        jsContent = Regex.Replace(jsContent, "let JS_TOKEN0_ADDRESS\s*=\s*"".*""", $"let JS_TOKEN0_ADDRESS = ""{tokenAddress}""")
        jsContent = Regex.Replace(jsContent, "let JS_TOKEN_TOBEAPPROVED_ADDRESS\s*=\s*"".*""", $"let JS_TOKEN_TOBEAPPROVED_ADDRESS = ""{tokenAddress}""")
        jsContent = Regex.Replace(jsContent, "let JS_POOL_ADDRESS\s*=\s*"".*""", $"let JS_POOL_ADDRESS = ""{poolAddress}""")
        jsContent = Regex.Replace(jsContent, "let JS_SWAP_ROUTER_ADDRESS\s*=\s*"".*""", $"let JS_SWAP_ROUTER_ADDRESS = ""{swapRouterAddress}""")

        SafeWriteFile(_jsFilePath, jsContent)
        UpdateStatus("Arquivo ~varPLT.js atualizado com sucesso")
    End Sub

    Private Function ExecuteNodeScript(tokenAddress As String, walletAddress As String, walletSecret As String) As String
        Dim nodePath As String = ObterCaminho("NodePath")
        If String.IsNullOrEmpty(nodePath) Then
            Throw New Exception("Caminho do Node.js não configurado")
        End If

        Dim scriptPath = Path.Combine(_workingDirectory, "approveToken.js")
        If Not File.Exists(scriptPath) Then
            Throw New Exception($"Arquivo do script não encontrado: {scriptPath}")
        End If

        Dim psi As New ProcessStartInfo With {
            .FileName = nodePath,
            .Arguments = $"""{scriptPath}"" ""{walletAddress}"" ""{walletSecret}"" ""{tokenAddress}""",
            .RedirectStandardOutput = True,
            .RedirectStandardError = True,
            .UseShellExecute = False,
            .CreateNoWindow = True,
            .WorkingDirectory = _workingDirectory
        }

        Using process As Process = Process.Start(psi)
            Dim output = process.StandardOutput.ReadToEnd()
            Dim [error] = process.StandardError.ReadToEnd()
            process.WaitForExit()

            Dim result = If(process.ExitCode = 0,
                          $"Script executado com sucesso!{vbCrLf}{output}",
                          $"Erro ao executar script:{vbCrLf}{[error]}")

            UpdateStatus(result)
            Return result
        End Using
    End Function

    Private Sub UpdateStatus(message As String)
        If _statusControl IsNot Nothing Then
            If _statusControl.InvokeRequired Then
                _statusControl.Invoke(Sub() _statusControl.Text = message)
            Else
                _statusControl.Text = message
            End If
        End If
    End Sub

    Private Sub SafeWriteFile(filePath As String, content As String)
        Dim maxRetries = 3
        Dim retryCount = 0
        Dim delayMs = 300

        While retryCount < maxRetries
            Try
                Using fs = New FileStream(filePath, FileMode.Create, FileAccess.Write, FileShare.None)
                    Using writer = New StreamWriter(fs, Encoding.UTF8)
                        writer.Write(content)
                        writer.Flush()
                    End Using
                End Using
                Return
            Catch ex As IOException When retryCount < maxRetries - 1
                retryCount += 1
                Thread.Sleep(delayMs)
                delayMs *= 2
            End Try
        End While

        Throw New IOException($"Não foi possível escrever no arquivo após {maxRetries} tentativas")
    End Sub

    Private Shared Function ObterCaminho(chave As String) As String
        Try
            Dim config As New FormConexao()
            Dim caminhoBanco As String = config.ObterCaminho()
            Dim connectionString As String = $"Data Source={caminhoBanco};Version=3;"

            Using connection As New SQLiteConnection(connectionString)
                connection.Open()
                Dim query As String = "SELECT Valor FROM Configuracoes WHERE Chave = @Chave"

                Using command As New SQLiteCommand(query, connection)
                    command.Parameters.AddWithValue("@Chave", chave)
                    Dim resultado As Object = command.ExecuteScalar()

                    Return If(resultado IsNot Nothing, resultado.ToString(), String.Empty)
                End Using
            End Using
        Catch ex As Exception
            Return String.Empty
        End Try
    End Function
End Class