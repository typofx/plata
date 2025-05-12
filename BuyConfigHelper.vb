Imports System.IO
Imports System.Text.RegularExpressions
Imports System.Globalization

Public Class BuyConfigHelper
    ' Caminho do arquivo de configuração
    Private ReadOnly _settingsIniPath As String

    ' Valores padrão
    Private ReadOnly DEFAULT_AMOUNT As Decimal = 0.05D
    Private ReadOnly DEFAULT_VOLATILITY As Decimal = 0.2D

    ' Controles do formulário
    Public Property AmountTextBox As TextBox
    Public Property VolatilityComboBox As ComboBox
    Public Property SaveButton As Button
    Public Property ResetButton As Button

    ' Construtor
    Public Sub New(workingDirectory As String)
        ' Define o caminho para o settings.ini na pasta pai
        _settingsIniPath = Path.Combine(Directory.GetParent(workingDirectory).FullName, "settings.ini")
    End Sub

    ' Inicialização completa
    Public Sub Initialize()
        ' Configuração do ComboBox
        ConfigureVolatilityComboBox()

        ' Configuração dos eventos
        ConfigureEventHandlers()

        ' Carrega os valores atuais
        LoadCurrentValues()
    End Sub

    ' Configuração detalhada do ComboBox
    Private Sub ConfigureVolatilityComboBox()
        With VolatilityComboBox
            .Items.Clear()
            .Items.Add("flat (10%)")
            .Items.Add("low (20%)")
            .Items.Add("high (30%)")
            .Items.Add("heavy (40%)")
            .Items.Add("freak (50%)")
            .DropDownStyle = ComboBoxStyle.DropDownList
        End With
    End Sub

    ' Configuração dos eventos
    Private Sub ConfigureEventHandlers()
        AddHandler SaveButton.Click, AddressOf btnSave_Click
        AddHandler ResetButton.Click, AddressOf btnReset_Click
    End Sub

    ' Carregamento dos valores atuais apenas do INI
    Private Sub LoadCurrentValues()
        If Not LoadValuesFromIni() Then
            ' Se não conseguiu carregar do INI, usa valores padrão
            ResetToDefaults()
        End If
    End Sub

    ' Carrega valores do settings.ini
    Private Function LoadValuesFromIni() As Boolean
        If Not File.Exists(_settingsIniPath) Then Return False

        Try
            Dim iniContent As String = File.ReadAllText(_settingsIniPath)
            Dim amountMatch = Regex.Match(iniContent, "BUY_AMOUNT\s*=\s*([\d.]+)", RegexOptions.IgnoreCase)
            Dim volatilityMatch = Regex.Match(iniContent, "BUY_VOLATILITY\s*=\s*([\d.]+)", RegexOptions.IgnoreCase)

            Dim loadedAnyValue As Boolean = False

            ' Carrega o valor base se encontrado
            If amountMatch.Success AndAlso amountMatch.Groups.Count > 1 Then
                AmountTextBox.Text = amountMatch.Groups(1).Value.Trim()
                loadedAnyValue = True
            End If

            ' Carrega a volatilidade se encontrada
            If volatilityMatch.Success AndAlso volatilityMatch.Groups.Count > 1 Then
                Dim volValue As Decimal
                If Decimal.TryParse(volatilityMatch.Groups(1).Value, NumberStyles.Any, CultureInfo.InvariantCulture, volValue) Then
                    ' Mapeia o valor numérico para o texto correspondente
                    Dim volatilityText = GetVolatilityText(volValue)
                    Dim index = VolatilityComboBox.Items.IndexOf(volatilityText)

                    If index >= 0 Then
                        VolatilityComboBox.SelectedIndex = index
                    Else
                        VolatilityComboBox.SelectedIndex = 1 ' Default para low (20%)
                    End If
                    loadedAnyValue = True
                End If
            End If

            Return loadedAnyValue
        Catch ex As Exception
            MessageBox.Show($"Erro ao ler o arquivo settings.ini: {ex.Message}", "Erro",
                          MessageBoxButtons.OK, MessageBoxIcon.Error)
            Return False
        End Try
    End Function

    ' Converte o valor numérico para o texto do ComboBox
    Private Function GetVolatilityText(volValue As Decimal) As String
        Select Case volValue
            Case 0.1 : Return "flat (10%)"
            Case 0.2 : Return "low (20%)"
            Case 0.3 : Return "high (30%)"
            Case 0.4 : Return "heavy (40%)"
            Case 0.5 : Return "freak (50%)"
            Case Else : Return "low (20%)"
        End Select
    End Function

    ' Evento de clique no botão Salvar
    Private Sub btnSave_Click(sender As Object, e As EventArgs)
        ' Validação do valor
        Dim amountText As String = AmountTextBox.Text.Trim()
        Dim dummyDecimal As Decimal

        If Not Decimal.TryParse(amountText, NumberStyles.Any, CultureInfo.InvariantCulture, dummyDecimal) Then
            MessageBox.Show("Digite um valor numérico válido para o valor de compra!", "Valor inválido",
                      MessageBoxButtons.OK, MessageBoxIcon.Warning)
            Return
        End If

        ' Obtém volatilidade
        Dim volatility As Decimal = GetVolatilityPercent(VolatilityComboBox.SelectedItem.ToString())

        ' Atualiza arquivo INI
        UpdateIniFile(amountText, volatility)
    End Sub

    ' Atualiza o arquivo INI
    Private Sub UpdateIniFile(amountText As String, volatility As Decimal)
        Try
            Dim iniContent As String = If(File.Exists(_settingsIniPath), File.ReadAllText(_settingsIniPath), "")

            ' Atualiza BUY_AMOUNT
            If Regex.IsMatch(iniContent, "BUY_AMOUNT\s*=", RegexOptions.IgnoreCase) Then
                iniContent = Regex.Replace(iniContent, "BUY_AMOUNT\s*=\s*[\d.]+", $"BUY_AMOUNT = {amountText}", RegexOptions.IgnoreCase)
            Else
                iniContent += $"{vbCrLf}BUY_AMOUNT = {amountText}{vbCrLf}"
            End If

            ' Atualiza BUY_VOLATILITY
            Dim volText = volatility.ToString("0.##", CultureInfo.InvariantCulture)
            If Regex.IsMatch(iniContent, "BUY_VOLATILITY\s*=", RegexOptions.IgnoreCase) Then
                iniContent = Regex.Replace(iniContent, "BUY_VOLATILITY\s*=\s*[\d.]+", $"BUY_VOLATILITY = {volText}", RegexOptions.IgnoreCase)
            Else
                iniContent += $"BUY_VOLATILITY = {volText}{vbCrLf}"
            End If

            ' Salva o arquivo
            File.WriteAllText(_settingsIniPath, iniContent)
            MessageBox.Show("Configurações de compra salvas com sucesso no settings.ini!", "Sucesso",
                          MessageBoxButtons.OK, MessageBoxIcon.Information)
        Catch ex As Exception
            MessageBox.Show($"Erro ao salvar configurações: {ex.Message}", "Erro",
                      MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    ' Obtém o percentual de volatilidade do texto selecionado
    Private Function GetVolatilityPercent(volatilityText As String) As Decimal
        Select Case volatilityText.Split(" "c)(0).ToLower()
            Case "flat" : Return 0.1D
            Case "low" : Return 0.2D
            Case "high" : Return 0.3D
            Case "heavy" : Return 0.4D
            Case "freak" : Return 0.5D
            Case Else : Return 0.2D
        End Select
    End Function

    ' Evento de clique no botão Resetar
    Private Sub btnReset_Click(sender As Object, e As EventArgs)
        ResetToDefaults()
    End Sub

    ' Reseta para os valores padrão
    Public Sub ResetToDefaults()
        AmountTextBox.Text = DEFAULT_AMOUNT.ToString("0.##", CultureInfo.InvariantCulture)
        VolatilityComboBox.SelectedIndex = 1 ' low (20%)
    End Sub

    ' Limpeza dos recursos
    Public Sub Cleanup()
        RemoveHandler SaveButton.Click, AddressOf btnSave_Click
        RemoveHandler ResetButton.Click, AddressOf btnReset_Click
    End Sub
End Class