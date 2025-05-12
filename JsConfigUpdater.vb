Imports System.IO
Imports System.Text
Imports System.Windows.Forms ' Para usar o SaveFileDialog

Public Class JsConfigUpdater
    Public Shared Sub UpdateFromIni(iniFilePath As String, workingDirectory As String)
        If Not File.Exists(iniFilePath) Then Return

        ' Lê o arquivo .ini e extrai as configurações
        Dim iniContent = File.ReadAllText(iniFilePath)
        Dim settings = ParseIniFile(iniContent)

        ' Cria o caminho para o arquivo centralizado de variáveis
        Dim varFilePath = Path.Combine(workingDirectory, "~varPLT.js")

        ' Obtém todos os valores do .ini (MANTIVE os nomes originais do INI)
        Dim poolAddress = WrapInQuotes(settings.GetValueOrDefault("Allowance_POOL_ADDRESS", ""))
        Dim routerAddress = WrapInQuotes(settings.GetValueOrDefault("Allowance_ROUTER_ADDRESS", ""))
        Dim token0Name = WrapInQuotes(settings.GetValueOrDefault("Token0_TOKEN0_NAME", ""))
        Dim token0Symbol = WrapInQuotes(settings.GetValueOrDefault("Token0_TOKEN0_SYMBOL", ""))
        Dim token0Decimals = settings.GetValueOrDefault("Token0_TOKEN0_DECIMALS", "0")
        Dim token0Address = WrapInQuotes(settings.GetValueOrDefault("Token0_TOKEN0_ADDRESS", ""))
        Dim token1Name = WrapInQuotes(settings.GetValueOrDefault("Token1_TOKEN1_NAME", ""))
        Dim token1Symbol = WrapInQuotes(settings.GetValueOrDefault("Token1_TOKEN1_SYMBOL", ""))
        Dim token1Decimals = settings.GetValueOrDefault("Token1_TOKEN1_DECIMALS", "0")
        Dim token1Address = WrapInQuotes(settings.GetValueOrDefault("Token1_TOKEN1_ADDRESS", ""))
        Dim tokenToBeApproved = WrapInQuotes(settings.GetValueOrDefault("Token0_TOKEN0_ADDRESS", ""))

        Dim sellAmount = settings.GetValueOrDefault("Input_SELL_AMOUNT", "0")
        Dim sellVolatility = settings.GetValueOrDefault("Input_SELL_VOLATILITY", "0")
        Dim buyAmount = settings.GetValueOrDefault("Input_BUY_AMOUNT", "0")
        Dim buyVolatility = settings.GetValueOrDefault("Input_BUY_VOLATILITY", "0")

        Dim maintainRatio = settings.GetValueOrDefault("Ratio_MAINTAIN_RATIO", "0")
        Dim volatilityCheck = settings.GetValueOrDefault("VolatilityCheck_VOLATILITY_CHECK", "false")


        Dim approveTime = settings.GetValueOrDefault("Timestamp_APPROVE", "0")
        Dim cycleTime = settings.GetValueOrDefault("Timestamp_CYCLE", "0")

        Dim rpcUrl = WrapInQuotes(settings.GetValueOrDefault("Network_RPC_URL", ""))

        ' Cria o conteúdo do arquivo de variáveis
        Dim varFileContent = $"' Variáveis globais para os scripts PLT '

let JS_RPC_URL = {rpcUrl}
let JS_POOL_ADDRESS = {poolAddress}
let JS_SWAP_ROUTER_ADDRESS = {routerAddress}

let JS_TRADE_AMOUNT_BUY = {buyAmount}
let JS_TRADE_VOLATILITY_BUY = {buyVolatility}
let JS_TRADE_AMOUNT_SELL = {sellAmount}
let JS_TRADE_VOLATILITY_SELL = {sellVolatility}

let JS_TOKEN0_NAME = {token0Name}
let JS_TOKEN0_SYMBOL = {token0Symbol}
let JS_TOKEN0_DECIMALS = {token0Decimals}
let JS_TOKEN0_ADDRESS = {token0Address}

let JS_TOKEN1_NAME = {token1Name}
let JS_TOKEN1_SYMBOL = {token1Symbol}
let JS_TOKEN1_DECIMALS = {token1Decimals}
let JS_TOKEN1_ADDRESS = {token1Address}

let JS_TOKEN_TOBEAPPROVED_ADDRESS = {tokenToBeApproved}
let JS_MAINTAIN_RATIO = {maintainRatio}
let JS_TRADE_VOLATILITY_ON = {volatilityCheck}

let JS_APPROVE_TIME = {approveTime}
let JS_CYCLE_TIME = {cycleTime}

if (JS_MAINTAIN_RATIO == 1) {{
JS_TRADE_AMOUNT_SELL = JS_TRADE_AMOUNT_BUY
JS_TRADE_VOLATILITY_SELL = JS_TRADE_VOLATILITY_SELL
}}

