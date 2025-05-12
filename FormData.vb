Imports System.Net.Http
Imports System.Windows.Forms

Namespace WinFormsApp1
    Partial Public Class FormData
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
        Private Async Sub FormData_Load(sender As Object, e As EventArgs) Handles MyBase.Load
            Try
                ' URL do JSON
                Dim url = "https://typofx.ie/plataforma/panel/asset/assets_data.json"

                ' Obter os dados do JSON
                Dim assets = Await FetchDataAsync(url)

                ' Configurar as colunas do DataGridView
                dataGridView1.AutoGenerateColumns = False

                ' Adicionar colunas para todas as propriedades
                dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                    .DataPropertyName = "name",
                    .HeaderText = "Nome"
                })

                dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                    .DataPropertyName = "ticker",
                    .HeaderText = "Ticker"
                })

                dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                    .DataPropertyName = "contract",
                    .HeaderText = "Contract"
                })

                dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                    .DataPropertyName = "decimals",
                    .HeaderText = "Decimals"
                })

                dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                    .DataPropertyName = "network",
                    .HeaderText = "Network"
                })

                dataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                    .DataPropertyName = "price_formatted",
                    .HeaderText = "Price"
                })

                ' Exibir os dados no DataGridView
                dataGridView1.DataSource = assets
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
                    If Equals(item.ticker, "PLT") Then
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