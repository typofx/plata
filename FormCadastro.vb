Imports System.IO
Imports System.Text
Imports MySql.Data.MySqlClient
Imports System.Data.SQLite

Namespace WinFormsApp1
    Partial Public Class FormCadastro

        Inherits Form

        Private connection As MySqlConnection
        Private connectionString As String = "Server=localhost;Database=datawallet;Uid=root;Pwd=;"

        Private dataGridView1 As DataGridView
        Private lblCaminhoEnv As Label ' Novo Label para exibir o caminho do .env
        Private loadedCsvIds As New List(Of Integer)
        Public Sub New()
            InitializeComponent()
            VerificarECriarCampoWalletTag()
            InitializeDataGridView()
            LoadData()
            CarregarCaminhoEnv() ' Carrega o caminho do arquivo .env ao iniciar o formulário

        End Sub

        ' Inicializa o DataGridView
        Private Sub InitializeDataGridView()
            dataGridView1 = New DataGridView With {
                .Dock = DockStyle.Bottom,
                .AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
                .SelectionMode = DataGridViewSelectionMode.FullRowSelect,
                .AllowUserToAddRows = False
            }

            ' Adiciona as colunas
            dataGridView1.Columns.Add("ID", "ID")
            dataGridView1.Columns("ID").Visible = True ' Esconde o ID do usuário
            dataGridView1.Columns.Add("WALLET_TAG", "Wallet Tag") ' Nova coluna
            dataGridView1.Columns.Add("WALLET_SECRET", "Wallet Secret")
            dataGridView1.Columns("WALLET_SECRET").Visible = False
            dataGridView1.Columns.Add("WALLET_ADDRESS", "Wallet Address")

            ' Botão Editar
            Dim editButton As DataGridViewButtonColumn = New DataGridViewButtonColumn With {
                .Name = "edit",
                .HeaderText = "Edit",
                .Text = "Edit",
                .UseColumnTextForButtonValue = True
            }
            dataGridView1.Columns.Add(editButton)

            ' Botão Excluir
            Dim deleteButton As DataGridViewButtonColumn = New DataGridViewButtonColumn With {
                .Name = "delete",
                .HeaderText = "Delete",
                .Text = "Delete",
                .UseColumnTextForButtonValue = True
            }
            dataGridView1.Columns.Add(deleteButton)

            'Botão Gerar.env
            Dim generateEnvButton As DataGridViewButtonColumn = New DataGridViewButtonColumn With {
            .Name = "generateEnv",
            .HeaderText = "Generate .env",
            .Text = "Generate",
            .UseColumnTextForButtonValue = True
            }
            dataGridView1.Columns.Add(generateEnvButton)

            'Botão Excluir.env
            Dim deleteEnvButton As DataGridViewButtonColumn = New DataGridViewButtonColumn With {
            .Name = "deleteEnv",
            .HeaderText = "Delete .env",
            .Text = "Delete",
            .UseColumnTextForButtonValue = True
            }
            dataGridView1.Columns.Add(deleteEnvButton)

            ' Botão Aprovar Token
            Dim aproveTokenButton As New DataGridViewButtonColumn With {
                .Name = "aproveToken",
                .HeaderText = "Aprove Token",
                .Text = "Aprove",
                .UseColumnTextForButtonValue = True
            }
            dataGridView1.Columns.Add(aproveTokenButton)

            ' Botão ON/OFF (Toggle) - Agora mostra diretamente ON ou OFF
            Dim toggleButton As New DataGridViewButtonColumn With {
                .Name = "toggleStatus",
                .HeaderText = "Status",
                .UseColumnTextForButtonValue = False
            }
            dataGridView1.Columns.Add(toggleButton)

            ' Configura o evento de clique nas células
            AddHandler dataGridView1.CellClick, AddressOf dataGridView1_CellClick
            AddHandler dataGridView1.CellFormatting, AddressOf dataGridView1_CellFormatting

            ' Adiciona o DataGridView ao formulário
            Controls.Add(dataGridView1)

            ' Adiciona um Label para exibir o caminho do .env
            lblCaminhoEnv = New Label With {
                .AutoSize = True,
                .Location = New Point(10, 180),
                .Text = "Caminho do .env: "
            }
            Controls.Add(lblCaminhoEnv)
        End Sub

        ' Formatação das células - Agora formata o botão de status
        Private Sub dataGridView1_CellFormatting(sender As Object, e As DataGridViewCellFormattingEventArgs)
            If e.ColumnIndex = dataGridView1.Columns("toggleStatus").Index AndAlso e.RowIndex >= 0 Then
                Dim row = dataGridView1.Rows(e.RowIndex)
                Dim status = row.Cells("toggleStatus").Tag?.ToString() ' O status está armazenado na Tag da célula

                If status = "1" Then
                    row.Cells("toggleStatus").Value = "ON"
                    row.Cells("toggleStatus").Style.BackColor = Color.LightGreen
                Else
                    row.Cells("toggleStatus").Value = "OFF"
                    row.Cells("toggleStatus").Style.BackColor = Color.LightCoral
                End If
            End If
        End Sub

        ' Evento de clique no botão Salvar
        Private Sub btnSalvar_Click(sender As Object, e As EventArgs) Handles btnSalvar.Click
            ' 1. Validação básica
            If String.IsNullOrWhiteSpace(txtWalletSecret.Text) OrElse
       String.IsNullOrWhiteSpace(txtWalletAddress.Text) Then
                MessageBox.Show("Preencha todos os campos!", "Aviso",
                       MessageBoxButtons.OK, MessageBoxIcon.Warning)
                Return
            End If

            ' 2. Preparação dos dados
            Dim walletSecret = txtWalletSecret.Text.Trim
            Dim walletAddress = txtWalletAddress.Text.Trim
            Dim walletTag = If(String.IsNullOrWhiteSpace(txtWalletTag.Text), Nothing, txtWalletTag.Text.Trim)

            Try
                ' 3. Conexão e comando SQL
                Using connection As New MySqlConnection(connectionString)
                    connection.Open()

                    ' Verifica se é edição ou novo registro
                    If txtWalletSecret.Tag IsNot Nothing Then ' Modo Edição
                        Dim id = CInt(txtWalletSecret.Tag)
                        Dim sql = "UPDATE wallets SET WALLET_SECRET=@secret, WALLET_ADDRESS=@address, WALLET_TAG=@tag WHERE ID=@id"

                        Using cmd As New MySqlCommand(sql, connection)
                            cmd.Parameters.AddWithValue("@secret", walletSecret)
                            cmd.Parameters.AddWithValue("@address", walletAddress)
                            cmd.Parameters.AddWithValue("@tag", walletTag)
                            cmd.Parameters.AddWithValue("@id", id)

                            Dim rowsAffected = cmd.ExecuteNonQuery
                            If rowsAffected > 0 Then
                                MessageBox.Show("Dados atualizados com sucesso!", "Sucesso")
                            End If
                        End Using
                    Else ' Modo Inserção
                        Dim sql = "INSERT INTO wallets (WALLET_SECRET, WALLET_ADDRESS, WALLET_TAG, STATUS) 
                       VALUES (@secret, @address, @tag, 0)"

                        Using cmd As New MySqlCommand(sql, connection)
                            cmd.Parameters.AddWithValue("@secret", walletSecret)
                            cmd.Parameters.AddWithValue("@address", walletAddress)
                            cmd.Parameters.AddWithValue("@tag", walletTag)

                            Dim rowsAffected = cmd.ExecuteNonQuery
                            If rowsAffected > 0 Then
                                MessageBox.Show("Registro salvo com sucesso!", "Sucesso")
                            End If
                        End Using
                    End If
                End Using

                ' 4. Atualiza a grid e limpa campos
                LoadData()
                txtWalletSecret.Clear()
                txtWalletAddress.Clear()
                txtWalletSecret.Tag = Nothing

            Catch ex As MySqlException
                MessageBox.Show($"Erro MySQL ({ex.Number}): {ex.Message}",
                       "Erro de Banco", MessageBoxButtons.OK, MessageBoxIcon.Error)
            Catch ex As Exception
                MessageBox.Show($"Erro: {ex.Message}",
                       "Erro Geral", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub

        ' Carrega os dados no DataGridView
        Private Sub LoadData()
            dataGridView1.Rows.Clear()
            Try
                Using conn As New MySqlConnection(connectionString)
                    conn.Open()
                    Dim query = "SELECT ID, WALLET_SECRET, WALLET_ADDRESS, WALLET_TAG, STATUS FROM wallets"

                    Using cmd As New MySqlCommand(query, conn)
                        Using reader = cmd.ExecuteReader()
                            While reader.Read()
                                ' Adiciona os valores na ORDEM CORRETA das colunas
                                Dim rowIndex = dataGridView1.Rows.Add(
                            reader("ID"),
                            If(IsDBNull(reader("WALLET_TAG")), "", reader("WALLET_TAG")), ' TAG
                            reader("WALLET_SECRET"), ' SECRET
                            reader("WALLET_ADDRESS"), ' ADDRESS
                            reader("STATUS") ' STATUS
                        )

                                ' Configura o toggle (ON/OFF)
                                dataGridView1.Rows(rowIndex).Cells("toggleStatus").Tag = reader("STATUS")
                            End While
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                MessageBox.Show("Erro ao carregar dados: " & ex.Message)
            End Try
        End Sub

        ' Evento de clique nas células do DataGridView
        Private Sub dataGridView1_CellClick(sender As Object, e As DataGridViewCellEventArgs)
            If e.RowIndex < 0 Then Return

            Dim id = Convert.ToInt32(dataGridView1.Rows(e.RowIndex).Cells("ID").Value)
            Dim walletSecret As String = dataGridView1.Rows(e.RowIndex).Cells("WALLET_SECRET").Value.ToString()
            Dim walletAddress As String = dataGridView1.Rows(e.RowIndex).Cells("WALLET_ADDRESS").Value.ToString()
            Dim walletTag As String = If(dataGridView1.Rows(e.RowIndex).Cells("WALLET_TAG").Value IsNot Nothing,
                            dataGridView1.Rows(e.RowIndex).Cells("WALLET_TAG").Value.ToString(),
                            String.Empty)


            If e.ColumnIndex = dataGridView1.Columns("edit").Index Then
                ' Preenche os campos para edição
                txtWalletSecret.Text = walletSecret
                txtWalletAddress.Text = walletAddress
                txtWalletTag.Text = walletTag
                txtWalletSecret.Tag = id
            ElseIf e.ColumnIndex = dataGridView1.Columns("delete").Index Then
                ' Pergunta se o usuário tem certeza antes de deletar
                Dim resposta As DialogResult = MessageBox.Show(
        "Tem certeza que deseja excluir esta carteira?",
        "Confirmar Exclusão",
        MessageBoxButtons.YesNo,
        MessageBoxIcon.Warning
    )

                ' Se o usuário clicar em "Sim", deleta o registro
                If resposta = DialogResult.Yes Then
                    DeleteRecord(id)
                    LoadData()
                End If
            ElseIf e.ColumnIndex = dataGridView1.Columns("generateEnv").Index Then
                ' Gera o arquivo .env
                GenerateEnvFile(walletSecret, walletAddress)
            ElseIf e.ColumnIndex = dataGridView1.Columns("deleteEnv").Index Then
                'Exclui o arquivo .env
                DeleteEnvFile(walletAddress)
            ElseIf e.ColumnIndex = dataGridView1.Columns("aproveToken").Index Then
                ' Pega os valores da linha clicada
                Dim walletAddressPost As String = dataGridView1.Rows(e.RowIndex).Cells("WALLET_ADDRESS").Value.ToString()
                Dim walletSecretPost As String = dataGridView1.Rows(e.RowIndex).Cells("WALLET_SECRET").Value.ToString()


                ' Abre o FormAproveToken e passa os valores
                Dim formAprove As New FormAproveToken(walletAddressPost, walletSecretPost)
                formAprove.ShowDialog()

            ElseIf e.ColumnIndex = dataGridView1.Columns("toggleStatus").Index Then
                ' Obtém o status atual da Tag da célula
                Dim currentStatus As Integer = Convert.ToInt32(dataGridView1.Rows(e.RowIndex).Cells("toggleStatus").Tag)
                ' Alterna o status entre ON e OFF
                ToggleWalletStatus(id, currentStatus)
                ' LoadData()
            End If
        End Sub

        ' Alterna o status da wallet entre ON e OFF
        Private Sub ToggleWalletStatus(id As Integer, currentStatus As Integer)
            Try
                Using connection = New MySqlConnection(connectionString)
                    connection.Open()
                    Dim newStatus = If(currentStatus = 1, 0, 1)
                    Dim updateSql = "UPDATE wallets SET STATUS = @status WHERE ID = @id"
                    Using command = New MySqlCommand(updateSql, connection)
                        command.Parameters.AddWithValue("@status", newStatus)
                        command.Parameters.AddWithValue("@id", id)
                        command.ExecuteNonQuery()

                        ' Atualiza apenas a linha modificada na DataGridView
                        For Each row As DataGridViewRow In dataGridView1.Rows
                            If Convert.ToInt32(row.Cells("ID").Value) = id Then
                                row.Cells("toggleStatus").Tag = newStatus.ToString()
                                dataGridView1.InvalidateRow(row.Index) ' Força a atualização da exibição
                                Exit For
                            End If
                        Next
                    End Using
                End Using
            Catch ex As Exception
                MessageBox.Show("Erro ao alterar status: " & ex.Message)
            End Try
        End Sub



        ' Exclui um registro do banco de dados
        Private Sub DeleteRecord(id As Integer)
            Try
                Using connection = New MySqlConnection(connectionString)
                    connection.Open()
                    Dim deleteSql = "DELETE FROM wallets WHERE ID = @id"
                    Using command = New MySqlCommand(deleteSql, connection)
                        command.Parameters.AddWithValue("@id", id)
                        command.ExecuteNonQuery()
                    End Using
                End Using
                MessageBox.Show("Registro deletado com sucesso.")
            Catch ex As Exception
                MessageBox.Show("Erro ao deletar o registro: " & ex.Message)
            End Try
        End Sub

        ' Gera o arquivo .env
        Private Sub GenerateEnvFile(walletSecret As String, walletAddress As String)
            Try
                ' Carrega o caminho do arquivo .env com base na chave "WorkingDirectory"
                Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                Dim envFilePath As String = Path.Combine(workingDirectory, ".env")

                If String.IsNullOrEmpty(workingDirectory) Then
                    MessageBox.Show("Caminho do arquivo .env não encontrado.", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    Return
                End If

                ' Gera o conteúdo do arquivo .env
                Dim envContent = $"POLYGON_CHAIN = ""https://polygon-rpc.com""
" & $"WALLET_ADDRESS = ""{walletAddress}""
" & $"WALLET_SECRET = ""{walletSecret}"""

                ' Salva o arquivo .env no caminho carregado
                File.WriteAllText(envFilePath, envContent)
                MessageBox.Show($"Arquivo .env gerado com sucesso em:
{envFilePath}")
            Catch ex As Exception
                MessageBox.Show("Erro ao gerar o arquivo .env: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub

        ' Exclui o arquivo .env
        Private Sub DeleteEnvFile(walletAddress As String)
            Try
                ' Carrega o caminho do arquivo .env com base na chave "WorkingDirectory"
                Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                Dim envFilePath As String = Path.Combine(workingDirectory, ".env")

                If File.Exists(envFilePath) Then
                    File.Delete(envFilePath)
                    MessageBox.Show($"Arquivo .env deletado com sucesso:
{envFilePath}")
                Else
                    MessageBox.Show("Arquivo .env não encontrado.")
                End If
            Catch ex As Exception
                MessageBox.Show("Erro ao deletar o arquivo .env: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub

        ' Obtém o valor de uma chave do banco de dados SQLite
        Private Function ObterCaminho(chave As String) As String
            Dim valor As String = String.Empty

            ' String de conexão com o SQLite
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

        ' Carrega o caminho do arquivo .env ao iniciar o formulário
        Private Sub CarregarCaminhoEnv()
            Try
                ' Obtém o caminho do arquivo .env com base na chave "WorkingDirectory"
                Dim workingDirectory As String = ObterCaminho("WorkingDirectory")
                Dim envFilePath As String = Path.Combine(workingDirectory, ".env")

                If Not String.IsNullOrEmpty(workingDirectory) Then
                    ' Exibe o caminho do .env no Label
                    lblCaminhoEnv.Text = $"Caminho do .env: {envFilePath}"
                Else
                    lblCaminhoEnv.Text = "Caminho do .env não encontrado."
                End If
            Catch ex As Exception
                lblCaminhoEnv.Text = "Erro ao carregar o caminho do .env."
                MessageBox.Show("Erro ao carregar o caminho do arquivo .env: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub


        ' Método para salvar todas as carteiras em um arquivo CSV
        Private Sub SaveAllWalletsToCsv()
            Try
                ' Abre uma caixa de diálogo para selecionar onde salvar o arquivo
                Dim saveFileDialog As New SaveFileDialog()
                saveFileDialog.Filter = "CSV files (*.csv)|*.csv"
                saveFileDialog.Title = "Save Wallet Setup"
                saveFileDialog.FileName = "wallets_backup_" & DateTime.Now.ToString("yyyyMMdd_HHmmss") & ".csv"

                If saveFileDialog.ShowDialog() = DialogResult.OK Then
                    ' Cria um StringBuilder para construir o conteúdo do CSV
                    Dim csvContent As New StringBuilder()

                    ' Adiciona o cabeçalho do CSV
                    csvContent.AppendLine("ID;")

                    ' Obtém todos os registros do banco de dados
                    Using connection As New MySqlConnection(connectionString)
                        connection.Open()
                        Dim selectSql = "SELECT ID, STATUS FROM wallets WHERE STATUS = 1"
                        Using command As New MySqlCommand(selectSql, connection)
                            Using reader = command.ExecuteReader()
                                While reader.Read()
                                    ' Adiciona cada linha ao CSV, separando os campos por ;
                                    csvContent.AppendLine($"{reader("ID")};")
                                End While
                            End Using
                        End Using
                    End Using

                    ' Escreve o conteúdo no arquivo
                    File.WriteAllText(saveFileDialog.FileName, csvContent.ToString())

                    MessageBox.Show($"Todas as carteiras foram salvas com sucesso em:{Environment.NewLine}{saveFileDialog.FileName}",
                                  "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
                End If
            Catch ex As Exception
                MessageBox.Show($"Erro ao salvar as carteiras: {ex.Message}",
                                "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub



        Private Sub LoadCsvToDataGridView()
            Try
                Dim openFileDialog As New OpenFileDialog() With {
            .Filter = "CSV files (*.csv)|*.csv",
            .Title = "Abrir Configuração Salva"
        }

                If openFileDialog.ShowDialog() = DialogResult.OK Then
                    dataGridView1.Rows.Clear()

                    Dim lines As String() = File.ReadAllLines(openFileDialog.FileName)

                    If lines.Length > 0 AndAlso lines(0).StartsWith("ID;") Then
                        loadedCsvIds.Clear()

                        ' Coleta os IDs válidos do CSV
                        For i As Integer = 1 To lines.Length - 1
                            Dim line As String = lines(i).Trim()
                            If Not String.IsNullOrEmpty(line) Then
                                Dim parts As String() = line.Split(";"c)
                                If parts.Length > 0 AndAlso Integer.TryParse(parts(0), Nothing) Then
                                    loadedCsvIds.Add(Integer.Parse(parts(0)))
                                End If
                            End If
                        Next

                        If loadedCsvIds.Count > 0 Then
                            LoadWalletsByIds(loadedCsvIds)
                            MessageBox.Show($"Configuração carregada com sucesso de:{Environment.NewLine}{openFileDialog.FileName}",
                                    "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Else
                            MessageBox.Show("Nenhum ID válido encontrado no CSV.", "Aviso", MessageBoxButtons.OK, MessageBoxIcon.Warning)
                        End If
                    Else
                        MessageBox.Show("Formato de arquivo CSV inválido.", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    End If
                End If
            Catch ex As Exception
                MessageBox.Show($"Erro ao carregar o arquivo CSV: {ex.Message}",
                        "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub

        Private Sub LoadWalletsByIds(ids As List(Of Integer))
            Try
                dataGridView1.Rows.Clear() ' Limpa a DataGridView

                Using connection As New MySqlConnection(connectionString)
                    connection.Open()

                    ' 1. CARREGA PRIMEIRO AS CARTEIRAS DO CSV (NO TOPO)
                    If ids.Count > 0 Then
                        Dim idList = String.Join(",", ids)
                        Dim sqlCarteirasCSV = $"SELECT ID, WALLET_SECRET, WALLET_ADDRESS, WALLET_TAG, STATUS FROM wallets WHERE ID IN ({idList}) ORDER BY FIELD(ID, {idList})"

                        Using command As New MySqlCommand(sqlCarteirasCSV, connection)
                            Using reader = command.ExecuteReader()
                                While reader.Read()
                                    Dim rowIndex = dataGridView1.Rows.Add(
                                reader("ID"),
                                If(IsDBNull(reader("WALLET_TAG")), "", reader("WALLET_TAG")),
                                reader("WALLET_SECRET"),
                                reader("WALLET_ADDRESS")
                            )
                                    dataGridView1.Rows(rowIndex).Cells("toggleStatus").Tag = reader("STATUS").ToString()

                                    dataGridView1.Rows(rowIndex).DefaultCellStyle.BackColor = Color.FromArgb(0, 120, 215)
                                    dataGridView1.Rows(rowIndex).DefaultCellStyle.ForeColor = Color.White
                                    dataGridView1.Rows(rowIndex).DefaultCellStyle.SelectionBackColor = Color.FromArgb(0, 120, 215)
                                    dataGridView1.Rows(rowIndex).DefaultCellStyle.SelectionForeColor = Color.White


                                End While
                            End Using
                        End Using
                    End If

                    ' 2. CARREGA AS OUTRAS CARTEIRAS (QUE NÃO ESTÃO NO CSV)
                    Dim sqlOutrasCarteiras = "SELECT ID, WALLET_SECRET, WALLET_ADDRESS, WALLET_TAG, STATUS FROM wallets"

                    ' Se há IDs no CSV, filtra para trazer apenas as que NÃO estão nele
                    If ids.Count > 0 Then
                        sqlOutrasCarteiras &= $" WHERE ID NOT IN ({String.Join(",", ids)})"
                    End If

                    Using command As New MySqlCommand(sqlOutrasCarteiras, connection)
                        Using reader = command.ExecuteReader()
                            While reader.Read()
                                Dim rowIndex = dataGridView1.Rows.Add(
                            reader("ID"),
                            If(IsDBNull(reader("WALLET_TAG")), "", reader("WALLET_TAG")),
                            reader("WALLET_SECRET"),
                            reader("WALLET_ADDRESS")
                        )
                                dataGridView1.Rows(rowIndex).Cells("toggleStatus").Tag = reader("STATUS").ToString()
                            End While
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                MessageBox.Show("Erro ao carregar carteiras: " & ex.Message, "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End Sub

        Private Sub VerificarECriarCampoWalletTag()
            Try
                Using connection As New MySqlConnection(connectionString)
                    connection.Open()

                    ' Verifica se a coluna existe
                    Dim checkColumnSql = "SELECT COUNT(*) FROM information_schema.columns 
                                 WHERE table_name = 'wallets' AND column_name = 'WALLET_TAG'"

                    Using cmd As New MySqlCommand(checkColumnSql, connection)
                        Dim columnExists = Convert.ToInt32(cmd.ExecuteScalar()) > 0

                        If Not columnExists Then
                            ' Adiciona a coluna se não existir
                            Dim addColumnSql = "ALTER TABLE wallets ADD COLUMN WALLET_TAG VARCHAR(255) NULL"
                            Using addCmd As New MySqlCommand(addColumnSql, connection)
                                addCmd.ExecuteNonQuery()
                            End Using
                        End If
                    End Using
                End Using
            Catch ex As Exception
                MessageBox.Show($"Erro ao verificar/criar campo WALLET_TAG: {ex.Message}")
            End Try
        End Sub


        Private Sub btnLoadCsv_Click(sender As Object, e As EventArgs) Handles btnLoadCsv.Click
            LoadCsvToDataGridView()
        End Sub


        Private Sub btnSaveAllWallets_Click(sender As Object, e As EventArgs) Handles btnSaveAllWallets.Click
            SaveAllWalletsToCsv()
        End Sub

    End Class
End Namespace