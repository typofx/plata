Imports System.Net.Http
Imports System.Windows.Forms
Imports System.Text.Json
Imports System.Linq

Public Class FormLpContracts
    Inherits Form

    ' Classe para representar os dados do JSON
    Public Class LpContract
        Public Property id As Integer
        Public Property pair As String
        Public Property contract As String
        Public Property exchange As String
        Public Property liquidity As Decimal
        Public Property liquidity_formatted As String
    End Class

    Private Async Sub FormLpContracts_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Try
            Dim url = "https://typofx.ie/plataforma/panel/lp-contracts/lp_contracts.json"
            Dim contracts = Await FetchDataAsync(url)

            ' Configurar DataGridView
            DataGridView1.AutoGenerateColumns = False
            DataGridView1.Columns.Clear()

            ' Adicionar colunas
            DataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                .DataPropertyName = "pair",
                .HeaderText = "Par",
                .Width = 120
            })

            DataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                .DataPropertyName = "contract",
                .HeaderText = "Contrato",
                .Width = 250,
                .DefaultCellStyle = New DataGridViewCellStyle With {.Font = New Font("Consolas", 9)}
            })

            DataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                .DataPropertyName = "exchange",
                .HeaderText = "Exchange",
                .Width = 150
            })

            DataGridView1.Columns.Add(New DataGridViewTextBoxColumn With {
                .DataPropertyName = "liquidity_formatted",
                .HeaderText = "Liquidez (USD)",
                .Width = 120,
                .DefaultCellStyle = New DataGridViewCellStyle With {
                    .Alignment = DataGridViewContentAlignment.MiddleRight,
                    .Format = "C2"
                }
            })

            ' Filtrar os contratos
            Dim validContracts = contracts.Where(Function(c)
                                                     Return c.id > 0 AndAlso
                                                            Not {"CetoEX", "CoinInn", "AAVE"}.Contains(c.exchange)
                                                 End Function).ToList()

            DataGridView1.DataSource = validContracts

            ' Ajustar automaticamente o tamanho das colunas
            DataGridView1.AutoResizeColumns(DataGridViewAutoSizeColumnsMode.DisplayedCells)

        Catch ex As Exception
            MessageBox.Show($"Erro ao carregar dados: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Async Function FetchDataAsync(url As String) As Task(Of List(Of LpContract))
        Using client As New HttpClient()
            Dim response = Await client.GetAsync(url)
            response.EnsureSuccessStatusCode()

            Dim json = Await response.Content.ReadAsStringAsync()
            Dim options = New JsonSerializerOptions With {
                .PropertyNameCaseInsensitive = True
            }

            ' Desserializar para lista de contratos
            Dim contracts = JsonSerializer.Deserialize(Of List(Of LpContract))(json, options)

            ' Formatar liquidez
            For Each contract In contracts
                If contract.liquidity = 0 Then
                    contract.liquidity_formatted = "$0.00"
                ElseIf contract.liquidity < 0.01 Then
                    contract.liquidity_formatted = $"${contract.liquidity:N6}"
                Else
                    contract.liquidity_formatted = $"${contract.liquidity:N2}"
                End If
            Next

            Return contracts
        End Using
    End Function
End Class