<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>';
    exit();
}

$user_email = $_SESSION['code_email'];

// Verifica se o usuário já tem uma conta bancária registrada
$sql = "SELECT bank_name, branch, account_type, account_number, account_holder_name, account_holder_cpf FROM granna80_bdlinks.granna_bank_accounts WHERE granna_user_email = '$user_email'";
$result = $conn->query($sql);

$existing_bank_account = false;

if ($result->num_rows > 0) {
    $existing_bank_account = true;
    $row = $result->fetch_assoc();
    $bank_name = $row['bank_name'];
    $branch = $row['branch'];
    $account_type = $row['account_type'];
    $account_number = $row['account_number'];
    $account_holder_name = $row['account_holder_name'];
    $account_holder_cpf = $row['account_holder_cpf'];
} else {
    // Default values for a new entry
    $bank_name = '';
    $branch = '';
    $account_type = '';
    $account_number = '';
    $account_holder_name = '';
    $account_holder_cpf = '';
}

// Lida com o envio do formulário para adicionar ou atualizar a conta bancária
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bank_name = $conn->real_escape_string($_POST['bank_name']);
    $branch = $conn->real_escape_string($_POST['branch']);
    $account_type = $conn->real_escape_string($_POST['account_type']);
    $account_number = $conn->real_escape_string($_POST['account_number']);
    $account_holder_name = $conn->real_escape_string($_POST['account_holder_name']);
    $account_holder_cpf = $conn->real_escape_string($_POST['account_holder_cpf']);

    if ($existing_bank_account) {
        // Atualiza a conta bancária existente
        $sql = "UPDATE granna80_bdlinks.granna_bank_accounts SET bank_name = '$bank_name', branch = '$branch', account_type = '$account_type', account_number = '$account_number', account_holder_name = '$account_holder_name', account_holder_cpf = '$account_holder_cpf' WHERE granna_user_email = '$user_email'";
        if ($conn->query($sql) === TRUE) {
            echo "Bank account updated successfully!";
            echo '<script>window.location.href = "add_bank.php";</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Insere uma nova conta bancária
        $sql = "INSERT INTO granna80_bdlinks.granna_bank_accounts (bank_name, branch, account_type, account_number, account_holder_name, account_holder_cpf, granna_user_email) VALUES ('$bank_name', '$branch', '$account_type', '$account_number', '$account_holder_name', '$account_holder_cpf', '$user_email')";
        if ($conn->query($sql) === TRUE) {
            echo "Bank account added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add or Edit Brazilian Bank Account</title>
</head>
<body>

<h2><?php echo $existing_bank_account ? 'Edit Bank Account' : 'Add Bank Account'; ?></h2>
<form method="POST" action="">
    <label for="bank_name">Bank:</label><br>
    <select id="bank_name" name="bank_name" required><br><br>
    <option value="">Select a Bank</option>
    <option value="001" <?php echo $bank_name === '001' ? 'selected' : ''; ?>>Banco do Brasil</option>
    <option value="003" <?php echo $bank_name === '003' ? 'selected' : ''; ?>>Banco da Amazônia</option>
    <option value="004" <?php echo $bank_name === '004' ? 'selected' : ''; ?>>Banco do Nordeste</option>
    <option value="021" <?php echo $bank_name === '021' ? 'selected' : ''; ?>>Banestes</option>
    <option value="025" <?php echo $bank_name === '025' ? 'selected' : ''; ?>>Banco Alfa</option>
    <option value="027" <?php echo $bank_name === '027' ? 'selected' : ''; ?>>Besc</option>
    <option value="029" <?php echo $bank_name === '029' ? 'selected' : ''; ?>>Banerj</option>
    <option value="031" <?php echo $bank_name === '031' ? 'selected' : ''; ?>>Banco Beg</option>
    <option value="033" <?php echo $bank_name === '033' ? 'selected' : ''; ?>>Banco Santander Banespa</option>
    <option value="036" <?php echo $bank_name === '036' ? 'selected' : ''; ?>>Banco Bem</option>
    <option value="037" <?php echo $bank_name === '037' ? 'selected' : ''; ?>>Banpará</option>
    <option value="038" <?php echo $bank_name === '038' ? 'selected' : ''; ?>>Banestado</option>
    <option value="039" <?php echo $bank_name === '039' ? 'selected' : ''; ?>>BEP</option>
    <option value="040" <?php echo $bank_name === '040' ? 'selected' : ''; ?>>Banco Cargill</option>
    <option value="041" <?php echo $bank_name === '041' ? 'selected' : ''; ?>>Banrisul</option>
    <option value="044" <?php echo $bank_name === '044' ? 'selected' : ''; ?>>BVA</option>
    <option value="045" <?php echo $bank_name === '045' ? 'selected' : ''; ?>>Banco Opportunity</option>
    <option value="047" <?php echo $bank_name === '047' ? 'selected' : ''; ?>>Banese</option>
    <option value="062" <?php echo $bank_name === '062' ? 'selected' : ''; ?>>Hipercard</option>
    <option value="063" <?php echo $bank_name === '063' ? 'selected' : ''; ?>>Ibibank</option>
    <option value="065" <?php echo $bank_name === '065' ? 'selected' : ''; ?>>Lemon Bank</option>
    <option value="066" <?php echo $bank_name === '066' ? 'selected' : ''; ?>>Banco Morgan Stanley Dean Witter</option>
    <option value="069" <?php echo $bank_name === '069' ? 'selected' : ''; ?>>BPN Brasil</option>
    <option value="070" <?php echo $bank_name === '070' ? 'selected' : ''; ?>>Banco de Brasília – BRB</option>
    <option value="072" <?php echo $bank_name === '072' ? 'selected' : ''; ?>>Banco Rural</option>
    <option value="073" <?php echo $bank_name === '073' ? 'selected' : ''; ?>>Banco Popular</option>
    <option value="074" <?php echo $bank_name === '074' ? 'selected' : ''; ?>>Banco J. Safra</option>
    <option value="075" <?php echo $bank_name === '075' ? 'selected' : ''; ?>>Banco CR2</option>
    <option value="076" <?php echo $bank_name === '076' ? 'selected' : ''; ?>>Banco KDB</option>
    <option value="096" <?php echo $bank_name === '096' ? 'selected' : ''; ?>>Banco BMF</option>
    <option value="104" <?php echo $bank_name === '104' ? 'selected' : ''; ?>>Caixa Econômica Federal</option>
    <option value="107" <?php echo $bank_name === '107' ? 'selected' : ''; ?>>Banco BBM</option>
    <option value="116" <?php echo $bank_name === '116' ? 'selected' : ''; ?>>Banco Único</option>
    <option value="151" <?php echo $bank_name === '151' ? 'selected' : ''; ?>>Nossa Caixa</option>
    <option value="175" <?php echo $bank_name === '175' ? 'selected' : ''; ?>>Banco Finasa</option>
    <option value="184" <?php echo $bank_name === '184' ? 'selected' : ''; ?>>Banco Itaú BBA</option>
    <option value="204" <?php echo $bank_name === '204' ? 'selected' : ''; ?>>American Express Bank</option>
    <option value="208" <?php echo $bank_name === '208' ? 'selected' : ''; ?>>Banco Pactual</option>
    <option value="212" <?php echo $bank_name === '212' ? 'selected' : ''; ?>>Banco Matone</option>
    <option value="213" <?php echo $bank_name === '213' ? 'selected' : ''; ?>>Banco Arbi</option>
    <option value="214" <?php echo $bank_name === '214' ? 'selected' : ''; ?>>Banco Dibens</option>
    <option value="217" <?php echo $bank_name === '217' ? 'selected' : ''; ?>>Banco Joh Deere</option>
    <option value="218" <?php echo $bank_name === '218' ? 'selected' : ''; ?>>Banco Bonsucesso</option>
    <option value="222" <?php echo $bank_name === '222' ? 'selected' : ''; ?>>Banco Calyon Brasil</option>
    <option value="224" <?php echo $bank_name === '224' ? 'selected' : ''; ?>>Banco Fibra</option>
    <option value="225" <?php echo $bank_name === '225' ? 'selected' : ''; ?>>Banco Brascan</option>
    <option value="229" <?php echo $bank_name === '229' ? 'selected' : ''; ?>>Banco Cruzeiro</option>
    <option value="230" <?php echo $bank_name === '230' ? 'selected' : ''; ?>>Unicard</option>
    <option value="233" <?php echo $bank_name === '233' ? 'selected' : ''; ?>>Banco GE Capital</option>
    <option value="237" <?php echo $bank_name === '237' ? 'selected' : ''; ?>>Bradesco</option>
    <option value="241" <?php echo $bank_name === '241' ? 'selected' : ''; ?>>Banco Clássico</option>
    <option value="243" <?php echo $bank_name === '243' ? 'selected' : ''; ?>>Banco Stock Máxima</option>
    <option value="246" <?php echo $bank_name === '246' ? 'selected' : ''; ?>>Banco ABC Brasil</option>
    <option value="248" <?php echo $bank_name === '248' ? 'selected' : ''; ?>>Banco Boavista Interatlântico</option>
    <option value="249" <?php echo $bank_name === '249' ? 'selected' : ''; ?>>Investcred Unibanco</option>
    <option value="250" <?php echo $bank_name === '250' ? 'selected' : ''; ?>>Banco Schahin</option>
    <option value="252" <?php echo $bank_name === '252' ? 'selected' : ''; ?>>Fininvest</option>
    <option value="254" <?php echo $bank_name === '254' ? 'selected' : ''; ?>>Paraná Banco</option>
    <option value="263" <?php echo $bank_name === '263' ? 'selected' : ''; ?>>Banco Cacique</option>
    <option value="265" <?php echo $bank_name === '265' ? 'selected' : ''; ?>>Banco Fator</option>
    <option value="266" <?php echo $bank_name === '266' ? 'selected' : ''; ?>>Banco Cédula</option>
    <option value="300" <?php echo $bank_name === '300' ? 'selected' : ''; ?>>Banco de la Nación Argentina</option>
    <option value="318" <?php echo $bank_name === '318' ? 'selected' : ''; ?>>Banco BMG</option>
    <option value="320" <?php echo $bank_name === '320' ? 'selected' : ''; ?>>Banco Industrial e Comercial</option>
    <option value="356" <?php echo $bank_name === '356' ? 'selected' : ''; ?>>ABN Amro Real</option>
    <option value="341" <?php echo $bank_name === '341' ? 'selected' : ''; ?>>Itaú</option>
    <option value="347" <?php echo $bank_name === '347' ? 'selected' : ''; ?>>Sudameris</option>
    <option value="351" <?php echo $bank_name === '351' ? 'selected' : ''; ?>>Banco Santander</option>
    <option value="353" <?php echo $bank_name === '353' ? 'selected' : ''; ?>>Banco Santander Brasil</option>
    <option value="366" <?php echo $bank_name === '366' ? 'selected' : ''; ?>>Banco Societe Generale Brasil</option>
    <option value="370" <?php echo $bank_name === '370' ? 'selected' : ''; ?>>Banco WestLB</option>
    <option value="376" <?php echo $bank_name === '376' ? 'selected' : ''; ?>>JP Morgan</option>
    <option value="389" <?php echo $bank_name === '389' ? 'selected' : ''; ?>>Banco Mercantil do Brasil</option>
    <option value="394" <?php echo $bank_name === '394' ? 'selected' : ''; ?>>Banco Mercantil de Crédito</option>
    <option value="399" <?php echo $bank_name === '399' ? 'selected' : ''; ?>>HSBC</option>
    <option value="409" <?php echo $bank_name === '409' ? 'selected' : ''; ?>>Unibanco</option>
    <option value="412" <?php echo $bank_name === '412' ? 'selected' : ''; ?>>Banco Capital</option>
    <option value="422" <?php echo $bank_name === '422' ? 'selected' : ''; ?>>Banco Safra</option>
    <option value="453" <?php echo $bank_name === '453' ? 'selected' : ''; ?>>Banco Rural</option>
    <option value="456" <?php echo $bank_name === '456' ? 'selected' : ''; ?>>Banco Tokyo Mitsubishi UFJ</option>
    <option value="464" <?php echo $bank_name === '464' ? 'selected' : ''; ?>>Banco Sumitomo Mitsui Brasileiro</option>
    <option value="477" <?php echo $bank_name === '477' ? 'selected' : ''; ?>>Citibank</option>
    <option value="479" <?php echo $bank_name === '479' ? 'selected' : ''; ?>>Itaubank (antigo Bank Boston)</option>
    <option value="487" <?php echo $bank_name === '487' ? 'selected' : ''; ?>>Deutsche Bank</option>
    <option value="488" <?php echo $bank_name === '488' ? 'selected' : ''; ?>>Banco Morgan Guaranty</option>
    <option value="492" <?php echo $bank_name === '492' ? 'selected' : ''; ?>>Banco NMB Postbank</option>
    <option value="494" <?php echo $bank_name === '494' ? 'selected' : ''; ?>>Banco la República Oriental del Uruguay</option>
    <option value="495" <?php echo $bank_name === '495' ? 'selected' : ''; ?>>Banco La Provincia de Buenos Aires</option>
    <option value="505" <?php echo $bank_name === '505' ? 'selected' : ''; ?>>Banco Credit Suisse</option>
    <option value="600" <?php echo $bank_name === '600' ? 'selected' : ''; ?>>Banco Luso Brasileiro</option>
    <option value="604" <?php echo $bank_name === '604' ? 'selected' : ''; ?>>Banco Industrial</option>
    <option value="610" <?php echo $bank_name === '610' ? 'selected' : ''; ?>>Banco VR</option>
    <option value="611" <?php echo $bank_name === '611' ? 'selected' : ''; ?>>Banco Paulista</option>
    <option value="612" <?php echo $bank_name === '612' ? 'selected' : ''; ?>>Banco Guanabara</option>
    <option value="613" <?php echo $bank_name === '613' ? 'selected' : ''; ?>>Banco Pecunia</option>
    <option value="623" <?php echo $bank_name === '623' ? 'selected' : ''; ?>>Banco Panamericano</option>
    <option value="626" <?php echo $bank_name === '626' ? 'selected' : ''; ?>>Banco Ficsa</option>
    <option value="630" <?php echo $bank_name === '630' ? 'selected' : ''; ?>>Banco Intercap</option>
    <option value="633" <?php echo $bank_name === '633' ? 'selected' : ''; ?>>Banco Rendimento</option>
    <option value="634" <?php echo $bank_name === '634' ? 'selected' : ''; ?>>Banco Triângulo</option>
    <option value="637" <?php echo $bank_name === '637' ? 'selected' : ''; ?>>Banco Sofisa</option>
    <option value="638" <?php echo $bank_name === '638' ? 'selected' : ''; ?>>Banco Prosper</option>
    <option value="643" <?php echo $bank_name === '643' ? 'selected' : ''; ?>>Banco Pine</option>
    <option value="652" <?php echo $bank_name === '652' ? 'selected' : ''; ?>>Itaú Holding Financeira</option>
    <option value="653" <?php echo $bank_name === '653' ? 'selected' : ''; ?>>Banco Indusval</option>
    <option value="654" <?php echo $bank_name === '654' ? 'selected' : ''; ?>>Banco A.J. Renner</option>
    <option value="655" <?php echo $bank_name === '655' ? 'selected' : ''; ?>>Banco Votorantim</option>
    <option value="707" <?php echo $bank_name === '707' ? 'selected' : ''; ?>>Banco Daycoval</option>
    <option value="719" <?php echo $bank_name === '719' ? 'selected' : ''; ?>>Banif</option>
    <option value="721" <?php echo $bank_name === '721' ? 'selected' : ''; ?>>Banco Credibel</option>
    <option value="734" <?php echo $bank_name === '734' ? 'selected' : ''; ?>>Banco Gerdau</option>
    <option value="735" <?php echo $bank_name === '735' ? 'selected' : ''; ?>>Banco Pottencial</option>
    <option value="738" <?php echo $bank_name === '738' ? 'selected' : ''; ?>>Banco Morada</option>
    <option value="739" <?php echo $bank_name === '739' ? 'selected' : ''; ?>>Banco Galvão de Negócios</option>
    <option value="740" <?php echo $bank_name === '740' ? 'selected' : ''; ?>>Banco Barclays</option>
    <option value="741" <?php echo $bank_name === '741' ? 'selected' : ''; ?>>BRP</option>
    <option value="743" <?php echo $bank_name === '743' ? 'selected' : ''; ?>>Banco Semear</option>
    <option value="745" <?php echo $bank_name === '745' ? 'selected' : ''; ?>>Banco Citibank</option>
    <option value="746" <?php echo $bank_name === '746' ? 'selected' : ''; ?>>Banco Modal</option>
    <option value="747" <?php echo $bank_name === '747' ? 'selected' : ''; ?>>Banco Rabobank International</option>
    <option value="748" <?php echo $bank_name === '748' ? 'selected' : ''; ?>>Banco Cooperativo Sicredi</option>
    <option value="749" <?php echo $bank_name === '749' ? 'selected' : ''; ?>>Banco Simples</option>
    <option value="751" <?php echo $bank_name === '751' ? 'selected' : ''; ?>>Dresdner Bank</option>
    <option value="752" <?php echo $bank_name === '752' ? 'selected' : ''; ?>>BNP Paribas</option>
    <option value="753" <?php echo $bank_name === '753' ? 'selected' : ''; ?>>Banco Comercial Uruguai</option>
    <option value="755" <?php echo $bank_name === '755' ? 'selected' : ''; ?>>Banco Merrill Lynch</option>
    <option value="756" <?php echo $bank_name === '756' ? 'selected' : ''; ?>>Banco Cooperativo do Brasil</option>
    <option value="757" <?php echo $bank_name === '757' ? 'selected' : ''; ?>>KEB</option>
</select><br><br>


    <label for="branch">Branch:</label><br>
    <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($branch); ?>" required><br><br>

    <label for="account_type">Account Type:</label><br>
    <select id="account_type" name="account_type" required>
        <option value="saving" <?php echo $account_type === 'saving' ? 'selected' : ''; ?>>Saving</option>
        <option value="current" <?php echo $account_type === 'current' ? 'selected' : ''; ?>>Current</option>
    </select><br><br>

    <label for="account_number">Account Number:</label><br>
    <input type="text" id="account_number" name="account_number" value="<?php echo htmlspecialchars($account_number); ?>" required><br><br>

    <label for="account_holder_name">Account Holder Name:</label><br>
    <input type="text" id="account_holder_name" name="account_holder_name" value="<?php echo htmlspecialchars($account_holder_name); ?>" required><br><br>

    <label for="account_holder_cpf">Account Holder CPF:</label><br>
    <input type="text" id="account_holder_cpf" name="account_holder_cpf" value="<?php echo htmlspecialchars($account_holder_cpf); ?>" required maxlength="11"><br><br>

    <input type="submit" value="<?php echo $existing_bank_account ? 'Update Bank Account' : 'Add Bank Account'; ?>">
    <a href="https://www.granna.ie/panel/">[ Back ]</a>
</form>

</body>
</html>
