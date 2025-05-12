Imports System.Net.Http
Imports System.Windows.Forms

Namespace WinFormsApp1
    Partial Public Class Scriptse0
        Inherits Form

        Private webBrowsers As WebBrowser()
        Private urls As String() = {"https://typofx.ie/plataforma/panel/token-historical-data-EURUSD/index.php?passwrd=platabotacess20112024@"}
        Private updateTimer As Timer
        Private consoleBox As TextBox
        Private currentIndex As Integer = 0 ' Controla qual página está sendo carregada
        Private loadingTimer As Timer ' Timer para timeout de carregamento
        Private nextUpdateTime As DateTime ' Armazena o tempo da próxima atualização
        Private countdownTimer As Timer ' Timer para atualizar a contagem regressiva

        Private Shared ReadOnly httpClient As HttpClient = New HttpClient(New HttpClientHandler With {
            .AllowAutoRedirect = False
        })

        Private isClosing As Boolean = False

        Public Sub New()
            InitializeComponent()
            InitializeConsole()
            InitializeWebBrowsers()
            InitializeTimer()
            InitializeLoadingTimer()
            InitializeCountdownTimer()

            AddHandler Me.FormClosing, AddressOf Scriptse0_FormClosing
            AddHandler Shown, AddressOf Scriptse0_Shown
        End Sub

        Private Sub Scriptse0_Load(sender As Object, e As EventArgs) Handles MyBase.Load
            LogToConsole("Application started.")
            StartSequentialLoading() ' Inicia o carregamento sequencial
            EnviarSinalOnlineAsync()
        End Sub

        Private Async Sub EnviarSinalOnlineAsync()
            Try
                ' Parâmetros que você deseja enviar
                Dim status As String = "online"
                Dim ScriptNumber As String = MachineLocationData.Instance.ScriptNumber
                Dim app As String = "Database Scripts E" & ScriptNumber

                Dim countryCode As String = MachineLocationData.Instance.CountryCode

                ' Constrói a URL com os parâmetros
                Dim machineName As String = MachineLocationData.Instance.MachineName
                Dim url As String = $"https://typofx.ie/status/status.php?status={status}&app={app}&countryCode={countryCode}&machineName={machineName}"

                ' Log para verificar a URL
                LogToConsole("Enviando GET para: " & url)

                ' Envia uma requisição GET com os parâmetros
                Dim resposta As HttpResponseMessage = Await httpClient.GetAsync(url)

                ' Verifica se a requisição foi bem-sucedida
                If resposta.IsSuccessStatusCode Then
                    Dim respostaTexto As String = Await resposta.Content.ReadAsStringAsync()
                    LogToConsole("Resposta do servidor: " & respostaTexto)
                Else
                    LogToConsole("Erro ao enviar sinal: " & resposta.StatusCode.ToString())
                End If
            Catch ex As Exception
                LogToConsole("Erro ao enviar sinal: " & ex.Message)
            End Try
        End Sub

        Private Async Sub Scriptse0_FormClosing(sender As Object, e As FormClosingEventArgs)
            ' Se o formulário já estiver sendo fechado, ignora o evento
            If isClosing Then
                Return
            End If

            ' Cancela o fechamento do formulário
            e.Cancel = True

            ' Marca que o formulário está sendo fechado
            isClosing = True

            Try
                ' Parâmetros que você deseja enviar
                Dim status As String = "offline"
                Dim ScriptNumber As String = MachineLocationData.Instance.ScriptNumber
                Dim app As String = "Database Scripts E" & ScriptNumber
                Dim countryCode As String = MachineLocationData.Instance.CountryCode
                Dim machineName As String = MachineLocationData.Instance.MachineName
                ' Constrói a URL com os parâmetros
                Dim url As String = $"https://typofx.ie/status/status.php?status={status}&app={app}&countryCode={countryCode}&machineName={machineName}"

                ' Log para verificar a URL
                LogToConsole("Enviando GET para: " & url)

                ' Cria uma requisição GET
                Dim requisicao As New HttpRequestMessage(HttpMethod.Get, url)

                ' Envia a requisição de forma síncrona (para garantir que seja concluída antes do fechamento)
                Dim resposta As HttpResponseMessage = Await httpClient.SendAsync(requisicao)

                ' Verifica se a requisição foi bem-sucedida
                If resposta.IsSuccessStatusCode Then
                    Dim respostaTexto As String = Await resposta.Content.ReadAsStringAsync()
                    LogToConsole("Resposta do servidor: " & respostaTexto)
                Else
                    LogToConsole("Erro ao enviar sinal: " & resposta.StatusCode.ToString())
                End If
            Catch ex As Exception
                LogToConsole("Erro ao enviar sinal de encerramento: " & ex.Message)
            Finally
                ' Fecha o formulário manualmente após a requisição ser concluída
                Me.Close()
            End Try
        End Sub

        Private Sub Scriptse0_Shown(sender As Object, e As EventArgs)
            LogToConsole("Form displayed.")
        End Sub

        Private Sub InitializeConsole()
            ' Caixa de texto simulando um console
            consoleBox = New TextBox With {
                .Multiline = True,
                .[ReadOnly] = True,
                .ScrollBars = ScrollBars.Vertical,
                .Dock = DockStyle.Fill,
                .Font = New Drawing.Font("Consolas", 10),
                .BackColor = Drawing.Color.Black,
                .ForeColor = Drawing.Color.Lime
            }
            Controls.Add(consoleBox)
        End Sub

        Private Sub InitializeWebBrowsers()
            webBrowsers = New WebBrowser(urls.Length - 1) {}
            For i = 0 To urls.Length - 1
                Dim browser = New WebBrowser With {
                    .ScriptErrorsSuppressed = True,
                    .Visible = False ' Torna os navegadores invisíveis
                }

                Dim index = i ' Para capturar o índice corretamente no evento
                AddHandler browser.DocumentCompleted, Sub(s, e)
                                                          LogToConsole($"Page loaded ({index + 1}/{urls.Length}): {urls(index)}")
                                                          loadingTimer.Stop() ' Para o timer de timeout
                                                          currentIndex += 1 ' Avança para a próxima página
                                                          If currentIndex < urls.Length Then
                                                              LogToConsole($"Loading page {currentIndex + 1}/{urls.Length}: {urls(currentIndex)}")
                                                              webBrowsers(currentIndex).Navigate(urls(currentIndex)) ' Carrega a próxima página
                                                              loadingTimer.Start() ' Reinicia o timer de timeout
                                                          End If
                                                      End Sub

                webBrowsers(i) = browser
            Next
        End Sub

        Private Sub StartSequentialLoading()
            If urls.Length > 0 Then
                currentIndex = 0 ' Reinicia o índice
                LogToConsole($"Loading page 1/{urls.Length}: {urls(0)}")
                webBrowsers(0).Navigate(urls(0)) ' Inicia o carregamento da primeira página
                loadingTimer.Start() ' Inicia o timer de timeout
            End If
        End Sub

        Private Sub InitializeTimer()
            updateTimer = New Timer()
            updateTimer.Interval = 1800000 ' 30 minutos em milissegundos
            AddHandler updateTimer.Tick, AddressOf UpdateTimer_Tick
            updateTimer.Start()
            nextUpdateTime = DateTime.Now.AddMinutes(30) ' Define o tempo da próxima atualização
            ShowCountdown() ' Exibe a contagem regressiva inicial
        End Sub

        Private Sub InitializeLoadingTimer()
            loadingTimer = New Timer()
            loadingTimer.Interval = 60000 ' 30 segundos em milissegundos
            AddHandler loadingTimer.Tick, AddressOf LoadingTimer_Tick
        End Sub

        Private Sub InitializeCountdownTimer()
            countdownTimer = New Timer()
            countdownTimer.Interval = 300000 ' 5 minutos em milissegundos
            AddHandler countdownTimer.Tick, AddressOf CountdownTimer_Tick
            countdownTimer.Start()
        End Sub

        Private Sub LoadingTimer_Tick(sender As Object, e As EventArgs)
            loadingTimer.Stop() ' Para o timer de timeout
            LogToConsole($"Timeout loading page {currentIndex + 1}/{urls.Length}: {urls(currentIndex)}")
            currentIndex += 1 ' Avança para a próxima página
            If currentIndex < urls.Length Then
                LogToConsole($"Loading page {currentIndex + 1}/{urls.Length}: {urls(currentIndex)}")
                webBrowsers(currentIndex).Navigate(urls(currentIndex)) ' Carrega a próxima página
                loadingTimer.Start() ' Reinicia o timer de timeout
            End If
        End Sub

        Private Sub UpdateTimer_Tick(sender As Object, e As EventArgs)
            LogToConsole("Refreshing all pages...")
            StartSequentialLoading() ' Reinicia o carregamento sequencial
            nextUpdateTime = DateTime.Now.AddMinutes(30) ' Define o tempo da próxima atualização
            ShowCountdown() ' Exibe a contagem regressiva após a atualização
        End Sub

        Private Sub CountdownTimer_Tick(sender As Object, e As EventArgs)
            ShowCountdown() ' Atualiza a contagem regressiva a cada 5 minutos
        End Sub

        Private Sub ShowCountdown()
            Dim timeRemaining = nextUpdateTime - DateTime.Now
            LogToConsole($"Next update in: {timeRemaining.Minutes} minutes and {timeRemaining.Seconds} seconds")
        End Sub

        Private Sub LogToConsole(message As String)
            Dim timestamp = Date.Now.ToString("HH:mm:ss")
            consoleBox.AppendText($"[{timestamp}] {message}{Environment.NewLine}")
            consoleBox.SelectionStart = consoleBox.Text.Length
            consoleBox.ScrollToCaret()
        End Sub

        Protected Overrides Sub OnFormClosing(e As FormClosingEventArgs)
            updateTimer.Stop()
            updateTimer.Dispose()
            loadingTimer.Stop()
            loadingTimer.Dispose()
            countdownTimer.Stop()
            countdownTimer.Dispose()
            LogToConsole("Application closing.")
            MyBase.OnFormClosing(e)
        End Sub
    End Class
End Namespace