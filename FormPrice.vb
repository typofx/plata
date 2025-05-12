Imports System.Net.Http
Imports System.Windows.Forms
Imports System.Drawing
Imports System.ComponentModel
Imports System.Linq

Namespace WinFormsApp1
    Partial Public Class FormPrice
        Inherits Form

        Public Sub New()
            InitializeComponent()
        End Sub

        ' Classe para representar os dados do JSON
        Public Class Asset
            Public Property id As Integer
            Public Property name As String
            Public Property icon As String
            Public Property ticker As String
            Public Property contract As String
            Public Property decimals As Integer
            Public Property network As String
            Public Property price As Decimal
            Public Property price_formatted As String ' Preço formatado para exibição
        End Class

        ' Evento executado quando o formulário é carregado
        Private Async Sub FormPrice_Load(sender As Object, e As EventArgs) Handles MyBase.Load
            Try
                ' URL do JSON
                Dim url = "https://typofx.ie/plataforma/panel/asset/assets_data.json"

                ' Obter os dados do JSON
                Dim assets = Await FetchDataAsync(url)

                ' Filtrar apenas os ativos com ticker PLT ou MATIC
                Dim pltAsset = assets.FirstOrDefault(Function(a) a.ticker = "PLT")
                Dim maticAsset = assets.FirstOrDefault(Function(a) a.ticker = "MATIC")

                ' Verificar se os ativos foram encontrados
                If pltAsset Is Nothing OrElse maticAsset Is Nothing Then
                    MessageBox.Show("Ativos PLT ou MATIC não encontrados.")
                    Return
                End If

                ' Calcular a taxa de câmbio PLT/MATIC
                Dim pltMaticRate As Decimal = pltAsset.price / maticAsset.price

                ' Criar uma lista para exibir no DataGridView
                Dim gridData As New List(Of Object)

                ' Adicionar PLT e MATIC à lista
                gridData.Add(New With {
                    .Ticker = pltAsset.ticker,
                    .Price = pltAsset.price_formatted
                })

                gridData.Add(New With {
                    .Ticker = maticAsset.ticker,
                    .Price = maticAsset.price_formatted
                })

                ' Adicionar a taxa PLT/MATIC à lista
                gridData.Add(New With {
                    .Ticker = "PLT/MATIC",
                    .Price = pltMaticRate.ToString("N8") ' Formatar com 8 casas decimais
                })

                ' Configurar as colunas do DataGridView (apenas uma vez)
                If dataGridView1.Columns.Count = 0 Then
                    dataGridView1.AutoGenerateColumns = False

                    ' Adicionar colunas
                    dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                        .DataPropertyName = "Ticker",
                        .HeaderText = "Ticker"
                    })

                    dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                        .DataPropertyName = "Price",
                        .HeaderText = "Preço (USD)"
                    })
                End If

                ' Exibir os dados no DataGridView
                dataGridView1.DataSource = gridData
            Catch ex As Exception
                MessageBox.Show("Erro ao carregar dados: " & ex.Message)
            End Try
        End Sub

        ' Método para buscar os dados do JSON
        Private Async Function FetchDataAsync(url As String) As Task(Of List(Of Asset))
            Using client As HttpClient = New HttpClient()
                ' Fazer a requisição HTTP
                Dim response = Await client.GetAsync(url)
                response.EnsureSuccessStatusCode()

                ' Ler o conteúdo como string
                Dim json As String = Await response.Content.ReadAsStringAsync()

                ' Converter o JSON para uma lista de objetos
                Dim assets = System.Text.Json.JsonSerializer.Deserialize(Of List(Of Asset))(json)

                ' Aplicar a lógica de formatação do preço
                For Each item In assets
                    If item.ticker = "PLT" Then
                        ' Formatar preço com 10 casas decimais
                        item.price_formatted = item.price.ToString("F10")
                    Else
                        ' Formatar preço com 4 casas decimais e separadores
                        item.price_formatted = String.Format("{0:N4}", item.price)
                    End If
                Next

                Return assets
            End Using
        End Function
    End Class
End Namespace