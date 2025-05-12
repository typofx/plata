Imports System.Data.SQLite
Imports System.IO
Imports System.Linq
Imports System.Text.RegularExpressions

Public Class MachineRegistry
    Private Shared ReadOnly ConnectionString As String = "Data Source=" & (New FormConexao()).ObterCaminho() & ";Version=3;"
    Private Const MACHINE_ID As Integer = 1 ' ID fixo para o único registro

    ' Lista de campos protegidos que não podem ser alterados pelo usuário
    Private ReadOnly ProtectedFields As New List(Of String) From {
        "MachineName", "SerialNumber", "RegistrationDate", "LastUpdate", "Responsible"
    }

    ' Singleton instance
    Private Shared _instance As MachineRegistry
    Public Shared ReadOnly Property Instance() As MachineRegistry
        Get
            If _instance Is Nothing Then
                _instance = New MachineRegistry()
            End If
            Return _instance
        End Get
    End Property

    ' Classe para representar informações de países
    Public Class CountryInfo
        Public Property Name As String
        Public Property Code As String
        Public Property PhoneCode As String

        Public Overrides Function ToString() As String
            Return $"{Name} (+{PhoneCode})"
        End Function
    End Class

    Private Sub New()
        InitializeDatabase()
        EnsureMachineRecordExists()
    End Sub

    Private Sub InitializeDatabase()
        Dim dbPath = (New FormConexao()).ObterCaminho()

        If Not File.Exists(dbPath) Then
            SQLiteConnection.CreateFile(dbPath)
        End If

        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()



            ' Verifica se a coluna BeOuTee já existe
            Dim checkColumnSql As String = "PRAGMA table_info(Machine)"
            Dim hasBeOuTeeColumn As Boolean = False

            Using cmdCheck As New SQLiteCommand(checkColumnSql, conn)
                Using reader = cmdCheck.ExecuteReader()
                    While reader.Read()
                        If reader("name").ToString() = "BeOuTee" Then
                            hasBeOuTeeColumn = True
                            Exit While
                        End If
                    End While
                End Using
            End Using

            Dim createTableSql As String = "CREATE TABLE IF NOT EXISTS Machine (" &
                         "Id INTEGER PRIMARY KEY, " &
                         "MachineName TEXT NOT NULL, " &
                         "SerialNumber TEXT UNIQUE NOT NULL, " &
                         "Location TEXT NOT NULL, " &
                         "Country TEXT NOT NULL, " &
                         "CountryCode TEXT NOT NULL, " &
                         "City TEXT NOT NULL, " &
                         "Responsible TEXT NOT NULL, " &
                         "RegistrationDate TEXT NOT NULL, " &
                         "LastUpdate TEXT NOT NULL, " &
                         "Notes TEXT, " &
                         "ScriptNumber INTEGER DEFAULT 0, " &
                         "BeOuTee TEXT, " &
                         "nudBotEnable INTEGER DEFAULT 0)" ' Novo campo adicionado

            Using cmd As New SQLiteCommand(createTableSql, conn)
                cmd.ExecuteNonQuery()
            End Using


            ' Verifica se a coluna nudBotEnable já existe
            Dim hasNudBotEnableColumn As Boolean = False
            Using cmdCheck As New SQLiteCommand(checkColumnSql, conn)
                Using reader = cmdCheck.ExecuteReader()
                    While reader.Read()
                        If reader("name").ToString() = "nudBotEnable" Then
                            hasNudBotEnableColumn = True
                            Exit While
                        End If
                    End While
                End Using
            End Using

            ' Se a coluna não existia, adiciona agora
            If Not hasNudBotEnableColumn Then
                Dim alterTableSql As String = "ALTER TABLE Machine ADD COLUMN nudBotEnable INTEGER DEFAULT 0"
                Using cmdAlter As New SQLiteCommand(alterTableSql, conn)
                    cmdAlter.ExecuteNonQuery()
                End Using
            End If


            ' Se a coluna não existia, adiciona agora
            If Not hasBeOuTeeColumn Then
                Dim alterTableSql As String = "ALTER TABLE Machine ADD COLUMN BeOuTee TEXT"
                Using cmdAlter As New SQLiteCommand(alterTableSql, conn)
                    cmdAlter.ExecuteNonQuery()
                End Using
            End If
        End Using
    End Sub

    Public Function IsValidBeOuTee(text As String) As Boolean
        Return Regex.IsMatch(text, "^[a-zA-Z]*$")
    End Function

    Public Sub LoadBeOuTeeOptions(comboBox As ComboBox, Optional selectedValue As String = "")
        comboBox.BeginUpdate()
        comboBox.Items.Clear()

        ' Adiciona todas as letras de A a Z
        For i As Integer = Asc("A") To Asc("Z")
            comboBox.Items.Add(Chr(i))
        Next

        ' Tenta selecionar o valor especificado
        If Not String.IsNullOrEmpty(selectedValue) Then
            selectedValue = selectedValue.ToUpper()
            For i As Integer = 0 To comboBox.Items.Count - 1
                If comboBox.Items(i).ToString() = selectedValue Then
                    comboBox.SelectedIndex = i
                    Exit For
                End If
            Next
        ElseIf comboBox.Items.Count > 0 Then
            comboBox.SelectedIndex = 0 ' Seleciona "A" como padrão se não houver valor
        End If

        comboBox.EndUpdate()
    End Sub

    Private Sub EnsureMachineRecordExists()
        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()

            ' Verifica se o registro existe
            Dim checkSql As String = "SELECT COUNT(*) FROM Machine WHERE Id = @Id"
            Using cmd As New SQLiteCommand(checkSql, conn)
                cmd.Parameters.AddWithValue("@Id", MACHINE_ID)
                Dim exists As Integer = Convert.ToInt32(cmd.ExecuteScalar())

                If exists = 0 Then
                    ' Cria registro padrão se não existir
                    CreateDefaultMachineRecord(conn)
                Else
                    ' Garante que os campos protegidos estão corretos
                    ValidateProtectedFields(conn)
                End If
            End Using
        End Using
    End Sub

    Private Sub CreateDefaultMachineRecord(conn As SQLiteConnection)
        Dim insertSql As String = "INSERT INTO Machine " &
                            "(Id, MachineName, SerialNumber, Location, " &
                            "Country, CountryCode, City, Responsible, " &
                            "RegistrationDate, LastUpdate, Notes, ScriptNumber, BeOuTee) " &
                            "VALUES (@Id, @MachineName, @SerialNumber, @Location, " &
                            "@Country, @CountryCode, @City, @Responsible, " &
                            "@RegistrationDate, @LastUpdate, @Notes, @ScriptNumber, @BeOuTee)"

        Using insertCmd As New SQLiteCommand(insertSql, conn)
            insertCmd.Parameters.AddWithValue("@Id", MACHINE_ID)
            insertCmd.Parameters.AddWithValue("@MachineName", Environment.MachineName)
            insertCmd.Parameters.AddWithValue("@SerialNumber", Guid.NewGuid().ToString())
            insertCmd.Parameters.AddWithValue("@Location", "Não especificado")
            insertCmd.Parameters.AddWithValue("@Country", "Não especificado")
            insertCmd.Parameters.AddWithValue("@CountryCode", "XX")
            insertCmd.Parameters.AddWithValue("@City", "Não especificado")
            insertCmd.Parameters.AddWithValue("@Responsible", Environment.UserName)
            insertCmd.Parameters.AddWithValue("@RegistrationDate", DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"))
            insertCmd.Parameters.AddWithValue("@LastUpdate", DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"))
            insertCmd.Parameters.AddWithValue("@Notes", "Registro criado automaticamente")
            insertCmd.Parameters.AddWithValue("@ScriptNumber", 0)
            insertCmd.Parameters.AddWithValue("@BeOuTee", "A") ' Valor padrão
            insertCmd.Parameters.AddWithValue("@nudBotEnable", 0) ' Valor padrão
            insertCmd.ExecuteNonQuery()
        End Using
    End Sub

    Private Sub ValidateProtectedFields(conn As SQLiteConnection)
        Dim currentData = GetMachine(conn)
        Dim needsUpdate As Boolean = False

        ' Verifica cada campo protegido
        If currentData("MachineName").ToString() <> Environment.MachineName Then
            currentData("MachineName") = Environment.MachineName
            needsUpdate = True
        End If

        If String.IsNullOrEmpty(currentData("SerialNumber").ToString()) Then
            currentData("SerialNumber") = Guid.NewGuid().ToString()
            needsUpdate = True
        End If

        If currentData("Responsible").ToString() <> Environment.UserName Then
            currentData("Responsible") = Environment.UserName
            needsUpdate = True
        End If

        If String.IsNullOrEmpty(currentData("RegistrationDate").ToString()) Then
            currentData("RegistrationDate") = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss")
            needsUpdate = True
        End If

        ' LastUpdate sempre é atualizado
        currentData("LastUpdate") = DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss")
        needsUpdate = True

        If needsUpdate Then
            UpdateMachineRecord(conn, currentData)
        End If
    End Sub

    Private Sub UpdateMachineRecord(conn As SQLiteConnection, data As DataRow)
        Dim updateSql As String = "UPDATE Machine SET " &
                                "MachineName = @MachineName, " &
                                "SerialNumber = @SerialNumber, " &
                                "Responsible = @Responsible, " &
                                "LastUpdate = @LastUpdate, " &
                                "RegistrationDate = @RegistrationDate " &
                                "WHERE Id = @Id"

        Using cmd As New SQLiteCommand(updateSql, conn)
            cmd.Parameters.AddWithValue("@Id", MACHINE_ID)
            cmd.Parameters.AddWithValue("@MachineName", data("MachineName"))
            cmd.Parameters.AddWithValue("@SerialNumber", data("SerialNumber"))
            cmd.Parameters.AddWithValue("@Responsible", data("Responsible"))
            cmd.Parameters.AddWithValue("@LastUpdate", data("LastUpdate"))
            cmd.Parameters.AddWithValue("@RegistrationDate", data("RegistrationDate"))

            cmd.ExecuteNonQuery()
        End Using
    End Sub

    ' Método para carregar países no ComboBox
    Public Sub LoadCountries(comboBox As ComboBox)
        comboBox.BeginUpdate()
        comboBox.Items.Clear()

        ' Países principais
        comboBox.Items.Add(New CountryInfo With {.Name = "UK", .Code = "GB", .PhoneCode = "44"})
        comboBox.Items.Add(New CountryInfo With {.Name = "USA", .Code = "US", .PhoneCode = "1"})
        comboBox.Items.Add(New CountryInfo With {.Name = "Brazil", .Code = "BR", .PhoneCode = "55"})
        comboBox.Items.Add(New CountryInfo With {.Name = "Ireland", .Code = "IE", .PhoneCode = "353"})

        ' Separador
        comboBox.Items.Add("---------------------------")

        ' Demais países (lista reduzida para exemplo)
        Dim otherCountries As New List(Of CountryInfo) From {
        New CountryInfo With {.Name = "Algeria", .Code = "DZ", .PhoneCode = "213"},
        New CountryInfo With {.Name = "Andorra", .Code = "AD", .PhoneCode = "376"},
        New CountryInfo With {.Name = "Angola", .Code = "AO", .PhoneCode = "244"},
        New CountryInfo With {.Name = "Anguilla", .Code = "AI", .PhoneCode = "1264"},
        New CountryInfo With {.Name = "Antigua & Barbuda", .Code = "AG", .PhoneCode = "1268"},
        New CountryInfo With {.Name = "Argentina", .Code = "AR", .PhoneCode = "54"},
        New CountryInfo With {.Name = "Armenia", .Code = "AM", .PhoneCode = "374"},
        New CountryInfo With {.Name = "Aruba", .Code = "AW", .PhoneCode = "297"},
        New CountryInfo With {.Name = "Australia", .Code = "AU", .PhoneCode = "61"},
        New CountryInfo With {.Name = "Austria", .Code = "AT", .PhoneCode = "43"},
        New CountryInfo With {.Name = "Azerbaijan", .Code = "AZ", .PhoneCode = "994"},
        New CountryInfo With {.Name = "Bahamas", .Code = "BS", .PhoneCode = "1242"},
        New CountryInfo With {.Name = "Bahrain", .Code = "BH", .PhoneCode = "973"},
        New CountryInfo With {.Name = "Bangladesh", .Code = "BD", .PhoneCode = "880"},
        New CountryInfo With {.Name = "Barbados", .Code = "BB", .PhoneCode = "1246"},
        New CountryInfo With {.Name = "Belarus", .Code = "BY", .PhoneCode = "375"},
        New CountryInfo With {.Name = "Belgium", .Code = "BE", .PhoneCode = "32"},
        New CountryInfo With {.Name = "Belize", .Code = "BZ", .PhoneCode = "501"},
        New CountryInfo With {.Name = "Benin", .Code = "BJ", .PhoneCode = "229"},
        New CountryInfo With {.Name = "Bermuda", .Code = "BM", .PhoneCode = "1441"},
        New CountryInfo With {.Name = "Bhutan", .Code = "BT", .PhoneCode = "975"},
        New CountryInfo With {.Name = "Bolivia", .Code = "BO", .PhoneCode = "591"},
        New CountryInfo With {.Name = "Bosnia Herzegovina", .Code = "BA", .PhoneCode = "387"},
        New CountryInfo With {.Name = "Botswana", .Code = "BW", .PhoneCode = "267"},
        New CountryInfo With {.Name = "Brunei", .Code = "BN", .PhoneCode = "673"},
        New CountryInfo With {.Name = "Bulgaria", .Code = "BG", .PhoneCode = "359"},
        New CountryInfo With {.Name = "Burkina Faso", .Code = "BF", .PhoneCode = "226"},
        New CountryInfo With {.Name = "Burundi", .Code = "BI", .PhoneCode = "257"},
        New CountryInfo With {.Name = "Cambodia", .Code = "KH", .PhoneCode = "855"},
        New CountryInfo With {.Name = "Cameroon", .Code = "CM", .PhoneCode = "237"},
        New CountryInfo With {.Name = "Canada", .Code = "CA", .PhoneCode = "1"},
        New CountryInfo With {.Name = "Cape Verde Islands", .Code = "CV", .PhoneCode = "238"},
        New CountryInfo With {.Name = "Cayman Islands", .Code = "KY", .PhoneCode = "1345"},
        New CountryInfo With {.Name = "Central African Republic", .Code = "CF", .PhoneCode = "236"},
        New CountryInfo With {.Name = "Chile", .Code = "CL", .PhoneCode = "56"},
        New CountryInfo With {.Name = "China", .Code = "CN", .PhoneCode = "86"},
        New CountryInfo With {.Name = "Colombia", .Code = "CO", .PhoneCode = "57"},
        New CountryInfo With {.Name = "Comoros", .Code = "KM", .PhoneCode = "269"},
        New CountryInfo With {.Name = "Congo", .Code = "CG", .PhoneCode = "242"},
        New CountryInfo With {.Name = "Cook Islands", .Code = "CK", .PhoneCode = "682"},
        New CountryInfo With {.Name = "Costa Rica", .Code = "CR", .PhoneCode = "506"},
        New CountryInfo With {.Name = "Croatia", .Code = "HR", .PhoneCode = "385"},
        New CountryInfo With {.Name = "Cuba", .Code = "CU", .PhoneCode = "53"},
        New CountryInfo With {.Name = "Cyprus North", .Code = "CY", .PhoneCode = "90392"},
        New CountryInfo With {.Name = "Cyprus South", .Code = "CY", .PhoneCode = "357"},
        New CountryInfo With {.Name = "Czech Republic", .Code = "CZ", .PhoneCode = "42"},
        New CountryInfo With {.Name = "Denmark", .Code = "DK", .PhoneCode = "45"},
        New CountryInfo With {.Name = "Djibouti", .Code = "DJ", .PhoneCode = "253"},
        New CountryInfo With {.Name = "Dominica", .Code = "DM", .PhoneCode = "1809"},
        New CountryInfo With {.Name = "Dominican Republic", .Code = "DO", .PhoneCode = "1809"},
        New CountryInfo With {.Name = "Ecuador", .Code = "EC", .PhoneCode = "593"},
        New CountryInfo With {.Name = "Egypt", .Code = "EG", .PhoneCode = "20"},
        New CountryInfo With {.Name = "El Salvador", .Code = "SV", .PhoneCode = "503"},
        New CountryInfo With {.Name = "Equatorial Guinea", .Code = "GQ", .PhoneCode = "240"},
        New CountryInfo With {.Name = "Eritrea", .Code = "ER", .PhoneCode = "291"},
        New CountryInfo With {.Name = "Estonia", .Code = "EE", .PhoneCode = "372"},
        New CountryInfo With {.Name = "Ethiopia", .Code = "ET", .PhoneCode = "251"},
        New CountryInfo With {.Name = "Falkland Islands", .Code = "FK", .PhoneCode = "500"},
        New CountryInfo With {.Name = "Faroe Islands", .Code = "FO", .PhoneCode = "298"},
        New CountryInfo With {.Name = "Fiji", .Code = "FJ", .PhoneCode = "679"},
        New CountryInfo With {.Name = "Finland", .Code = "FI", .PhoneCode = "358"},
        New CountryInfo With {.Name = "France", .Code = "FR", .PhoneCode = "33"},
        New CountryInfo With {.Name = "French Guiana", .Code = "GF", .PhoneCode = "594"},
        New CountryInfo With {.Name = "French Polynesia", .Code = "PF", .PhoneCode = "689"},
        New CountryInfo With {.Name = "Gabon", .Code = "GA", .PhoneCode = "241"},
        New CountryInfo With {.Name = "Gambia", .Code = "GM", .PhoneCode = "220"},
        New CountryInfo With {.Name = "Georgia", .Code = "GE", .PhoneCode = "7880"},
        New CountryInfo With {.Name = "Germany", .Code = "DE", .PhoneCode = "49"},
        New CountryInfo With {.Name = "Ghana", .Code = "GH", .PhoneCode = "233"},
        New CountryInfo With {.Name = "Gibraltar", .Code = "GI", .PhoneCode = "350"},
        New CountryInfo With {.Name = "Greece", .Code = "GR", .PhoneCode = "30"},
        New CountryInfo With {.Name = "Greenland", .Code = "GL", .PhoneCode = "299"},
        New CountryInfo With {.Name = "Grenada", .Code = "GD", .PhoneCode = "1473"},
        New CountryInfo With {.Name = "Guadeloupe", .Code = "GP", .PhoneCode = "590"},
        New CountryInfo With {.Name = "Guam", .Code = "GU", .PhoneCode = "671"},
        New CountryInfo With {.Name = "Guatemala", .Code = "GT", .PhoneCode = "502"},
        New CountryInfo With {.Name = "Guinea", .Code = "GN", .PhoneCode = "224"},
        New CountryInfo With {.Name = "Guinea - Bissau", .Code = "GW", .PhoneCode = "245"},
        New CountryInfo With {.Name = "Guyana", .Code = "GY", .PhoneCode = "592"},
        New CountryInfo With {.Name = "Haiti", .Code = "HT", .PhoneCode = "509"},
        New CountryInfo With {.Name = "Honduras", .Code = "HN", .PhoneCode = "504"},
        New CountryInfo With {.Name = "Hong Kong", .Code = "HK", .PhoneCode = "852"},
        New CountryInfo With {.Name = "Hungary", .Code = "HU", .PhoneCode = "36"},
        New CountryInfo With {.Name = "Iceland", .Code = "IS", .PhoneCode = "354"},
        New CountryInfo With {.Name = "India", .Code = "IN", .PhoneCode = "91"},
        New CountryInfo With {.Name = "Indonesia", .Code = "ID", .PhoneCode = "62"},
        New CountryInfo With {.Name = "Iran", .Code = "IR", .PhoneCode = "98"},
        New CountryInfo With {.Name = "Iraq", .Code = "IQ", .PhoneCode = "964"},
        New CountryInfo With {.Name = "Israel", .Code = "IL", .PhoneCode = "972"},
        New CountryInfo With {.Name = "Italy", .Code = "IT", .PhoneCode = "39"},
        New CountryInfo With {.Name = "Jamaica", .Code = "JM", .PhoneCode = "1876"},
        New CountryInfo With {.Name = "Japan", .Code = "JP", .PhoneCode = "81"},
        New CountryInfo With {.Name = "Jordan", .Code = "JO", .PhoneCode = "962"},
        New CountryInfo With {.Name = "Kazakhstan", .Code = "KZ", .PhoneCode = "7"},
        New CountryInfo With {.Name = "Kenya", .Code = "KE", .PhoneCode = "254"},
        New CountryInfo With {.Name = "Kiribati", .Code = "KI", .PhoneCode = "686"},
        New CountryInfo With {.Name = "Korea North", .Code = "KP", .PhoneCode = "850"},
        New CountryInfo With {.Name = "Korea South", .Code = "KR", .PhoneCode = "82"},
        New CountryInfo With {.Name = "Kuwait", .Code = "KW", .PhoneCode = "965"},
        New CountryInfo With {.Name = "Kyrgyzstan", .Code = "KG", .PhoneCode = "996"},
        New CountryInfo With {.Name = "Laos", .Code = "LA", .PhoneCode = "856"},
        New CountryInfo With {.Name = "Latvia", .Code = "LV", .PhoneCode = "371"},
        New CountryInfo With {.Name = "Lebanon", .Code = "LB", .PhoneCode = "961"},
        New CountryInfo With {.Name = "Lesotho", .Code = "LS", .PhoneCode = "266"},
        New CountryInfo With {.Name = "Liberia", .Code = "LR", .PhoneCode = "231"},
        New CountryInfo With {.Name = "Libya", .Code = "LY", .PhoneCode = "218"},
        New CountryInfo With {.Name = "Liechtenstein", .Code = "LI", .PhoneCode = "417"},
        New CountryInfo With {.Name = "Lithuania", .Code = "LT", .PhoneCode = "370"},
        New CountryInfo With {.Name = "Luxembourg", .Code = "LU", .PhoneCode = "352"},
        New CountryInfo With {.Name = "Macao", .Code = "MO", .PhoneCode = "853"},
        New CountryInfo With {.Name = "Macedonia", .Code = "MK", .PhoneCode = "389"},
        New CountryInfo With {.Name = "Madagascar", .Code = "MG", .PhoneCode = "261"},
        New CountryInfo With {.Name = "Malawi", .Code = "MW", .PhoneCode = "265"},
        New CountryInfo With {.Name = "Malaysia", .Code = "MY", .PhoneCode = "60"},
        New CountryInfo With {.Name = "Maldives", .Code = "MV", .PhoneCode = "960"},
        New CountryInfo With {.Name = "Mali", .Code = "ML", .PhoneCode = "223"},
        New CountryInfo With {.Name = "Malta", .Code = "MT", .PhoneCode = "356"},
        New CountryInfo With {.Name = "Marshall Islands", .Code = "MH", .PhoneCode = "692"},
        New CountryInfo With {.Name = "Martinique", .Code = "MQ", .PhoneCode = "596"},
        New CountryInfo With {.Name = "Mauritania", .Code = "MR", .PhoneCode = "222"},
        New CountryInfo With {.Name = "Mayotte", .Code = "YT", .PhoneCode = "269"},
        New CountryInfo With {.Name = "Mexico", .Code = "MX", .PhoneCode = "52"},
        New CountryInfo With {.Name = "Micronesia", .Code = "FM", .PhoneCode = "691"},
        New CountryInfo With {.Name = "Moldova", .Code = "MD", .PhoneCode = "373"},
        New CountryInfo With {.Name = "Monaco", .Code = "MC", .PhoneCode = "377"},
        New CountryInfo With {.Name = "Mongolia", .Code = "MN", .PhoneCode = "976"},
        New CountryInfo With {.Name = "Montserrat", .Code = "MS", .PhoneCode = "1664"},
        New CountryInfo With {.Name = "Morocco", .Code = "MA", .PhoneCode = "212"},
        New CountryInfo With {.Name = "Mozambique", .Code = "MZ", .PhoneCode = "258"},
        New CountryInfo With {.Name = "Myanmar", .Code = "MN", .PhoneCode = "95"},
        New CountryInfo With {.Name = "Namibia", .Code = "NA", .PhoneCode = "264"},
        New CountryInfo With {.Name = "Nauru", .Code = "NR", .PhoneCode = "674"},
        New CountryInfo With {.Name = "Nepal", .Code = "NP", .PhoneCode = "977"},
        New CountryInfo With {.Name = "Netherlands", .Code = "NL", .PhoneCode = "31"},
        New CountryInfo With {.Name = "New Caledonia", .Code = "NC", .PhoneCode = "687"},
        New CountryInfo With {.Name = "New Zealand", .Code = "NZ", .PhoneCode = "64"},
        New CountryInfo With {.Name = "Nicaragua", .Code = "NI", .PhoneCode = "505"},
        New CountryInfo With {.Name = "Niger", .Code = "NE", .PhoneCode = "227"},
        New CountryInfo With {.Name = "Nigeria", .Code = "NG", .PhoneCode = "234"},
        New CountryInfo With {.Name = "Niue", .Code = "NU", .PhoneCode = "683"},
        New CountryInfo With {.Name = "Norfolk Islands", .Code = "NF", .PhoneCode = "672"},
        New CountryInfo With {.Name = "Northern Marianas", .Code = "NP", .PhoneCode = "670"},
        New CountryInfo With {.Name = "Norway", .Code = "NO", .PhoneCode = "47"},
        New CountryInfo With {.Name = "Oman", .Code = "OM", .PhoneCode = "968"},
        New CountryInfo With {.Name = "Palau", .Code = "PW", .PhoneCode = "680"},
        New CountryInfo With {.Name = "Panama", .Code = "PA", .PhoneCode = "507"},
        New CountryInfo With {.Name = "Papua New Guinea", .Code = "PG", .PhoneCode = "675"},
        New CountryInfo With {.Name = "Paraguay", .Code = "PY", .PhoneCode = "595"},
        New CountryInfo With {.Name = "Peru", .Code = "PE", .PhoneCode = "51"},
        New CountryInfo With {.Name = "Philippines", .Code = "PH", .PhoneCode = "63"},
        New CountryInfo With {.Name = "Poland", .Code = "PL", .PhoneCode = "48"},
        New CountryInfo With {.Name = "Portugal", .Code = "PT", .PhoneCode = "351"},
        New CountryInfo With {.Name = "Puerto Rico", .Code = "PR", .PhoneCode = "1787"},
        New CountryInfo With {.Name = "Qatar", .Code = "QA", .PhoneCode = "974"},
        New CountryInfo With {.Name = "Reunion", .Code = "RE", .PhoneCode = "262"},
        New CountryInfo With {.Name = "Romania", .Code = "RO", .PhoneCode = "40"},
        New CountryInfo With {.Name = "Russia", .Code = "RU", .PhoneCode = "7"},
        New CountryInfo With {.Name = "Rwanda", .Code = "RW", .PhoneCode = "250"},
        New CountryInfo With {.Name = "San Marino", .Code = "SM", .PhoneCode = "378"},
        New CountryInfo With {.Name = "Sao Tome & Principe", .Code = "ST", .PhoneCode = "239"},
        New CountryInfo With {.Name = "Saudi Arabia", .Code = "SA", .PhoneCode = "966"},
        New CountryInfo With {.Name = "Senegal", .Code = "SN", .PhoneCode = "221"},
        New CountryInfo With {.Name = "Serbia", .Code = "CS", .PhoneCode = "381"},
        New CountryInfo With {.Name = "Seychelles", .Code = "SC", .PhoneCode = "248"},
        New CountryInfo With {.Name = "Sierra Leone", .Code = "SL", .PhoneCode = "232"},
        New CountryInfo With {.Name = "Singapore", .Code = "SG", .PhoneCode = "65"},
        New CountryInfo With {.Name = "Slovak Republic", .Code = "SK", .PhoneCode = "421"},
        New CountryInfo With {.Name = "Slovenia", .Code = "SI", .PhoneCode = "386"},
        New CountryInfo With {.Name = "Solomon Islands", .Code = "SB", .PhoneCode = "677"},
        New CountryInfo With {.Name = "Somalia", .Code = "SO", .PhoneCode = "252"},
        New CountryInfo With {.Name = "South Africa", .Code = "ZA", .PhoneCode = "27"},
        New CountryInfo With {.Name = "Spain", .Code = "ES", .PhoneCode = "34"},
        New CountryInfo With {.Name = "Sri Lanka", .Code = "LK", .PhoneCode = "94"},
        New CountryInfo With {.Name = "St. Helena", .Code = "SH", .PhoneCode = "290"},
        New CountryInfo With {.Name = "St. Kitts", .Code = "KN", .PhoneCode = "1869"},
        New CountryInfo With {.Name = "St. Lucia", .Code = "SC", .PhoneCode = "1758"},
        New CountryInfo With {.Name = "Sudan", .Code = "SD", .PhoneCode = "249"},
        New CountryInfo With {.Name = "Suriname", .Code = "SR", .PhoneCode = "597"},
        New CountryInfo With {.Name = "Swaziland", .Code = "SZ", .PhoneCode = "268"},
        New CountryInfo With {.Name = "Sweden", .Code = "SE", .PhoneCode = "46"},
        New CountryInfo With {.Name = "Switzerland", .Code = "CH", .PhoneCode = "41"},
        New CountryInfo With {.Name = "Syria", .Code = "SI", .PhoneCode = "963"},
        New CountryInfo With {.Name = "Taiwan", .Code = "TW", .PhoneCode = "886"},
        New CountryInfo With {.Name = "Tajikstan", .Code = "TJ", .PhoneCode = "7"},
        New CountryInfo With {.Name = "Thailand", .Code = "TH", .PhoneCode = "66"},
        New CountryInfo With {.Name = "Togo", .Code = "TG", .PhoneCode = "228"},
        New CountryInfo With {.Name = "Tonga", .Code = "TO", .PhoneCode = "676"},
        New CountryInfo With {.Name = "Trinidad & Tobago", .Code = "TT", .PhoneCode = "1868"},
        New CountryInfo With {.Name = "Tunisia", .Code = "TN", .PhoneCode = "216"},
        New CountryInfo With {.Name = "Turkey", .Code = "TR", .PhoneCode = "90"},
        New CountryInfo With {.Name = "Turkmenistan", .Code = "TM", .PhoneCode = "7"},
        New CountryInfo With {.Name = "Turkmenistan", .Code = "TM", .PhoneCode = "993"},
        New CountryInfo With {.Name = "Turks & Caicos Islands", .Code = "TC", .PhoneCode = "1649"},
        New CountryInfo With {.Name = "Tuvalu", .Code = "TV", .PhoneCode = "688"},
        New CountryInfo With {.Name = "Uganda", .Code = "UG", .PhoneCode = "256"},
        New CountryInfo With {.Name = "Ukraine", .Code = "UA", .PhoneCode = "380"},
        New CountryInfo With {.Name = "United Arab Emirates", .Code = "AE", .PhoneCode = "971"},
        New CountryInfo With {.Name = "Uruguay", .Code = "UY", .PhoneCode = "598"},
        New CountryInfo With {.Name = "Uzbekistan", .Code = "UZ", .PhoneCode = "7"},
        New CountryInfo With {.Name = "Vanuatu", .Code = "VU", .PhoneCode = "678"},
        New CountryInfo With {.Name = "Vatican City", .Code = "VA", .PhoneCode = "379"},
        New CountryInfo With {.Name = "Venezuela", .Code = "VE", .PhoneCode = "58"},
        New CountryInfo With {.Name = "Vietnam", .Code = "VN", .PhoneCode = "84"},
        New CountryInfo With {.Name = "Virgin Islands - British", .Code = "VG", .PhoneCode = "1284"},
        New CountryInfo With {.Name = "Virgin Islands - US", .Code = "VI", .PhoneCode = "1340"},
        New CountryInfo With {.Name = "Wallis & Futuna", .Code = "WF", .PhoneCode = "681"},
        New CountryInfo With {.Name = "Yemen (North)", .Code = "YE", .PhoneCode = "969"},
        New CountryInfo With {.Name = "Yemen (South)", .Code = "YE", .PhoneCode = "967"},
        New CountryInfo With {.Name = "Zambia", .Code = "ZM", .PhoneCode = "260"},
        New CountryInfo With {.Name = "Zimbabwe", .Code = "ZW", .PhoneCode = "263"}
    }

        ' Ordena e adiciona os países
        For Each country In otherCountries.OrderBy(Function(c) c.Name)
            comboBox.Items.Add(country)
        Next

        comboBox.DisplayMember = "Name"
        comboBox.ValueMember = "Code"
        comboBox.EndUpdate()
    End Sub

    ' Método para obter o país selecionado
    Public Function GetSelectedCountry(comboBox As ComboBox) As CountryInfo
        If comboBox.SelectedItem IsNot Nothing AndAlso TypeOf comboBox.SelectedItem Is CountryInfo Then
            Return DirectCast(comboBox.SelectedItem, CountryInfo)
        End If
        Return Nothing
    End Function

    ' Método para configurar o país atual no ComboBox
    Public Sub SetSelectedCountry(comboBox As ComboBox, countryCode As String)
        For i As Integer = 0 To comboBox.Items.Count - 1
            If TypeOf comboBox.Items(i) Is CountryInfo Then
                Dim country As CountryInfo = DirectCast(comboBox.Items(i), CountryInfo)
                If country.Code = countryCode Then
                    comboBox.SelectedIndex = i
                    Exit For
                End If
            End If
        Next
    End Sub

    ' Carrega os dados da máquina nos controles
    ' Carrega os dados da máquina nos controles (atualizado)
    Public Sub LoadMachineToControls(machineName As TextBox, serialNumber As TextBox,
                       location As TextBox, countryCombo As ComboBox,
                       city As TextBox, responsible As TextBox,
                       notes As TextBox, scriptNumber As NumericUpDown,
                       cmbBeOuTee As ComboBox, nudBotEnable As NumericUpDown)
        Dim machineData = GetMachine()

        If machineData IsNot Nothing Then
            machineName.Text = machineData("MachineName").ToString()
            serialNumber.Text = machineData("SerialNumber").ToString()
            location.Text = machineData("Location").ToString()

            If Not String.IsNullOrEmpty(machineData("CountryCode").ToString()) Then
                SetSelectedCountry(countryCombo, machineData("CountryCode").ToString())
            End If

            city.Text = machineData("City").ToString()
            responsible.Text = machineData("Responsible").ToString()
            notes.Text = machineData("Notes").ToString()
            scriptNumber.Value = Convert.ToDecimal(machineData("ScriptNumber"))

            ' Obtém o valor salvo no banco (ou "A" se for nulo)
            Dim savedBeOuTee As String = If(machineData.IsNull("BeOuTee"), "A", machineData("BeOuTee").ToString())

            ' Carrega as opções já selecionando o valor correto
            LoadBeOuTeeOptions(cmbBeOuTee, savedBeOuTee)

            ' DEBUG: Verifique se está selecionando corretamente
            nudBotEnable.Value = Convert.ToDecimal(If(machineData.IsNull("nudBotEnable"), 0, machineData("nudBotEnable")))
        End If
    End Sub

    ' Salva os dados dos controles para a máquina (ignorando campos protegidos)
    Public Sub SaveMachineFromControls(machineName As TextBox, serialNumber As TextBox,
                                 location As TextBox, countryCombo As ComboBox,
                                 city As TextBox, responsible As TextBox,
                                 notes As TextBox, scriptNumber As NumericUpDown,
                                 cmbBeOuTee As ComboBox, nudBotEnable As NumericUpDown)  ' Novo parâmetro adicionado
        Try
            ' Valida o campo BeOuTee
            If Not IsValidBeOuTee(cmbBeOuTee.Text) Then
                Throw New ArgumentException("O campo BeOuTee só pode conter letras de A a Z")
            End If

            Dim selectedCountry = GetSelectedCountry(countryCombo)
            Dim countryName As String = If(selectedCountry?.Name, "Não especificado")
            Dim countryCode As String = If(selectedCountry?.Code, "XX")

            Using conn As New SQLiteConnection(ConnectionString)
                conn.Open()

                ' Primeiro valida os campos protegidos
                ValidateProtectedFields(conn)

                ' Atualiza apenas os campos não protegidos
                Dim updateSql As String = "UPDATE Machine SET " &
                                    "Location = @Location, " &
                                    "Country = @Country, " &
                                    "CountryCode = @CountryCode, " &
                                    "City = @City, " &
                                    "Notes = @Notes, " &
                                    "ScriptNumber = @ScriptNumber, " &
                                    "BeOuTee = @BeOuTee, " &
                                    "nudBotEnable = @nudBotEnable, " &  ' Novo campo adicionado
                                    "LastUpdate = @LastUpdate " &
                                    "WHERE Id = @Id"

                Using cmd As New SQLiteCommand(updateSql, conn)
                    cmd.Parameters.AddWithValue("@Id", MACHINE_ID)
                    cmd.Parameters.AddWithValue("@Location", location.Text)
                    cmd.Parameters.AddWithValue("@Country", countryName)
                    cmd.Parameters.AddWithValue("@CountryCode", countryCode)
                    cmd.Parameters.AddWithValue("@City", city.Text)
                    cmd.Parameters.AddWithValue("@Notes", notes.Text)
                    cmd.Parameters.AddWithValue("@ScriptNumber", scriptNumber.Value)
                    cmd.Parameters.AddWithValue("@BeOuTee", cmbBeOuTee.Text.ToUpper()) ' Garante que está em maiúsculas
                    cmd.Parameters.AddWithValue("@nudBotEnable", Convert.ToInt32(nudBotEnable.Value))
                    cmd.Parameters.AddWithValue("@LastUpdate", DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"))

                    cmd.ExecuteNonQuery()
                End Using
            End Using
        Catch ex As Exception
            Throw New Exception("Erro ao salvar configurações da máquina: " & ex.Message, ex)
        End Try
    End Sub

    ' Obtém os dados da máquina
    Private Function GetMachine(Optional conn As SQLiteConnection = Nothing) As DataRow
        Dim closeConnection As Boolean = False

        If conn Is Nothing Then
            conn = New SQLiteConnection(ConnectionString)
            conn.Open()
            closeConnection = True
        End If

        Try
            Dim selectSql As String = "SELECT * FROM Machine WHERE Id = @Id"
            Using cmd As New SQLiteCommand(selectSql, conn)
                cmd.Parameters.AddWithValue("@Id", MACHINE_ID)

                Using adapter As New SQLiteDataAdapter(cmd)
                    Dim dt As New DataTable()
                    adapter.Fill(dt)

                    If dt.Rows.Count > 0 Then
                        Return dt.Rows(0)
                    End If
                End Using
            End Using
        Finally
            If closeConnection Then
                conn.Close()
            End If
        End Try

        Return Nothing
    End Function

    ' Obtém informações completas da máquina
    Public Function GetMachineInfo() As DataTable
        Dim dt As New DataTable()

        Using conn As New SQLiteConnection(ConnectionString)
            conn.Open()

            Dim selectSql As String = "SELECT * FROM Machine WHERE Id = @Id"
            Using cmd As New SQLiteCommand(selectSql, conn)
                cmd.Parameters.AddWithValue("@Id", MACHINE_ID)

                Using adapter As New SQLiteDataAdapter(cmd)
                    adapter.Fill(dt)
                End Using
            End Using
        End Using

        Return dt
    End Function





End Class