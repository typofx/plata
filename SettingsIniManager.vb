Imports System.IO
Imports System.Text

Public Class SettingsIniManager
    Private ReadOnly _iniFilePath As String
    Private ReadOnly _workingDirectory As String

    Public Sub New(workingDirectory As String)
        _workingDirectory = workingDirectory
        Dim parentDirectory = Directory.GetParent(workingDirectory).FullName
        _iniFilePath = Path.Combine(parentDirectory, "settings.ini")
    End Sub



    Public Sub SaveSettings(
    buyAmount As String, buyVolatility As String,
    sellAmount As String, sellVolatility As String,
    poolAddress As String, routerAddress As String,
    token0Name As String, token0Symbol As String, token0Decimals As String, token0Address As String,
    token1Name As String, token1Symbol As String, token1Decimals As String, token1Address As String,
    approveDelay As String, cycleDelay As String, maintainRatio As String, volatilityCheck As String, networkUrl As String)

        Dim iniContent As New StringBuilder()

        ' Adiciona as seções no formato específico
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Input]")
        iniContent.AppendLine($"BUY_AMOUNT = {buyAmount}")
        iniContent.AppendLine($"BUY_VOLATILITY = {buyVolatility}")
        iniContent.AppendLine($"SELL_AMOUNT = {sellAmount}")
        iniContent.AppendLine($"SELL_VOLATILITY = {sellVolatility}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Allowance]")
        iniContent.AppendLine($"POOL_ADDRESS = ""{poolAddress}""")
        iniContent.AppendLine($"ROUTER_ADDRESS = ""{routerAddress}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Token0]")
        iniContent.AppendLine($"TOKEN0_NAME = ""{token0Name}""")
        iniContent.AppendLine($"TOKEN0_SYMBOL = ""{token0Symbol}""")
        iniContent.AppendLine($"TOKEN0_DECIMALS = {token0Decimals}")
        iniContent.AppendLine($"TOKEN0_ADDRESS = ""{token0Address}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Token1]")
        iniContent.AppendLine($"TOKEN1_NAME = ""{token1Name}""")
        iniContent.AppendLine($"TOKEN1_SYMBOL = ""{token1Symbol}""")
        iniContent.AppendLine($"TOKEN1_DECIMALS = {token1Decimals}")
        iniContent.AppendLine($"TOKEN1_ADDRESS = ""{token1Address}""")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Timestamp]")
        iniContent.AppendLine($"APPROVE = {approveDelay}")
        iniContent.AppendLine($"CYCLE = {cycleDelay}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Ratio]")
        iniContent.AppendLine($"MAINTAIN_RATIO = {maintainRatio}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[VolatilityCheck]")
        iniContent.AppendLine($"VOLATILITY_CHECK = {volatilityCheck}")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine(";")
        iniContent.AppendLine("[Network]")
        iniContent.AppendLine($"RPC_URL = {networkUrl}")


        ' Salva o arquivo
        File.WriteAllText(_iniFilePath, iniContent.ToString(), Encoding.UTF8)
        JsConfigUpdater.UpdateFromIni(_iniFilePath, _workingDirectory)
    End Sub

    Public Sub UpdateSettings(
        Optional buyAmount As String = Nothing, Optional buyVolatility As String = Nothing,
        Optional sellAmount As String = Nothing, Optional sellVolatility As String = Nothing,
        Optional poolAddress As String = Nothing, Optional routerAddress As String = Nothing,
        Optional token0Name As String = Nothing, Optional token0Symbol As String = Nothing,
        Optional token0Decimals As String = Nothing, Optional token0Address As String = Nothing,
        Optional token1Name As String = Nothing, Optional token1Symbol As String = Nothing,
        Optional token1Decimals As String = Nothing, Optional token1Address As String = Nothing,
        Optional approveDelay As String = Nothing, Optional cycleDelay As String = Nothing, Optional maintainRatio As String = Nothing, Optional volatilityCheck As String = Nothing, Optional networkUrl As String = Nothing)

        ' Se o arquivo não existir, cria com valores padrão
        If Not File.Exists(_iniFilePath) Then
            SaveSettings(
                If(buyAmount, "0.000015"), If(buyVolatility, "0.3"),
                If(sellAmount, "4"), If(sellVolatility, "0.3"),
                If(poolAddress, """"""), If(routerAddress, """"""),
                If(token0Name, """"""), If(token0Symbol, """"""), If(token0Decimals, "0"), If(token0Address, """"""),
                If(token1Name, """"""), If(token1Symbol, """"""), If(token1Decimals, "0"), If(token1Address, """"""),
                If(approveDelay, "2"), If(cycleDelay, "20"), If(maintainRatio, "0"), If(volatilityCheck, "0"), If(networkUrl, """"""))
            Return
        End If

        ' Lê todo o conteúdo do arquivo
        Dim lines As List(Of String) = File.ReadAllLines(_iniFilePath).ToList()
        Dim section As String = Nothing

        ' Processa cada linha para atualizar os valores
        For i As Integer = 0 To lines.Count - 1
            Dim line = lines(i).Trim()

            ' Detecta seções
            If line.StartsWith("[") AndAlso line.EndsWith("]") Then
                section = line
                Continue For
            End If

            ' Ignora comentários e linhas vazias
            If String.IsNullOrWhiteSpace(line) OrElse line.StartsWith(";") Then
                Continue For
            End If

            ' Processa cada seção
            Select Case section
                Case "[Input]"
                    UpdateValue(lines, i, "BUY_AMOUNT", buyAmount)
                    UpdateValue(lines, i, "BUY_VOLATILITY", buyVolatility)
                    UpdateValue(lines, i, "SELL_AMOUNT", sellAmount)
                    UpdateValue(lines, i, "SELL_VOLATILITY", sellVolatility)

                Case "[Allowance]"
                    UpdateValue(lines, i, "POOL_ADDRESS", poolAddress, True)
                    UpdateValue(lines, i, "ROUTER_ADDRESS", routerAddress, True)

                Case "[Token0]"
                    UpdateValue(lines, i, "TOKEN0_NAME", token0Name, True)
                    UpdateValue(lines, i, "TOKEN0_SYMBOL", token0Symbol, True)
                    UpdateValue(lines, i, "TOKEN0_DECIMALS", token0Decimals)
                    UpdateValue(lines, i, "TOKEN0_ADDRESS", token0Address, True)

                Case "[Token1]"
                    ' CORREÇÃO: Alterado de TOKEN0_ para TOKEN1_
                    UpdateValue(lines, i, "TOKEN1_NAME", token1Name, True)
                    UpdateValue(lines, i, "TOKEN1_SYMBOL", token1Symbol, True)
                    UpdateValue(lines, i, "TOKEN1_DECIMALS", token1Decimals)
                    UpdateValue(lines, i, "TOKEN1_ADDRESS", token1Address, True)

                Case "[Timestamp]"
                    UpdateValue(lines, i, "APPROVE", approveDelay)
                    UpdateValue(lines, i, "CYCLE", cycleDelay)
                Case "[Ratio]"
                    UpdateValue(lines, i, "MAINTAIN_RATIO", maintainRatio)

                Case "[VolatilityCheck]"
                    UpdateValue(lines, i, "VOLATILITY_CHECK", volatilityCheck)

                Case "[Network]"
                    UpdateValue(lines, i, "RPC_URL", networkUrl, True)

            End Select
        Next




        If maintainRatio IsNot Nothing AndAlso Not lines.Any(Function(l) l.Trim() = "[Ratio]") Then
            lines.Add(";")
            lines.Add(";")
            lines.Add(";")
            lines.Add("[Ratio]")
            lines.Add($"MAINTAIN_RATIO = {maintainRatio}")
        End If


        If volatilityCheck IsNot Nothing AndAlso Not lines.Any(Function(l) l.Trim() = "[VolatilityCheck]") Then
            lines.Add(";")
            lines.Add(";")
            lines.Add(";")
            lines.Add("[VolatilityCheck]")
            lines.Add($"VOLATILITY_CHECK = {volatilityCheck}")
        End If

        If networkUrl IsNot Nothing AndAlso Not lines.Any(Function(l) l.Trim() = "[Network]") Then
            lines.Add(";")
            lines.Add(";")
            lines.Add(";")
            lines.Add("[Network]")
            lines.Add($"RPC_URL = ""{networkUrl}""")
        End If


        ' Salva as alterações
        File.WriteAllLines(_iniFilePath, lines, Encoding.UTF8)
        JsConfigUpdater.UpdateFromIni(_iniFilePath, _workingDirectory)
    End Sub

    Public Function GetSetting(section As String, key As String) As String
        If Not File.Exists(_iniFilePath) Then Return Nothing

        Dim currentSection As String = ""

        For Each line In File.ReadAllLines(_iniFilePath)
            Dim trimmedLine = line.Trim()

            If trimmedLine.StartsWith("[") AndAlso trimmedLine.EndsWith("]") Then
                currentSection = trimmedLine
                Continue For
            End If

            If currentSection = $"[{section}]" AndAlso trimmedLine.StartsWith(key) Then
                Dim parts = trimmedLine.Split(New Char() {"="c}, 2)
                If parts.Length = 2 Then
                    Return parts(1).Trim().Trim(""""c) ' Remove aspas se existirem
                End If
            End If
        Next

        Return Nothing
    End Function

    Private Sub UpdateValue(ByRef lines As List(Of String), index As Integer, key As String, newValue As String, Optional quoted As Boolean = False)
        If newValue IsNot Nothing AndAlso lines(index).Trim().StartsWith(key) Then
            Dim value = If(quoted, $"""{newValue}""", newValue)
            lines(index) = $"{key} = {value}"
        End If
    End Sub
End Class