module.exports = {{ 
JS_POOL_ADDRESS, 
JS_SWAP_ROUTER_ADDRESS,
JS_TRADE_AMOUNT_BUY,
JS_TRADE_VOLATILITY_BUY,
JS_TRADE_AMOUNT_SELL,
JS_TRADE_VOLATILITY_SELL,
JS_TOKEN0_NAME,
JS_TOKEN0_SYMBOL,
JS_TOKEN0_DECIMALS,
JS_TOKEN0_ADDRESS,
JS_TOKEN1_NAME,
JS_TOKEN1_SYMBOL,
JS_TOKEN1_DECIMALS,
JS_TOKEN1_ADDRESS,
JS_TOKEN_TOBEAPPROVED_ADDRESS,
JS_MAINTAIN_RATIO,
JS_TRADE_VOLATILITY_ON,
JS_RPC_URL
}};"

        ' Salva o arquivo de variáveis
        File.WriteAllText(varFilePath, varFileContent, Encoding.UTF8)

        ' Oferece a opção de salvar como .bin
        SaveAsBinFile(varFileContent)
    End Sub

    Public Shared Sub SaveAsBinFile(content As String)
        Dim saveFileDialog As New SaveFileDialog()
        saveFileDialog.Filter = "Binary File (*.bin)|*.bin"
        saveFileDialog.Title = "Salvar arquivo de configuração como BIN"
        saveFileDialog.FileName = "SetupConfig.bin"

        If saveFileDialog.ShowDialog() = DialogResult.OK Then
            Try
                ' Converte o conteúdo para bytes (usando UTF8)
                Dim bytes As Byte() = Encoding.UTF8.GetBytes(content)

                ' Salva o arquivo binário
                File.WriteAllBytes(saveFileDialog.FileName, bytes)
                MessageBox.Show("Arquivo BIN salvo com sucesso!", "Sucesso", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Catch ex As Exception
                MessageBox.Show($"Erro ao salvar arquivo BIN: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
            End Try
        End If
    End Sub

    Private Shared Function WrapInQuotes(value As String) As String
        If String.IsNullOrEmpty(value) Then Return """"""
        If Integer.TryParse(value, Nothing) OrElse Decimal.TryParse(value, Nothing) Then
            Return value
        Else
            Return $"""{value}"""
        End If
    End Function

    Private Shared Function ParseIniFile(iniContent As String) As Dictionary(Of String, String)
        Dim settings = New Dictionary(Of String, String)(StringComparer.OrdinalIgnoreCase)
        Dim currentSection As String = Nothing

        For Each line In iniContent.Split({vbCrLf, vbLf}, StringSplitOptions.RemoveEmptyEntries)
            Dim trimmedLine = line.Trim()

            If trimmedLine.StartsWith(";") OrElse String.IsNullOrWhiteSpace(trimmedLine) Then
                Continue For
            End If

            If trimmedLine.StartsWith("[") AndAlso trimmedLine.EndsWith("]") Then
                currentSection = trimmedLine.Trim("[", "]")
                Continue For
            End If

            Dim equalSignIndex = trimmedLine.IndexOf("="c)
            If equalSignIndex > 0 Then
                Dim key = trimmedLine.Substring(0, equalSignIndex).Trim()
                Dim value = trimmedLine.Substring(equalSignIndex + 1).Trim().Trim(""""c)

                ' Tratamento especial para booleanos
                If key.Equals("MAINTAIN_RATIO", StringComparison.OrdinalIgnoreCase) Then
                    value = If(value = "1", "1", "0")
                ElseIf key.Equals("VOLATILITY_CHECK", StringComparison.OrdinalIgnoreCase) Then
                    ' Garante que será true/false em minúsculas
                    value = value.ToLower()
                    If value <> "true" AndAlso value <> "false" Then
                        value = If(value = "1", "true", "false")
                    End If
                End If

                settings($"{currentSection}_{key}") = value
            End If
        Next

        Return settings
    End Function




    Public Shared Function LoadBinFile() As String
        Dim openFileDialog As New OpenFileDialog()
        openFileDialog.Filter = "Binary File (*.bin)|*.bin"
        openFileDialog.Title = "Abrir arquivo de configuração BIN"

        If openFileDialog.ShowDialog() = DialogResult.OK Then
            Try
                ' Lê o arquivo binário como texto (pois foi salvo como UTF8)
                Dim bytes As Byte() = File.ReadAllBytes(openFileDialog.FileName)
                Return Encoding.UTF8.GetString(bytes)
            Catch ex As Exception
                MessageBox.Show($"Erro ao ler arquivo BIN: {ex.Message}", "Erro", MessageBoxButtons.OK, MessageBoxIcon.Error)
                Return String.Empty
            End Try
        End If

        Return String.Empty
    End Function

    Public Shared Function ConvertBinToIni(binContent As String) As String
        ' Extrai os valores do conteúdo binário (que é o JS)
        Dim settings = New Dictionary(Of String, String)(StringComparer.OrdinalIgnoreCase)

        ' Expressão regular para extrair as variáveis
        Dim pattern = "let (\w+)\s*=\s*(.*?)(?=\nlet|\nmodule|$)"
        Dim matches = System.Text.RegularExpressions.Regex.Matches(binContent, pattern,
                      System.Text.RegularExpressions.RegexOptions.Singleline)

        For Each match As System.Text.RegularExpressions.Match In matches
            If match.Groups.Count >= 3 Then
                Dim key = match.Groups(1).Value
                Dim value = match.Groups(2).Value.Trim()

                ' Remove aspas e ponto-e-vírgula se existirem
                If value.StartsWith("""") AndAlso value.EndsWith("""") Then
                    value = value.Substring(1, value.Length - 2)
                ElseIf value.EndsWith(";") Then
                    value = value.TrimEnd(";"c)
                End If

                settings(key) = value
            End If
        Next


        Dim iniContent As New StringBuilder()

        ' Seção [Input]
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Input]")
        iniContent.AppendLine($"BUY_AMOUNT = {settings.GetValueOrDefault("JS_TRADE_AMOUNT_BUY", "0")}")
        iniContent.AppendLine($"BUY_VOLATILITY = {settings.GetValueOrDefault("JS_TRADE_VOLATILITY_BUY", "0")}")
        iniContent.AppendLine($"SELL_AMOUNT = {settings.GetValueOrDefault("JS_TRADE_AMOUNT_SELL", "0")}")
        iniContent.AppendLine($"SELL_VOLATILITY = {settings.GetValueOrDefault("JS_TRADE_VOLATILITY_SELL", "0")}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [Allowance]
        iniContent.AppendLine("[Allowance]")
        iniContent.AppendLine($"POOL_ADDRESS = ""{settings.GetValueOrDefault("JS_POOL_ADDRESS", "")}""")
        iniContent.AppendLine($"ROUTER_ADDRESS = ""{settings.GetValueOrDefault("JS_SWAP_ROUTER_ADDRESS", "")}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [Token0]
        iniContent.AppendLine("[Token0]")
        iniContent.AppendLine($"TOKEN0_NAME = ""{settings.GetValueOrDefault("JS_TOKEN0_NAME", "")}""")
        iniContent.AppendLine($"TOKEN0_SYMBOL = ""{settings.GetValueOrDefault("JS_TOKEN0_SYMBOL", "")}""")
        iniContent.AppendLine($"TOKEN0_DECIMALS = {settings.GetValueOrDefault("JS_TOKEN0_DECIMALS", "0")}")
        iniContent.AppendLine($"TOKEN0_ADDRESS = ""{settings.GetValueOrDefault("JS_TOKEN0_ADDRESS", "")}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [Token1]
        iniContent.AppendLine("[Token1]")
        iniContent.AppendLine($"TOKEN1_NAME = ""{settings.GetValueOrDefault("JS_TOKEN1_NAME", "")}""")
        iniContent.AppendLine($"TOKEN1_SYMBOL = ""{settings.GetValueOrDefault("JS_TOKEN1_SYMBOL", "")}""")
        iniContent.AppendLine($"TOKEN1_DECIMALS = {settings.GetValueOrDefault("JS_TOKEN1_DECIMALS", "0")}")
        iniContent.AppendLine($"TOKEN1_ADDRESS = ""{settings.GetValueOrDefault("JS_TOKEN1_ADDRESS", "")}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [Timestamp]
        iniContent.AppendLine("[Timestamp]")
        iniContent.AppendLine($"APPROVE = {settings.GetValueOrDefault("JS_APPROVE_TIME", "2")}")
        iniContent.AppendLine($"CYCLE = {settings.GetValueOrDefault("JS_CYCLE_TIME", "10")}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [Ratio]
        iniContent.AppendLine("[Ratio]")
        iniContent.AppendLine($"MAINTAIN_RATIO = {If(settings.GetValueOrDefault("JS_MAINTAIN_RATIO", "0") = "1", "1", "0")}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [Network]
        iniContent.AppendLine("[Network]")
        iniContent.AppendLine($"RPC_URL = ""{settings.GetValueOrDefault("JS_RPC_URL", "")}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")

        ' Seção [VolatilityCheck]
        iniContent.AppendLine("[VolatilityCheck]")
        iniContent.AppendLine($"VOLATILITY_CHECK = {If(settings.GetValueOrDefault("JS_TRADE_VOLATILITY_ON", "false").ToLower() = "true", "1", "0")}")

        Return iniContent.ToString()
    End Function

End Class