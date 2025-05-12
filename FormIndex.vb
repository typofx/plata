Imports System.Net.Http
Imports System.Threading.Tasks
Imports WinFormsApp1.WinFormsApp1

Public Class FormIndex


    ' HttpClient para fazer requisições HTTP
    Private Shared ReadOnly httpClient As HttpClient = New HttpClient(New HttpClientHandler With {
        .AllowAutoRedirect = False
    })

    ' Flag para controlar se o formulário está sendo fechado
    Private isClosing As Boolean = False

    Public Sub New()
        ' Esta chamada é requerida pelo designer.
        InitializeComponent()

        ' Adiciona os manipuladores de eventos
        AddHandler Me.FormClosing, AddressOf FormIndex_FormClosing
        AddHandler Me.Load, AddressOf FormPlatabot_Load
    End Sub

    Private Sub FormIndex_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        ' Configura o Panel para ficar no fundo da janela
        Panel1.Dock = DockStyle.Bottom

        ' Define uma altura fixa para o Panel
        Panel1.Height = 50 ' Altura de 50 pixels

        AtualizarListaBots()
        ' Atualiza a cada 2 segundos
        TimerAtualizacao.Interval = 2000
        TimerAtualizacao.Start()

    End Sub

    Private Sub AtualizarListaBots()
        ' Limpa a lista atual
        lstBots.Items.Clear()

        ' Obtém todas as instâncias de FormPlatabot e ordena por BotID
        Dim botsAbertos = Application.OpenForms.OfType(Of FormPlatabot)() _
                       .OrderBy(Function(bot) bot.BotID) _
                       .ToList()

        ' Adiciona informações de cada bot em ordem
        For Each bot In botsAbertos
            lstBots.Items.Add($"Bot ID: {bot.BotID} - Status: {If(bot.Timer1.Enabled, "Rodando", "Parado")}")
        Next
        Dim BotEnable As String = MachineLocationData.Instance.BotEnable

        lblTotalBots.Text = $"Total de Bots Abertos: {botsAbertos.Count}"
        lblBotLimit.Text = $"Limite de Bots: {BotEnable}"
    End Sub

    Private Sub TimerAtualizacao_Tick(sender As Object, e As EventArgs) Handles TimerAtualizacao.Tick
        AtualizarListaBots()
    End Sub


    Private Async Sub FormPlatabot_Load(sender As Object, e As EventArgs)
        ' Envia o sinal de "online" quando o formulário é carregado
        EnviarSinalOnlineAsync()
    End Sub

    Private Async Sub EnviarSinalOnlineAsync()



        Try
            ' Parâmetros que você deseja enviar

            Dim location As String = MachineLocationData.Instance.Location
            Dim countryCode As String = MachineLocationData.Instance.CountryCode
            Dim machineName As String = MachineLocationData.Instance.MachineName


            Dim status As String = "online"
            Dim app As String = location

            ' Constrói a URL com os parâmetros

            Dim url As String = $"https://typofx.ie/status/status.php?status={status}&app={app}&countryCode={countryCode}&machineName={machineName}"

            ' Log para verificar a URL
            Console.WriteLine("Enviando GET para: " & url)

            ' Envia uma requisição GET com os parâmetros
            Dim resposta As HttpResponseMessage = Await httpClient.GetAsync(url)

            ' Verifica se a requisição foi bem-sucedida
            If resposta.IsSuccessStatusCode Then
                Dim respostaTexto As String = Await resposta.Content.ReadAsStringAsync()
                Console.WriteLine("Resposta do servidor: " & respostaTexto)
            Else
                Console.WriteLine("Erro ao enviar sinal: " & resposta.StatusCode.ToString())
            End If
        Catch ex As Exception
            Console.WriteLine("Erro ao enviar sinal: " & ex.Message)
        End Try
    End Sub

    Private Async Sub FormIndex_FormClosing(sender As Object, e As FormClosingEventArgs)
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
            Dim location As String = MachineLocationData.Instance.Location
            Dim countryCode As String = MachineLocationData.Instance.CountryCode
            Dim machineName As String = MachineLocationData.Instance.MachineName
            Dim app As String = location
            Dim status As String = "offline"


            ' Constrói a URL com os parâmetros
            Dim url As String = $"https://typofx.ie/status/status.php?status={status}&app={app}&countryCode={countryCode}&machineName={machineName}"


            ' Log para verificar a URL
            Console.WriteLine("Enviando GET para: " & url)

            ' Cria uma requisição GET
            Dim requisicao As New HttpRequestMessage(HttpMethod.Get, url)

            ' Envia a requisição de forma síncrona (para garantir que seja concluída antes do fechamento)
            Dim resposta As HttpResponseMessage = Await httpClient.SendAsync(requisicao)

            ' Verifica se a requisição foi bem-sucedida
            If resposta.IsSuccessStatusCode Then
                Dim respostaTexto As String = Await resposta.Content.ReadAsStringAsync()
                Console.WriteLine("Resposta do servidor: " & respostaTexto)
            Else
                Console.WriteLine("Erro ao enviar sinal: " & resposta.StatusCode.ToString())
            End If
        Catch ex As Exception
            Console.WriteLine("Erro ao enviar sinal de encerramento: " & ex.Message)
        Finally
            ' Fecha o formulário manualmente após a requisição ser concluída
            Me.Close()
        End Try
    End Sub

    Private Sub CadastroToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles CadastroToolStripMenuItem.Click
        Dim formCadastro As New FormCadastro()
        formCadastro.Show()
    End Sub

    Private Sub Form2ToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles Form2ToolStripMenuItem.Click
        Dim form2 As New FormData()
        form2.Show()
    End Sub

    Private Sub Form3ToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles Form3ToolStripMenuItem.Click
        Dim form3 As New FormBotA0()
        form3.Show()
    End Sub

    Private Sub ConfigToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles ConfigToolStripMenuItem.Click
        Dim formConfiguracao As New FormConfiguracao
        formConfiguracao.Show()
    End Sub

    Private Sub PlatabotToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles PlatabotToolStripMenuItem.Click
        Try
            Dim formPlatabot As New FormPlatabot()


            If Not formPlatabot.IsDisposed Then
                formPlatabot.Show()
            Else

            End If
        Catch ex As Exception
            MessageBox.Show($"Erro ao criar bot: {ex.Message}", "Erro",
                      MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub ExitToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles ExitToolStripMenuItem.Click
        Application.Exit()
    End Sub

    Private Sub Panel1_Paint(sender As Object, e As PaintEventArgs) Handles Panel1.Paint
        ' Define uma cor escura para o fundo do Panel
        Dim corEscura As Color = Color.FromArgb(157, 157, 157) ' Cinza escuro

        ' Cria um pincel com a cor escura
        Using brush As New SolidBrush(corEscura)
            ' Preenche o fundo do Panel com a cor escura
            e.Graphics.FillRectangle(brush, Panel1.ClientRectangle)
        End Using
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim form3 As New FormBotA0()
        form3.Show()
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Dim form2 As New FormData()
        form2.Show()
    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs) Handles Button3.Click
        Dim formCadastro As New FormCadastro
        formCadastro.Show()
    End Sub

    Private Sub DatabaseScriptsP0ToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles DatabaseScriptsP0ToolStripMenuItem.Click
        Dim scriptsp0Form As New Scriptsp0()
        scriptsp0Form.Show()
    End Sub

    Private Sub DatabaseScriptsP0ToolStripMenuItem1_Click(sender As Object, e As EventArgs) Handles DatabaseScriptsP0ToolStripMenuItem1.Click
        Dim scriptse0Form As New Scriptse0()
        scriptse0Form.Show()
    End Sub

    Private Sub PriceToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles PriceToolStripMenuItem.Click
        Dim formPrice As New FormPrice()
        formPrice.Show()
    End Sub




    Private Sub AboutToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles AboutToolStripMenuItem.Click
        Dim formAbout As New FormAbout()
        formAbout.Show()
    End Sub

    Private Sub LiquidityPositionsToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles LiquidityPositionsToolStripMenuItem.Click
        Dim formlp As New FormLpContracts()
        FormLpContracts.Show()
    End Sub


End Class