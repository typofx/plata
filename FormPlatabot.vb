Imports System.IO
Imports System.Net.Http
Imports MySql.Data.MySqlClient
Imports System.Data.SQLite
Imports System.Threading
Imports System.Text
Imports System.Text.RegularExpressions

Namespace WinFormsApp1

    Public Module BotIdManager
        Public ReadOnly MAX_BOTS As Integer = CInt(MachineLocationData.Instance.BotEnable)

        Private _nextId As Integer = 0
        Private _availableIds As New Stack(Of Integer)

        Public Function GetNextId() As Integer
            If _availableIds.Count > 0 Then
                Return _availableIds.Pop()
            Else
                Dim id = _nextId
                _nextId += 1
                Return id
            End If
        End Function

        Public Sub ReleaseId(id As Integer)
            _availableIds.Push(id)
            _availableIds = New Stack(Of Integer)(_availableIds.OrderByDescending(Function(i) i))
        End Sub

        Public Sub Reset()
            _nextId = 0
            _availableIds.Clear()
        End Sub

        Public Function GetAvailableIds() As IEnumerable(Of Integer)
            Return _availableIds
        End Function

        Public Function GetActiveBotsInfo() As List(Of (Id As Integer, IsRunning As Boolean))
            Dim botsInfo As New List(Of (Integer, Boolean))
            Dim botsAbertos = Application.OpenForms.OfType(Of FormPlatabot)().ToList()

            For Each bot In botsAbertos
                botsInfo.Add((bot.BotID, bot.Timer1.Enabled))
            Next

            Return botsInfo
        End Function

        Public Function GetTotalBots() As Integer
            Return Application.OpenForms.OfType(Of FormPlatabot)().Count()
        End Function

        Public Function CanCreateNewBot() As Boolean
            Return Application.OpenForms.OfType(Of FormPlatabot)().Count() < MAX_BOTS
        End Function

    End Module
    Partial Public Class FormPlatabot

        Public Property CleanAllMechanism As Object

        Public Shared BotIDCounter As Integer = 0
        Public BotID As Integer

        Private VARvalueTimelapse As Integer = 0
        Private VARblockPosition As Integer = 0
        Private VARrepete As Integer = 0
        Private VARlimit As Integer = 0
        Private VARinterval As Integer = 0

        Private tokenSelecionado As String = ""
        Private ehPlata As Boolean = False
        Private maintainRatio As Integer = 0
        Private nodePath As String = ""
        Private workingDirectory As String = ""
        Private scriptApprove As String = ""
        Private scriptPathSellPLT As String = ""
        Private scriptPathBuyPLT As String = ""
        Private scriptPathExacOutput As String = ""
        Private scriptCheckBalanceOf As String = ""
        Private isProcessing As Boolean = False
        Private random As Random = New Random()
        Private tokenAddresses As (String, String)
        Private _caminhoCsv As String = ""
        Private _csvSelecionado As Boolean = False
        Private _binSelecionado As Boolean = False

        Private Shared ReadOnly httpClient As HttpClient = New HttpClient(New HttpClientHandler With {
            .AllowAutoRedirect = False
        })
        Private isClosing As Boolean = False
        Private cancellationTokenSource As New CancellationTokenSource()
        Private isRunning As Boolean = False


        Public Sub New()
            ' Verifica se pode criar nova instância
            If Not BotIdManager.CanCreateNewBot() Then
                MessageBox.Show($"Limite máximo de bots atingido!", "Limite de Bots",
                   MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Me.Close()
                Return
            End If
            InitializeComponent()

            Dim outrasJanelas = Application.OpenForms.OfType(Of FormPlatabot)().Where(Function(f) f IsNot Me).ToList()


            If outrasJanelas.Count = 0 Then
                BotIdManager.Reset()
            End If


            Me.BotID = BotIdManager.GetNextId()
            UpdateBotIdLabel()


            AddHandler Me.FormClosing, AddressOf Form1_FormClosing
            AddHandler Me.Load, AddressOf FormPlatabot_Load
        End Sub

        Private Sub InitializeBotWorkingDirectory()
            ' Obtém o diretório base dos bots
            Dim baseWorkingDir As String = ObterCaminho("WorkingDirectory")

            ' Cria o diretório específico para este bot se não existir
            workingDirectory = Path.Combine(baseWorkingDir, $"bot{BotID}")

            Try
                If Not Directory.Exists(workingDirectory) Then
                    Directory.CreateDirectory(workingDirectory)
                    ' SafeUpdateText($"Diretório do bot criado: {workingDirectory}")
                End If

                ' Configura os caminhos dos scripts específicos para este bot
                scriptApprove = Path.Combine(workingDirectory, "approveToken.js")
                scriptPathSellPLT = Path.Combine(workingDirectory, "sellPLT.js")
                scriptPathBuyPLT = Path.Combine(workingDirectory, "buyPLT.js")
                scriptPathExacOutput = Path.Combine(workingDirectory, "exacOutput.js")
                scriptCheckBalanceOf = Path.Combine(workingDirectory, "checkBalanceOf.js")

                ' Copia os arquivos necessários para o diretório do bot se não existirem
                CopyRequiredFilesToBotDirectory(baseWorkingDir, workingDirectory)

            Catch ex As Exception
                SafeUpdateText($"ERRO ao configurar diretório do bot: {ex.Message}")
                Throw
            End Try
        End Sub

        Private Sub CopyRequiredFilesToBotDirectory(sourceDir As String, targetDir As String)
            Try
                ' SafeUpdateText($"Copiando arquivos de {sourceDir} para {targetDir}")

                Dim requiredFiles As String() = {
            "approveToken.js", "sellPLT.js", "buyPLT.js",
            "exacOutput.js", "checkBalanceOf.js"
        }

                For Each fileName In requiredFiles
                    Dim sourcePath = Path.Combine(sourceDir, fileName)
                    Dim targetPath = Path.Combine(targetDir, fileName)

                    ' SafeUpdateText($"Verificando arquivo: {sourcePath}")

                    If File.Exists(sourcePath) Then
                        Dim fileContent As String = File.ReadAllText(sourcePath)

                        ' Substitui TODAS as variações de require('./helpers') para require('../helpers')
                        fileContent = Regex.Replace(
                    fileContent,
                    "require\s*\(\s*['""]\./helpers['""]\s*\)",
                    "require('../helpers')",
                    RegexOptions.IgnoreCase
                )

                        ' Substitui TODAS as variações de require('./abi.json') para require('../abi.json')
                        fileContent = Regex.Replace(
                    fileContent,
                    "require\s*\(\s*['""]\./abi\.json['""]\s*\)",
                    "require('../abi.json')",
                    RegexOptions.IgnoreCase
                )

                        ' Grava o arquivo modificado no destino (sobrescreve se já existir)
                        File.WriteAllText(targetPath, fileContent)
                        '  SafeUpdateText($"Arquivo processado: {fileName}")
                    Else
                        SafeUpdateText($"AVISO: Arquivo de origem não encontrado: {fileName}")
                    End If
                Next
            Catch ex As Exception
                SafeUpdateText($"ERRO ao copiar arquivos: {ex.Message}")
                Throw
            End Try
        End Sub

        Private Sub SafeInvoke(action As Action)
            If Me.IsDisposed OrElse Disposing Then Return

            Try
                If Me.InvokeRequired Then
                    Me.Invoke(New Action(Sub()
                                             If Not Me.IsDisposed AndAlso Not Disposing Then
                                                 action()
                                             End If
                                         End Sub))
                Else
                    If Not Me.IsDisposed AndAlso Not Disposing Then
                        action()
                    End If
                End If
            Catch ex As ObjectDisposedException

            End Try
        End Sub

        Private Sub SafeUpdateText(text As String)
            SafeInvoke(Sub()
                           Try
                               If Not textBox.IsDisposed Then
                                   textBox.AppendText($"[{DateTime.Now:HH:mm:ss}] {VARrepete} . {text}{Environment.NewLine}")
                                   textBox.ScrollToCaret()
                               End If
                           Catch ex As ObjectDisposedException

                           End Try
                       End Sub)
        End Sub
        Private Sub SafeUpdateLabel(lbl As Label, text As String)
            If lbl.InvokeRequired Then
                lbl.Invoke(Sub() lbl.Text = text)
            Else
                lbl.Text = text
            End If
        End Sub

        Private Sub UpdateBotIdLabel()
            SafeInvoke(Sub()
                           If lblBotId IsNot Nothing AndAlso Not lblBotId.IsDisposed Then
                               lblBotId.Text = $"ID: {Me.BotID}"
                               ' Opcional: mudar cor baseada no ID
                               lblBotId.ForeColor = GetColorForId(Me.BotID)
                           End If
                       End Sub)
        End Sub

        Private Function GetColorForId(id As Integer) As Color
            Dim colors As Color() = {Color.Red, Color.Blue, Color.Green, Color.Orange, Color.Purple}
            Return colors(id Mod colors.Length)
        End Function

        Private Function ObterCaminho(chave As String) As String
            Dim valor As String = String.Empty
            Dim config As New FormConexao()
            Dim caminhoBanco As String = config.ObterCaminho()
            Dim connectionString As String = $"Data Source={caminhoBanco};Version=3;"

            Using connection As New SQLiteConnection(connectionString)
                connection.Open()
                Dim query As String = "SELECT Valor FROM Configuracoes WHERE Chave = @Chave"
                Using command As New SQLiteCommand(query, connection)
                    command.Parameters.AddWithValue("@Chave", chave)
                    Dim reader As SQLiteDataReader = command.ExecuteReader()

                    If reader.Read() Then
                        valor = reader("Valor").ToString()
                    End If
                End Using
            End Using

            Return valor
        End Function


        Private Sub FormPlatabot_Load(sender As Object, e As EventArgs) Handles MyBase.Load
            OutraInstanciaEstaAberta()

            Strip01.Text = "Loading Setup..."
            textBox.AppendText($"Loading Setup...{Environment.NewLine}")
            InitializeBotWorkingDirectory()

            ' Verifica se o arquivo de configuração do bot existe
            Dim nodePath As String = ObterCaminho("NodePath")
            ' Dim workingDirectory As String = ObterCaminho("WorkingDirectory")



            Dim botConfigFile As String = Path.Combine(workingDirectory, $"~varPLT.js")
            If Not File.Exists(botConfigFile) Then
                SafeUpdateText($"Aguardando arquivo de configuração do bot: ~varPLT.js")
            End If

            ' Tempos configuráveis
            Dim tempos = ExtrairTemposConfiguracao()
            Dim JS_APPROVE_TIME As Integer = tempos.ApproveTime
            Dim JS_CYCLE_TIME As Integer = tempos.CycleTime

            LBLvalueTimelapse.Text = JS_APPROVE_TIME.ToString()
            TXTtimeLapse.Text = JS_APPROVE_TIME.ToString()
            TXTdelayBTcycles.Text = JS_CYCLE_TIME.ToString()
            BTNstartStop.Enabled = False

            TXTlimit.Enabled = False
            LBLlimit.Text = "∞"
            LBLrepete.Text = "0"
            LBLblockPosition.Text = CStr(0)

            If Not Directory.Exists(workingDirectory) Then
                SafeUpdateText($"ERRO: Diretório não encontrado: {workingDirectory}")
                MessageBox.Show("Diretório de trabalho não encontrado!", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return
            End If

            Strip01.Text = "Ready to Go"
            textBox.AppendText($"Ready to Go{Environment.NewLine}")
            lblCsvSelecionado.Text = "Nenhum arquivo selecionado"
        End Sub

        Private Async Function EnviarSinalOnlineAsync() As Task
            Try
                Dim status As String = "online"
                Dim BeOuTee As String = MachineLocationData.Instance.BeOuTee
                Dim app As String = "BeOuTee " & BeOuTee
                Dim countryCode As String = MachineLocationData.Instance.CountryCode
                Dim machineName As String = MachineLocationData.Instance.MachineName
                Dim url As String = $"https://typofx.ie/status/status.php?status={status}&app={app}&countryCode={countryCode}&machineName={machineName}"

                Dim resposta As HttpResponseMessage = Await httpClient.GetAsync(url)

                If resposta.IsSuccessStatusCode Then
                    Dim respostaTexto As String = Await resposta.Content.ReadAsStringAsync()
                    Strip01.Text = "Online"
                Else
                    SafeUpdateText($"Erro status: {resposta.StatusCode}")
                End If
            Catch ex As Exception
                SafeUpdateText($"Falha ao enviar status: {ex.Message}")
            End Try
        End Function

        Private Async Sub Form1_FormClosing(sender As Object, e As FormClosingEventArgs)
            If Not isClosing Then
                ' Libera o ID quando o bot é fechado
                BotIdManager.ReleaseId(Me.BotID)
                SafeUpdateText($"Liberando ID: {Me.BotID}")
            End If

            If isClosing Then Return
            e.Cancel = True
            isClosing = True
            cancellationTokenSource.Cancel()

            Try
                Dim status As String = "offline"
                Dim BeOuTee As String = MachineLocationData.Instance.BeOuTee
                Dim app As String = "BeOuTee " & BeOuTee
                Dim countryCode As String = MachineLocationData.Instance.CountryCode
                Dim machineName As String = MachineLocationData.Instance.MachineName
                Dim url As String = $"https://typofx.ie/status/status.php?status={status}&app={app}&countryCode={countryCode}&machineName={machineName}"

                Dim requisicao As New HttpRequestMessage(HttpMethod.Get, url)
                Dim resposta As HttpResponseMessage = Await httpClient.SendAsync(requisicao)

                If resposta.IsSuccessStatusCode Then
                    Dim respostaTexto As String = Await resposta.Content.ReadAsStringAsync()
                    SafeUpdateText("Status: Offline")
                Else
                    SafeUpdateText($"Erro ao enviar status offline: {resposta.StatusCode}")
                End If
            Catch ex As Exception
                SafeUpdateText($"Erro ao enviar status offline: {ex.Message}")
            Finally

                Me.Close()
            End Try
        End Sub

        Private Async Function ExecutarScript(nodePath As String, scriptPath As String, Optional tokenAddress As String = Nothing) As Task(Of Boolean)
            Return Await Task.Run(Async Function()
                                      Try
                                          '  Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                                          Dim arguments As String = $"""{scriptPath}"""

                                          If Not String.IsNullOrEmpty(tokenAddress) Then
                                              arguments += $" ""{tokenAddress}"""
                                          End If

                                          Dim psi As New ProcessStartInfo With {
                .FileName = nodePath,
                .Arguments = arguments,
                .RedirectStandardOutput = True,
                .RedirectStandardError = True,
                .UseShellExecute = False,
                .CreateNoWindow = True,
                .WorkingDirectory = workingDirectory
            }


                                          Using process As New Process()
                                              process.StartInfo = psi

                                              Dim outputBuilder As New StringBuilder()
                                              Dim errorBuilder As New StringBuilder()

                                              Dim tcs = New TaskCompletionSource(Of Boolean)()

                                              AddHandler process.OutputDataReceived, Sub(sender, e)
                                                                                         If e.Data IsNot Nothing Then
                                                                                             outputBuilder.AppendLine(e.Data)
                                                                                             SafeUpdateText(e.Data)
                                                                                         End If
                                                                                     End Sub

                                              AddHandler process.ErrorDataReceived, Sub(sender, e)
                                                                                        If e.Data IsNot Nothing Then
                                                                                            errorBuilder.AppendLine(e.Data)
                                                                                            SafeUpdateText($"ERRO: {e.Data}")
                                                                                        End If
                                                                                    End Sub

                                              AddHandler process.Exited, Sub(sender, e)
                                                                             tcs.TrySetResult(process.ExitCode = 0)
                                                                         End Sub

                                              process.EnableRaisingEvents = True

                                              If Not process.Start() Then
                                                  SafeUpdateText("Falha ao iniciar o processo Node.js")
                                                  Return False
                                              End If

                                              process.BeginOutputReadLine()
                                              process.BeginErrorReadLine()

                                              Return Await tcs.Task.ConfigureAwait(False)
                                          End Using
                                      Catch ex As Exception
                                          SafeUpdateText($"Falha ao executar script: {ex.Message}")
                                          Return False
                                      End Try
                                  End Function)
        End Function


        Private Sub CriarArquivoEnv(walletData As (String, String))
            Try
                ' Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                Dim envFilePath As String = Path.Combine(workingDirectory, $".env")
                Dim envContent = $"WALLET_ADDRESS={walletData.Item2}{Environment.NewLine}WALLET_SECRET={walletData.Item1}"
                File.WriteAllText(envFilePath, envContent)
                SafeUpdateText($"Arquivo de ambiente criado: .env")
            Catch ex As Exception
                SafeUpdateText($"Erro ao criar .env: {ex.Message}")
            End Try
        End Sub

        Private Sub ExcluirArquivoEnv()
            Try
                '   Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                Dim envFilePath As String = Path.Combine(workingDirectory, $".env") ' Atualizado para botX.env
                If File.Exists(envFilePath) Then
                    File.Delete(envFilePath)
                    SafeUpdateText($"Arquivo .env removido")
                End If
            Catch ex As Exception
                SafeUpdateText($"Erro ao remover .env: {ex.Message}")
            End Try
        End Sub

        Private Function ObterCarteiraAleatoria() As (walletSecret As String, walletAddress As String)?

            Try
                ' Verifica se temos um caminho válido
                If String.IsNullOrEmpty(_caminhoCsv) OrElse Not File.Exists(_caminhoCsv) Then
                    SafeUpdateText("Selecione um arquivo CSV válido primeiro")
                    Return Nothing
                End If

                ' Lê todas as linhas do CSV
                Dim idsCsv = File.ReadAllLines(_caminhoCsv)


                If idsCsv.Length < 2 Then
                    SafeUpdateText("Arquivo CSV vazio ou formato inválido")
                    Return Nothing
                End If

                ' Processa IDs (ignora cabeçalho)
                Dim ids = New List(Of Integer)
                For i As Integer = 1 To idsCsv.Length - 1
                    Dim idStr = idsCsv(i).Trim().TrimEnd(";"c)
                    If Integer.TryParse(idStr, Nothing) Then
                        ids.Add(Integer.Parse(idStr))
                    End If
                Next

                If ids.Count = 0 Then
                    SafeUpdateText("Nenhum ID válido encontrado no CSV")
                    Return Nothing
                End If

                ' Converte para formato SQL
                Dim idsSql = String.Join(",", ids)

                ' Consulta o banco de dados
                Dim connectionString = "Server=localhost;Database=datawallet;Uid=root;Pwd=;Connection Timeout=30;"
                Dim query = $"SELECT WALLET_SECRET, WALLET_ADDRESS FROM wallets WHERE ID IN ({idsSql}) AND STATUS = 1 ORDER BY RAND() LIMIT 1;"

                Using connection As New MySqlConnection(connectionString)
                    connection.Open()
                    Using command As New MySqlCommand(query, connection)
                        Using reader As MySqlDataReader = command.ExecuteReader()
                            If reader.Read() Then
                                Return (
                            reader.GetString("WALLET_SECRET"),
                            reader.GetString("WALLET_ADDRESS")
                        )
                            End If
                        End Using
                    End Using
                End Using

                SafeUpdateText("Nenhuma carteira ativa encontrada para os IDs")
                Return Nothing

            Catch ex As Exception
                SafeUpdateText($"Erro ao obter carteira: {ex.Message}")
                Return Nothing
            End Try
        End Function


        Private Sub BTNmodify_Click(sender As Object, e As EventArgs) Handles BTNmodify.Click
            LBLvalueTimelapse.Text = TXTtimeLapse.Text
            VARvalueTimelapse = TXTtimeLapse.Text
            Timer1.Interval = TXTtimeLapse.Text
        End Sub

        Private Async Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
            ' Se já estiver processando, ignora novos ticks
            If isProcessing Then Return

            Try
                isProcessing = True

                Select Case VARblockPosition
                    Case 0
                        LBLblockA.ForeColor = Color.Blue
                        Await InicializarOperacaoAsync()
                        ' 1. Sorteia se vai ser Token0 ou Token1
                        random = New Random()

                        tokenAddresses = Await Task.Run(Function() LerEnderecosDosTokens()).ConfigureAwait(False)
                        maintainRatio = Await Task.Run(Function() LerMaintainRatio()).ConfigureAwait(False)

                        If random.Next(2) = 0 Then
                            tokenSelecionado = tokenAddresses.Item1
                        Else
                            tokenSelecionado = tokenAddresses.Item2
                        End If
                        Await Task.Run(Sub() AtualizarTokenParaAprovar(tokenSelecionado)).ConfigureAwait(False)

                    Case 1
                        LBLblockB.ForeColor = Color.Blue
                        ' 5. Executa CheckBalanceOf
                        Await ExecutarScript(nodePath, scriptCheckBalanceOf, tokenSelecionado)

                    Case 2
                        LBLblockC.ForeColor = Color.Blue
                        ' 6. Executa Approve
                        Await ExecutarScript(nodePath, scriptApprove, tokenSelecionado)

                    Case 3
                        LBLblockD.ForeColor = Color.Blue

                        ehPlata = (tokenSelecionado.ToLower() = LerToken1())

                        If ehPlata Then

                            If maintainRatio = 1 Then
                                Await ExecutarScript(nodePath, scriptPathExacOutput)
                            Else
                                Await ExecutarScript(nodePath, scriptPathSellPLT)
                            End If
                        Else
                            Await ExecutarScript(nodePath, scriptPathBuyPLT)
                        End If

                    Case 4

                        ' Finaliza o ciclo
                        CleanAllMechanism_Click()
                        LBLdelayBTcycles.ForeColor = Color.Green
                        VARrepete += 1
                        Timer2.Enabled = True
                        Timer1.Enabled = False
                End Select

                ' Atualiza interface e posição somente após conclusão
                ' LBLrepete.Text = CStr(VARrepete)
                SafeUpdateLabel(LBLrepete, VARrepete.ToString())

                VARblockPosition += 1
                'LBLblockPosition.Text = VARblockPosition
                SafeUpdateLabel(LBLblockPosition, VARblockPosition.ToString())


            Catch ex As Exception
                SafeUpdateText($"ERRO no Timer1_Tick: {ex.Message}")
                LBLblockD.ForeColor = Color.Red
                Timer1.Enabled = False
            Finally
                isProcessing = False ' Libera para próxima execução
            End Try
        End Sub


        Private Async Function InicializarOperacaoAsync() As Task
            ' Executa o sinal online em segundo plano
            Dim sinalOnlineTask = EnviarSinalOnlineAsync()

            ' Aguarda o sinal online completar
            Await sinalOnlineTask.ConfigureAwait(False)

            ' Pré-carrega caminhos e diretórios
            nodePath = Await Task.Run(Function() ObterCaminho("NodePath")).ConfigureAwait(False)
            ' workingDirectory = Await Task.Run(Function() ObterCaminho("WorkingDirectory")).ConfigureAwait(False)

            If Not Directory.Exists(workingDirectory) Then
                SafeUpdateText("ERRO: Diretório de trabalho não existe!")
                Timer1.Enabled = False
                Return
            End If

            ' Pré-constroi caminhos dos scripts
            scriptApprove = Path.Combine(workingDirectory, "approveToken.js")
            scriptPathSellPLT = Path.Combine(workingDirectory, "sellPLT.js")
            scriptPathBuyPLT = Path.Combine(workingDirectory, "buyPLT.js")
            scriptPathExacOutput = Path.Combine(workingDirectory, "exacOutput.js")
            scriptCheckBalanceOf = Path.Combine(workingDirectory, "checkBalanceOf.js")

            ' Obtém carteira de forma assíncrona
            Dim walletData = Await Task.Run(Function() ObterCarteiraAleatoria()).ConfigureAwait(False)
            If walletData Is Nothing Then
                SafeUpdateText("ERRO: Nenhuma carteira disponível!")
                Timer1.Enabled = False
                Return
            End If

            ' Executa operações de arquivo
            Await Task.Run(Sub()
                               ExcluirArquivoEnv()
                               CriarArquivoEnv(walletData.Value)
                           End Sub).ConfigureAwait(False)
        End Function

        Private Sub CKBinfinite_CheckedChanged(sender As Object, e As EventArgs) Handles CKBinfinite.CheckedChanged
            If CKBinfinite.Checked = True Then
                TXTlimit.Enabled = False
                LBLlimit.Text = "∞"
                TXTlimit.Text = "∞"
            Else
                TXTlimit.Enabled = True
                LBLlimit.Text = "?"
                TXTlimit.Text = "10"
            End If
        End Sub

        Private Sub BTNstartStop_Click(sender As Object, e As EventArgs) Handles BTNstartStop.Click
            If Timer1.Enabled = False Or BTNstartStop.Text = "Start" Then
                BTNstartStop.Text = "Stop"
                Timer1.Enabled = True
                CKBinfinite.Enabled = False
                btnSelectCsvFile.Enabled = False
                btnSelectBinFile.Enabled = False
            Else
                BTNstartStop.Text = "Start"
                btnSelectCsvFile.Enabled = True
                btnSelectBinFile.Enabled = True
                CKBinfinite.Enabled = True
                Timer1.Enabled = False
                Timer2.Enabled = False
                VARrepete = 0
                VARblockPosition = 0
                LBLrepete.Text = VARrepete
                LBLlimit.Text = CStr(VARlimit)
                LBLdelayBTcycles.ForeColor = Color.Black
            End If

            If CKBinfinite.Checked = False Then
                LBLlimit.Text = TXTlimit.Text
                VARlimit = CInt(TXTlimit.Text)
            Else
                LBLlimit.Text = "∞"
            End If

            'LBLblockA.ForeColor = Color.Black
            'LBLblockB.ForeColor = Color.Black
            'LBLblockC.ForeColor = Color.Black
            'VARblockPosition = -1
            'LBLblockPosition.Text = 0
            CleanAllMechanism_Click()

        End Sub

        Private Sub CleanAllMechanism_Click()
            LBLblockA.ForeColor = Color.Black
            LBLblockB.ForeColor = Color.Black
            LBLblockC.ForeColor = Color.Black
            LBLblockD.ForeColor = Color.Black
            LBLdelayBTcycles.ForeColor = Color.Black
            VARblockPosition = -1
            LBLblockPosition.Text = CStr(0)
        End Sub

        Private Sub Timer2_Tick(sender As Object, e As EventArgs) Handles Timer2.Tick


            If CKBinfinite.Checked = False And VARrepete >= VARlimit Then
                Timer1.Enabled = False
                Timer2.Enabled = False
                BTNstartStop.Text = "Start"
                CKBinfinite.Enabled = True
                VARrepete = 0
                LBLblockD.ForeColor = Color.Black
            Else
                Timer1.Enabled = True
                Timer2.Enabled = False
            End If

            CleanAllMechanism_Click()

        End Sub

        Private Sub TXTchange_Click(sender As Object, e As EventArgs) Handles BTNChange.Click

            VARinterval = CDec(TXTdelayBTcycles.Text) * 1000

            Timer2.Interval = CStr(VARinterval)

        End Sub

        Private Function LerEnderecosDosTokens() As (String, String)
            Dim token0Address As String = ""
            Dim token1Address As String = ""
            ' Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
            Dim filePath As String = Path.Combine(workingDirectory, $"~varPLT.js")

            Try
                Dim fileContent As String = File.ReadAllText(filePath)

                Dim token0Match = System.Text.RegularExpressions.Regex.Match(
                    fileContent,
                    "JS_TOKEN0_ADDRESS\s*=\s*""([^""]+)""")
                If token0Match.Success Then
                    token0Address = token0Match.Groups(1).Value
                End If

                Dim token1Match = System.Text.RegularExpressions.Regex.Match(
                    fileContent,
                    "JS_TOKEN1_ADDRESS\s*=\s*""([^""]+)""")
                If token1Match.Success Then
                    token1Address = token1Match.Groups(1).Value
                End If

                If String.IsNullOrEmpty(token0Address) OrElse String.IsNullOrEmpty(token1Address) Then
                    SafeUpdateText("ERRO: Não foi possível ler os endereços dos tokens do arquivo")
                End If

            Catch ex As Exception
                SafeUpdateText($"ERRO ao ler arquivo de tokens: {ex.Message}")
            End Try

            Return (token0Address, token1Address)
        End Function


        Private Function LerMaintainRatio() As Integer
            Dim maintainRatio As Integer = 999 ' Valor padrão para indicar falha
            ' Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
            Dim filePath As String = Path.Combine(workingDirectory, $"~varPLT.js")

            Try
                Dim fileContent As String = File.ReadAllText(filePath)

                Dim ratioMatch = System.Text.RegularExpressions.Regex.Match(
            fileContent,
            "JS_MAINTAIN_RATIO\s*=\s*(\d+)")

                If ratioMatch.Success Then
                    maintainRatio = CInt(ratioMatch.Groups(1).Value)
                Else
                    SafeUpdateText("ERRO: Não foi possível ler JS_MAINTAIN_RATIO do arquivo")
                End If

            Catch ex As Exception
                SafeUpdateText($"ERRO ao ler arquivo ~varPLT.js: {ex.Message}")
            End Try

            Return maintainRatio
        End Function

        Private Sub AtualizarTokenParaAprovar(tokenAddress As String)
            '   Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
            Dim filePath As String = Path.Combine(workingDirectory, $"~varPLT.js")

            Try
                Dim linhas() As String = File.ReadAllLines(filePath)

                For i As Integer = 0 To linhas.Length - 1
                    If linhas(i).Contains("JS_TOKEN_TOBEAPPROVED_ADDRESS") Then
                        Dim inicio As Integer = linhas(i).IndexOf("""") + 1
                        Dim fim As Integer = linhas(i).LastIndexOf("""")

                        If inicio > 0 AndAlso fim > inicio Then
                            linhas(i) = linhas(i).Substring(0, inicio) &
                               tokenAddress &
                               linhas(i).Substring(fim)
                        Else
                            linhas(i) = $"const JS_TOKEN_TOBEAPPROVED_ADDRESS = ""{tokenAddress}"";"
                        End If
                        Exit For
                    End If
                Next

                File.WriteAllLines(filePath, linhas)
                SafeUpdateText($"{tokenAddress}")

            Catch ex As Exception
                SafeUpdateText($"ERRO ao atualizar token para aprovação: {ex.Message}")
            End Try
        End Sub

        Private Sub SalvarLog()
            Try

                Using saveDialog As New SaveFileDialog()
                    saveDialog.Filter = "Arquivos de Texto (*.txt)|*.txt|Todos os Arquivos (*.*)|*.*"
                    saveDialog.FilterIndex = 1
                    saveDialog.RestoreDirectory = True
                    saveDialog.FileName = $"PlataBot_Log_{DateTime.Now:yyyyMMdd_HHmmss}.txt"


                    If saveDialog.ShowDialog() = DialogResult.OK Then

                        File.WriteAllText(saveDialog.FileName, textBox.Text)


                        SafeUpdateText($"Log salvo com sucesso em: {saveDialog.FileName}")
                    Else
                        SafeUpdateText("Operação de salvar log cancelada pelo usuário")
                    End If
                End Using
            Catch ex As Exception
                SafeUpdateText($"ERRO ao salvar log: {ex.Message}")
            End Try
        End Sub




        Private Function LerToken1() As String
            Dim tokenOne As String = String.Empty
            ' Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
            Dim filePath As String = Path.Combine(workingDirectory, $"~varPLT.js")

            Try
                Dim fileContent As String = File.ReadAllText(filePath)

                ' Expressão regular para encontrar o JS_TOKEN1_ADDRESS
                Dim token1Match = System.Text.RegularExpressions.Regex.Match(
            fileContent,
            "JS_TOKEN1_ADDRESS\s*=\s*""([^""]+)""")

                If token1Match.Success Then
                    tokenOne = token1Match.Groups(1).Value.ToLower()
                    ' SafeUpdateText($"Endereço encontrado: {tokenOne}")
                Else
                    ' SafeUpdateText("ERRO: Não foi possível encontrar o endereço do token no arquivo")
                End If

            Catch ex As Exception
                SafeUpdateText($"ERRO ao ler arquivo ~varPLT.js: {ex.Message}")
            End Try

            Return tokenOne
        End Function

        Public Function OutraInstanciaEstaAberta() As Boolean
            Dim outrasJanelas = Application.OpenForms.OfType(Of FormPlatabot)().Where(Function(f) f IsNot Me).ToList()
            Return outrasJanelas.Count > 0
        End Function


        Private Sub btnSalvarLog_Click(sender As Object, e As EventArgs) Handles btnSalvarLog.Click
            SalvarLog()
        End Sub




        Public Function SelecionarCarteiraCSV() As Boolean
            Dim openFileDialog As New OpenFileDialog With {
        .InitialDirectory = Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments),
        .Filter = "Arquivos CSV (*.csv)|*.csv",
        .Title = "Selecione o arquivo de carteiras",
        .RestoreDirectory = True
    }

            If openFileDialog.ShowDialog() = DialogResult.OK Then
                _caminhoCsv = openFileDialog.FileName
                _csvSelecionado = True

                SafeUpdateLabel(lblCsvSelecionado, Path.GetFileName(_caminhoCsv))
                SafeUpdateText($"Arquivo CSV selecionado: {_caminhoCsv}")

                VerificarArquivosSelecionados()
                Return True
            End If

            Return False
        End Function
        Private Sub btnSelectCsvFile_Click(sender As Object, e As EventArgs) Handles btnSelectCsvFile.Click
            BTNstartStop.Enabled = False

            SelecionarCarteiraCSV()
        End Sub

        Private Sub btnSelectBinFile_Click(sender As Object, e As EventArgs) Handles btnSelectBinFile.Click
            Dim openFileDialog As New OpenFileDialog With {
                .Filter = "Arquivos Binários (*.bin)|*.bin",
                .Title = "Selecione o arquivo de configuração do bot",
                .RestoreDirectory = True
            }
            _binSelecionado = False
            If openFileDialog.ShowDialog() = DialogResult.OK Then
                Try
                    '   Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                    If String.IsNullOrEmpty(workingDirectory) OrElse Not Directory.Exists(workingDirectory) Then
                        SafeUpdateText("Diretório de trabalho não configurado ou inválido")
                        Return
                    End If

                    ' Cria o arquivo de configuração específico do bot
                    Dim novoNome As String = $"~varPLT.js"
                    Dim destinoPath As String = Path.Combine(workingDirectory, novoNome)

                    ' Copia e renomeia o arquivo
                    File.Copy(openFileDialog.FileName, destinoPath, True)

                    ' Atualiza a interface
                    AtualizarLabelsComTempos()
                    SafeUpdateLabel(lblBinSelecionado, Path.GetFileName(openFileDialog.FileName))
                    SafeUpdateText($"Configuração do Bot {BotID} carregada: {Path.GetFileName(openFileDialog.FileName)}")

                    _binSelecionado = True
                    VerificarArquivosSelecionados()

                Catch ex As Exception
                    SafeUpdateText($"ERRO ao carregar configuração: {ex.Message}")
                End Try
            End If
        End Sub

        Private Sub VerificarArquivosSelecionados()
            BTNstartStop.Enabled = _csvSelecionado AndAlso _binSelecionado
        End Sub
        Private Function ExtrairTemposConfiguracao() As (ApproveTime As Integer, CycleTime As Integer)
            Dim approveTime As Integer = 2 ' Valor padrão
            Dim cycleTime As Integer = 10 ' Valor padrão

            Dim configFile As String = Path.Combine(workingDirectory, $"~varPLT.js")

            If Not File.Exists(configFile) Then
                ' SafeUpdateText($"Arquivo de configuração não encontrado: {configFile}")
                Return (approveTime, cycleTime)
            End If

            Try
                Dim fileContent As String = File.ReadAllText(configFile)

                ' Expressão regular para JS_APPROVE_TIME
                Dim approveMatch = System.Text.RegularExpressions.Regex.Match(
                    fileContent,
                    "JS_APPROVE_TIME\s*=\s*(\d+)")
                If approveMatch.Success Then
                    approveTime = CInt(approveMatch.Groups(1).Value)
                End If

                ' Expressão regular para JS_CYCLE_TIME
                Dim cycleMatch = System.Text.RegularExpressions.Regex.Match(
                    fileContent,
                    "JS_CYCLE_TIME\s*=\s*(\d+)")
                If cycleMatch.Success Then
                    cycleTime = CInt(cycleMatch.Groups(1).Value)
                End If

                'SafeUpdateText($"Tempos configurados - Aprovação: {approveTime}s, Ciclo: {cycleTime}s")

            Catch ex As Exception
                SafeUpdateText($"ERRO ao ler tempos de configuração: {ex.Message}")
            End Try

            Return (approveTime, cycleTime)
        End Function
        Private Sub AtualizarLabelsComTempos()
            Dim tempos = ExtrairTemposConfiguracao()

            SafeInvoke(Sub()

                           LBLvalueTimelapse.Text = tempos.ApproveTime.ToString()
                           TXTtimeLapse.Text = tempos.ApproveTime.ToString()


                           TXTdelayBTcycles.Text = tempos.CycleTime.ToString()
                       End Sub)
        End Sub

    End Class
End Namespace