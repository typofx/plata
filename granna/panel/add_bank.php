<?php
session_start();
include 'conexao.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>';
    exit();
}

$user_email = $_SESSION['code_email'];

// Check current number of bank accounts for the logged-in user
$sql = "SELECT COUNT(*) AS account_count FROM granna80_bdlinks.granna_bank_accounts WHERE granna_user_email = '$user_email'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$account_count = $row['account_count'];
$account_limit = 10; // Set maximum account limit per user

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM granna80_bdlinks.granna_bank_accounts WHERE id = '$delete_id' AND granna_user_email = '$user_email'";
    $conn->query($sql);
    echo '<script>window.location.href = "add_bank.php";</script>';

    exit();
}

// Handle edit request
$edit_mode = false;
if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit_id']);
    $sql = "SELECT * FROM granna80_bdlinks.granna_bank_accounts WHERE id = '$edit_id' AND granna_user_email = '$user_email'";
    $result = $conn->query($sql);
    $edit_account = $result->fetch_assoc();
}

// Handle form submission to add or update a bank account
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $conn->real_escape_string($_POST['label']);
    $bank_name = $conn->real_escape_string($_POST['bank_name']);
    $branch = $conn->real_escape_string($_POST['branch']);
    $account_type = $conn->real_escape_string($_POST['account_type']);
    $account_number = $conn->real_escape_string($_POST['account_number']);
    $account_holder_name = $conn->real_escape_string($_POST['account_holder_name']);
    $account_holder_cpf = $conn->real_escape_string($_POST['account_holder_cpf']);

    if ($edit_mode) {
        // Update existing account
        $sql = "UPDATE granna80_bdlinks.granna_bank_accounts SET label='$label', bank_name='$bank_name', branch='$branch', account_type='$account_type', account_number='$account_number', account_holder_name='$account_holder_name', account_holder_cpf='$account_holder_cpf' WHERE id='$edit_id' AND granna_user_email = '$user_email'";
        $conn->query($sql);
        echo '<script>window.location.href = "add_bank.php";</script>';
        exit();
    } else {
        if ($account_count < $account_limit) {
            // Insert new bank account
            $sql = "INSERT INTO granna80_bdlinks.granna_bank_accounts (label, bank_name, branch, account_type, account_number, account_holder_name, account_holder_cpf, granna_user_email) VALUES ('$label', '$bank_name', '$branch', '$account_type', '$account_number', '$account_holder_name', '$account_holder_cpf', '$user_email')";
            $conn->query($sql);
            echo '<script>window.location.href = "add_bank.php";</script>';
            exit();
        } else {
            echo "You have reached the maximum limit of 10 bank accounts.";
        }
    }
}

// Fetch all bank accounts for the logged-in user
$sql = "SELECT id, label, bank_name, branch, account_type, account_number, account_holder_name, account_holder_cpf FROM granna80_bdlinks.granna_bank_accounts WHERE granna_user_email = '$user_email'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Bank Accounts</title>
    <style>
        table,
        th,
        td {
            text-align: center;
        }

        .highlight {
            background-color: yellow;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#accountsTable').DataTable();
        });
    </script>
</head>

<body>


   



    <h2><?php echo $edit_mode ? 'Edit Bank Account' : 'Add New Bank Account'; ?></h2>




    <!-- Form for adding/editing a bank account -->
    <?php if ($account_count < $account_limit || $edit_mode): ?>
        <form method="POST" action="">
            <label for="label">Label:</label><br>
            <input type="text" id="label" name="label" value="<?php echo $edit_mode ? htmlspecialchars($edit_account['label']) : ''; ?>" required><br><br>

            <label for="bank_name">Bank:</label><br>
            <select id="bank_name" name="bank_name" required>
                <option value="">Select a Bank</option>
                <option value="001 - Banco do Brasil" <?php echo $bank_name === '001 - Banco do Brasil' ? 'selected' : ''; ?>>001 - Banco do Brasil</option>
                <option value="003 - Banco da Amazônia" <?php echo $bank_name === '003 - Banco da Amazônia' ? 'selected' : ''; ?>>003 - Banco da Amazônia</option>
                <option value="004 - Banco do Nordeste" <?php echo $bank_name === '004 - Banco do Nordeste' ? 'selected' : ''; ?>>004 - Banco do Nordeste</option>
                <option value="021 - Banestes" <?php echo $bank_name === '021 - Banestes' ? 'selected' : ''; ?>>021 - Banestes</option>
                <option value="025 - Banco Alfa" <?php echo $bank_name === '025 - Banco Alfa' ? 'selected' : ''; ?>>025 - Banco Alfa</option>
                <option value="027 - Besc" <?php echo $bank_name === '027 - Besc' ? 'selected' : ''; ?>>027 - Besc</option>
                <option value="029 - Banerj" <?php echo $bank_name === '029 - Banerj' ? 'selected' : ''; ?>>029 - Banerj</option>
                <option value="031 - Banco Beg" <?php echo $bank_name === '031 - Banco Beg' ? 'selected' : ''; ?>>031 - Banco Beg</option>
                <option value="033 - Banco Santander Banespa" <?php echo $bank_name === '033 - Banco Santander Banespa' ? 'selected' : ''; ?>>033 - Banco Santander Banespa</option>
                <option value="036 - Banco Bem" <?php echo $bank_name === '036 - Banco Bem' ? 'selected' : ''; ?>>036 - Banco Bem</option>
                <option value="037 - Banpará" <?php echo $bank_name === '037 - Banpará' ? 'selected' : ''; ?>>037 - Banpará</option>
                <option value="038 - Banestado" <?php echo $bank_name === '038 - Banestado' ? 'selected' : ''; ?>>038 - Banestado</option>
                <option value="039 - BEP" <?php echo $bank_name === '039 - BEP' ? 'selected' : ''; ?>>039 - BEP</option>
                <option value="040 - Banco Cargill" <?php echo $bank_name === '040 - Banco Cargill' ? 'selected' : ''; ?>>040 - Banco Cargill</option>
                <option value="041 - Banrisul" <?php echo $bank_name === '041 - Banrisul' ? 'selected' : ''; ?>>041 - Banrisul</option>
                <option value="044 - BVA" <?php echo $bank_name === '044 - BVA' ? 'selected' : ''; ?>>044 - BVA</option>
                <option value="045 - Banco Opportunity" <?php echo $bank_name === '045 - Banco Opportunity' ? 'selected' : ''; ?>>045 - Banco Opportunity</option>
                <option value="047 - Banese" <?php echo $bank_name === '047 - Banese' ? 'selected' : ''; ?>>047 - Banese</option>
                <option value="062 - Hipercard" <?php echo $bank_name === '062 - Hipercard' ? 'selected' : ''; ?>>062 - Hipercard</option>
                <option value="063 - Ibibank" <?php echo $bank_name === '063 - Ibibank' ? 'selected' : ''; ?>>063 - Ibibank</option>
                <option value="065 - Lemon Bank" <?php echo $bank_name === '065 - Lemon Bank' ? 'selected' : ''; ?>>065 - Lemon Bank</option>
                <option value="066 - Banco Morgan Stanley Dean Witter" <?php echo $bank_name === '066 - Banco Morgan Stanley Dean Witter' ? 'selected' : ''; ?>>066 - Banco Morgan Stanley Dean Witter</option>
                <option value="069 - BPN Brasil" <?php echo $bank_name === '069 - BPN Brasil' ? 'selected' : ''; ?>>069 - BPN Brasil</option>
                <option value="070 - Banco de Brasília – BRB" <?php echo $bank_name === '070 - Banco de Brasília – BRB' ? 'selected' : ''; ?>>070 - Banco de Brasília – BRB</option>
                <option value="072 - Banco Rural" <?php echo $bank_name === '072 - Banco Rural' ? 'selected' : ''; ?>>072 - Banco Rural</option>
                <option value="073 - Banco Popular" <?php echo $bank_name === '073 - Banco Popular' ? 'selected' : ''; ?>>073 - Banco Popular</option>
                <option value="074 - Banco J. Safra" <?php echo $bank_name === '074 - Banco J. Safra' ? 'selected' : ''; ?>>074 - Banco J. Safra</option>
                <option value="075 - Banco CR2" <?php echo $bank_name === '075 - Banco CR2' ? 'selected' : ''; ?>>075 - Banco CR2</option>
                <option value="076 - Banco KDB" <?php echo $bank_name === '076 - Banco KDB' ? 'selected' : ''; ?>>076 - Banco KDB</option>
                <option value="096 - Banco BMF" <?php echo $bank_name === '096 - Banco BMF' ? 'selected' : ''; ?>>096 - Banco BMF</option>
                <option value="104 - Caixa Econômica Federal" <?php echo $bank_name === '104 - Caixa Econômica Federal' ? 'selected' : ''; ?>>104 - Caixa Econômica Federal</option>
                <option value="107 - Banco BBM" <?php echo $bank_name === '107 - Banco BBM' ? 'selected' : ''; ?>>107 - Banco BBM</option>
                <option value="116 - Banco Único" <?php echo $bank_name === '116 - Banco Único' ? 'selected' : ''; ?>>116 - Banco Único</option>
                <option value="151 - Nossa Caixa" <?php echo $bank_name === '151 - Nossa Caixa' ? 'selected' : ''; ?>>151 - Nossa Caixa</option>
                <option value="175 - Banco Finasa" <?php echo $bank_name === '175 - Banco Finasa' ? 'selected' : ''; ?>>175 - Banco Finasa</option>
                <option value="184 - Banco Itaú BBA" <?php echo $bank_name === '184 - Banco Itaú BBA' ? 'selected' : ''; ?>>184 - Banco Itaú BBA</option>
                <option value="204 - American Express Bank" <?php echo $bank_name === '204 - American Express Bank' ? 'selected' : ''; ?>>204 - American Express Bank</option>
                <option value="208 - Banco Pactual" <?php echo $bank_name === '208 - Banco Pactual' ? 'selected' : ''; ?>>208 - Banco Pactual</option>
                <option value="212 - Banco Matone" <?php echo $bank_name === '212 - Banco Matone' ? 'selected' : ''; ?>>212 - Banco Matone</option>
                <option value="213 - Banco Arbi" <?php echo $bank_name === '213 - Banco Arbi' ? 'selected' : ''; ?>>213 - Banco Arbi</option>
                <option value="214 - Banco Dibens" <?php echo $bank_name === '214 - Banco Dibens' ? 'selected' : ''; ?>>214 - Banco Dibens</option>
                <option value="217 - Banco Joh Deere" <?php echo $bank_name === '217 - Banco Joh Deere' ? 'selected' : ''; ?>>217 - Banco Joh Deere</option>
                <option value="218 - Banco Bonsucesso" <?php echo $bank_name === '218 - Banco Bonsucesso' ? 'selected' : ''; ?>>218 - Banco Bonsucesso</option>
                <option value="222 - Banco Calyon Brasil" <?php echo $bank_name === '222 - Banco Calyon Brasil' ? 'selected' : ''; ?>>222 - Banco Calyon Brasil</option>
                <option value="224 - Banco Fibra" <?php echo $bank_name === '224 - Banco Fibra' ? 'selected' : ''; ?>>224 - Banco Fibra</option>
                <option value="225 - Banco Brascan" <?php echo $bank_name === '225 - Banco Brascan' ? 'selected' : ''; ?>>225 - Banco Brascan</option>
                <option value="229 - Banco Cruzeiro" <?php echo $bank_name === '229 - Banco Cruzeiro' ? 'selected' : ''; ?>>229 - Banco Cruzeiro</option>
                <option value="230 - Unicard" <?php echo $bank_name === '230 - Unicard' ? 'selected' : ''; ?>>230 - Unicard</option>
                <option value="233 - Banco GE Capital" <?php echo $bank_name === '233 - Banco GE Capital' ? 'selected' : ''; ?>>233 - Banco GE Capital</option>
                <option value="237 - Bradesco" <?php echo $bank_name === '237 - Bradesco' ? 'selected' : ''; ?>>237 - Bradesco</option>
                <option value="241 - Banco Clássico" <?php echo $bank_name === '241 - Banco Clássico' ? 'selected' : ''; ?>>241 - Banco Clássico</option>
                <option value="243 - Banco Stock Máxima" <?php echo $bank_name === '243 - Banco Stock Máxima' ? 'selected' : ''; ?>>243 - Banco Stock Máxima</option>
                <option value="246 - Banco ABC Brasil" <?php echo $bank_name === '246 - Banco ABC Brasil' ? 'selected' : ''; ?>>246 - Banco ABC Brasil</option>

                <option value="248 - Banco Boavista Interatlântico" <?php echo $bank_name === '248 - Banco Boavista Interatlântico' ? 'selected' : ''; ?>>248 - Banco Boavista Interatlântico</option>
                <option value="249 - Investcred Unibanco" <?php echo $bank_name === '249 - Investcred Unibanco' ? 'selected' : ''; ?>>249 - Investcred Unibanco</option>
                <option value="250 - Banco Schahin" <?php echo $bank_name === '250 - Banco Schahin' ? 'selected' : ''; ?>>250 - Banco Schahin</option>
                <option value="252 - Fininvest" <?php echo $bank_name === '252 - Fininvest' ? 'selected' : ''; ?>>252 - Fininvest</option>
                <option value="254 - Paraná Banco" <?php echo $bank_name === '254 - Paraná Banco' ? 'selected' : ''; ?>>254 - Paraná Banco</option>
                <option value="263 - Banco Cacique" <?php echo $bank_name === '263 - Banco Cacique' ? 'selected' : ''; ?>>263 - Banco Cacique</option>
                <option value="265 - Banco Fator" <?php echo $bank_name === '265 - Banco Fator' ? 'selected' : ''; ?>>265 - Banco Fator</option>
                <option value="266 - Banco Cédula" <?php echo $bank_name === '266 - Banco Cédula' ? 'selected' : ''; ?>>266 - Banco Cédula</option>
                <option value="300 - Banco de la Nación Argentina" <?php echo $bank_name === '300 - Banco de la Nación Argentina' ? 'selected' : ''; ?>>300 - Banco de la Nación Argentina</option>
                <option value="318 - Banco BMG" <?php echo $bank_name === '318 - Banco BMG' ? 'selected' : ''; ?>>318 - Banco BMG</option>
                <option value="320 - Banco Industrial e Comercial" <?php echo $bank_name === '320 - Banco Industrial e Comercial' ? 'selected' : ''; ?>>320 - Banco Industrial e Comercial</option>
                <option value="356 - ABN Amro Real" <?php echo $bank_name === '356 - ABN Amro Real' ? 'selected' : ''; ?>>356 - ABN Amro Real</option>
                <option value="341 - Itaú" <?php echo $bank_name === '341 - Itaú' ? 'selected' : ''; ?>>341 - Itaú</option>
                <option value="347 - Sudameris" <?php echo $bank_name === '347 - Sudameris' ? 'selected' : ''; ?>>347 - Sudameris</option>
                <option value="351 - Banco Santander" <?php echo $bank_name === '351 - Banco Santander' ? 'selected' : ''; ?>>351 - Banco Santander</option>
                <option value="353 - Banco Santander Brasil" <?php echo $bank_name === '353 - Banco Santander Brasil' ? 'selected' : ''; ?>>353 - Banco Santander Brasil</option>
                <option value="366 - Banco Societe Generale Brasil" <?php echo $bank_name === '366 - Banco Societe Generale Brasil' ? 'selected' : ''; ?>>366 - Banco Societe Generale Brasil</option>
                <option value="370 - Banco WestLB" <?php echo $bank_name === '370 - Banco WestLB' ? 'selected' : ''; ?>>370 - Banco WestLB</option>
                <option value="376 - JP Morgan" <?php echo $bank_name === '376 - JP Morgan' ? 'selected' : ''; ?>>376 - JP Morgan</option>
                <option value="389 - Banco Mercantil do Brasil" <?php echo $bank_name === '389 - Banco Mercantil do Brasil' ? 'selected' : ''; ?>>389 - Banco Mercantil do Brasil</option>
                <option value="394 - Banco Mercantil de Crédito" <?php echo $bank_name === '394 - Banco Mercantil de Crédito' ? 'selected' : ''; ?>>394 - Banco Mercantil de Crédito</option>
                <option value="399 - HSBC" <?php echo $bank_name === '399 - HSBC' ? 'selected' : ''; ?>>399 - HSBC</option>
                <option value="409 - Unibanco" <?php echo $bank_name === '409 - Unibanco' ? 'selected' : ''; ?>>409 - Unibanco</option>
                <option value="412 - Banco Capital" <?php echo $bank_name === '412 - Banco Capital' ? 'selected' : ''; ?>>412 - Banco Capital</option>
                <option value="422 - Banco Safra" <?php echo $bank_name === '422 - Banco Safra' ? 'selected' : ''; ?>>422 - Banco Safra</option>
                <option value="453 - Banco Rural" <?php echo $bank_name === '453 - Banco Rural' ? 'selected' : ''; ?>>453 - Banco Rural</option>
                <option value="456 - Banco Tokyo Mitsubishi UFJ" <?php echo $bank_name === '456 - Banco Tokyo Mitsubishi UFJ' ? 'selected' : ''; ?>>456 - Banco Tokyo Mitsubishi UFJ</option>
                <option value="464 - Banco Sumitomo Mitsui Brasileiro" <?php echo $bank_name === '464 - Banco Sumitomo Mitsui Brasileiro' ? 'selected' : ''; ?>>464 - Banco Sumitomo Mitsui Brasileiro</option>
                <option value="477 - Citibank" <?php echo $bank_name === '477 - Citibank' ? 'selected' : ''; ?>>477 - Citibank</option>
                <option value="479 - Itaubank (antigo Bank Boston)" <?php echo $bank_name === '479 - Itaubank (antigo Bank Boston)' ? 'selected' : ''; ?>>479 - Itaubank (antigo Bank Boston)</option>
                <option value="487 - Deutsche Bank" <?php echo $bank_name === '487 - Deutsche Bank' ? 'selected' : ''; ?>>487 - Deutsche Bank</option>
                <option value="488 - Banco Morgan Guaranty" <?php echo $bank_name === '488 - Banco Morgan Guaranty' ? 'selected' : ''; ?>>488 - Banco Morgan Guaranty</option>
                <option value="492 - Banco NMB Postbank" <?php echo $bank_name === '492 - Banco NMB Postbank' ? 'selected' : ''; ?>>492 - Banco NMB Postbank</option>
                <option value="494 - Banco la República Oriental del Uruguay" <?php echo $bank_name === '494 - Banco la República Oriental del Uruguay' ? 'selected' : ''; ?>>494 - Banco la República Oriental del Uruguay</option>
                <option value="495 - Banco La Provincia de Buenos Aires" <?php echo $bank_name === '495 - Banco La Provincia de Buenos Aires' ? 'selected' : ''; ?>>495 - Banco La Provincia de Buenos Aires</option>
                <option value="505 - Banco Credit Suisse" <?php echo $bank_name === '505 - Banco Credit Suisse' ? 'selected' : ''; ?>>505 - Banco Credit Suisse</option>
                <option value="600 - Banco Luso Brasileiro" <?php echo $bank_name === '600 - Banco Luso Brasileiro' ? 'selected' : ''; ?>>600 - Banco Luso Brasileiro</option>
                <option value="604 - Banco Industrial" <?php echo $bank_name === '604 - Banco Industrial' ? 'selected' : ''; ?>>604 - Banco Industrial</option>
                <option value="610 - Banco VR" <?php echo $bank_name === '610 - Banco VR' ? 'selected' : ''; ?>>610 - Banco VR</option>
                <option value="611 - Banco Paulista" <?php echo $bank_name === '611 - Banco Paulista' ? 'selected' : ''; ?>>611 - Banco Paulista</option>
                <option value="612 - Banco Guanabara" <?php echo $bank_name === '612 - Banco Guanabara' ? 'selected' : ''; ?>>612 - Banco Guanabara</option>
                <option value="613 - Banco Pecunia" <?php echo $bank_name === '613 - Banco Pecunia' ? 'selected' : ''; ?>>613 - Banco Pecunia</option>
                <option value="623 - Banco Panamericano" <?php echo $bank_name === '623 - Banco Panamericano' ? 'selected' : ''; ?>>623 - Banco Panamericano</option>
                <option value="626 - Banco Ficsa" <?php echo $bank_name === '626 - Banco Ficsa' ? 'selected' : ''; ?>>626 - Banco Ficsa</option>
                <option value="630 - Banco Intercap" <?php echo $bank_name === '630 - Banco Intercap' ? 'selected' : ''; ?>>630 - Banco Intercap</option>
                <option value="633 - Banco Rendimento" <?php echo $bank_name === '633 - Banco Rendimento' ? 'selected' : ''; ?>>633 - Banco Rendimento</option>


                <option value="634 - Banco Triângulo" <?php echo $bank_name === '634 - Banco Triângulo' ? 'selected' : ''; ?>>634 - Banco Triângulo</option>
                <option value="637 - Banco Sofisa" <?php echo $bank_name === '637 - Banco Sofisa' ? 'selected' : ''; ?>>637 - Banco Sofisa</option>
                <option value="638 - Banco Prosper" <?php echo $bank_name === '638 - Banco Prosper' ? 'selected' : ''; ?>>638 - Banco Prosper</option>
                <option value="643 - Banco Pine" <?php echo $bank_name === '643 - Banco Pine' ? 'selected' : ''; ?>>643 - Banco Pine</option>
                <option value="652 - Itaú Holding Financeira" <?php echo $bank_name === '652 - Itaú Holding Financeira' ? 'selected' : ''; ?>>652 - Itaú Holding Financeira</option>
                <option value="653 - Banco Indusval" <?php echo $bank_name === '653 - Banco Indusval' ? 'selected' : ''; ?>>653 - Banco Indusval</option>
                <option value="654 - Banco A.J. Renner" <?php echo $bank_name === '654 - Banco A.J. Renner' ? 'selected' : ''; ?>>654 - Banco A.J. Renner</option>
                <option value="655 - Banco Votorantim" <?php echo $bank_name === '655 - Banco Votorantim' ? 'selected' : ''; ?>>655 - Banco Votorantim</option>
                <option value="707 - Banco Daycoval" <?php echo $bank_name === '707 - Banco Daycoval' ? 'selected' : ''; ?>>707 - Banco Daycoval</option>
                <option value="719 - Banif" <?php echo $bank_name === '719 - Banif' ? 'selected' : ''; ?>>719 - Banif</option>
                <option value="721 - Banco Credibel" <?php echo $bank_name === '721 - Banco Credibel' ? 'selected' : ''; ?>>721 - Banco Credibel</option>
                <option value="734 - Banco Gerdau" <?php echo $bank_name === '734 - Banco Gerdau' ? 'selected' : ''; ?>>734 - Banco Gerdau</option>
                <option value="735 - Banco Pottencial" <?php echo $bank_name === '735 - Banco Pottencial' ? 'selected' : ''; ?>>735 - Banco Pottencial</option>
                <option value="738 - Banco Morada" <?php echo $bank_name === '738 - Banco Morada' ? 'selected' : ''; ?>>738 - Banco Morada</option>
                <option value="739 - Banco Galvão de Negócios" <?php echo $bank_name === '739 - Banco Galvão de Negócios' ? 'selected' : ''; ?>>739 - Banco Galvão de Negócios</option>
                <option value="740 - Banco Barclays" <?php echo $bank_name === '740 - Banco Barclays' ? 'selected' : ''; ?>>740 - Banco Barclays</option>
                <option value="741 - BRP" <?php echo $bank_name === '741 - BRP' ? 'selected' : ''; ?>>741 - BRP</option>
                <option value="743 - Banco Semear" <?php echo $bank_name === '743 - Banco Semear' ? 'selected' : ''; ?>>743 - Banco Semear</option>
                <option value="745 - Banco Citibank" <?php echo $bank_name === '745 - Banco Citibank' ? 'selected' : ''; ?>>745 - Banco Citibank</option>
                <option value="746 - Banco Modal" <?php echo $bank_name === '746 - Banco Modal' ? 'selected' : ''; ?>>746 - Banco Modal</option>
                <option value="747 - Banco Rabobank International" <?php echo $bank_name === '747 - Banco Rabobank International' ? 'selected' : ''; ?>>747 - Banco Rabobank International</option>
                <option value="748 - Banco Cooperativo Sicredi" <?php echo $bank_name === '748 - Banco Cooperativo Sicredi' ? 'selected' : ''; ?>>748 - Banco Cooperativo Sicredi</option>
                <option value="749 - Banco Simples" <?php echo $bank_name === '749 - Banco Simples' ? 'selected' : ''; ?>>749 - Banco Simples</option>
                <option value="751 - Dresdner Bank" <?php echo $bank_name === '751 - Dresdner Bank' ? 'selected' : ''; ?>>751 - Dresdner Bank</option>
                <option value="752 - BNP Paribas" <?php echo $bank_name === '752 - BNP Paribas' ? 'selected' : ''; ?>>752 - BNP Paribas</option>
                <option value="753 - Banco Comercial Uruguai" <?php echo $bank_name === '753 - Banco Comercial Uruguai' ? 'selected' : ''; ?>>753 - Banco Comercial Uruguai</option>
                <option value="755 - Banco Merrill Lynch" <?php echo $bank_name === '755 - Banco Merrill Lynch' ? 'selected' : ''; ?>>755 - Banco Merrill Lynch</option>
                <option value="756 - Banco Cooperativo do Brasil" <?php echo $bank_name === '756 - Banco Cooperativo do Brasil' ? 'selected' : ''; ?>>756 - Banco Cooperativo do Brasil</option>
                <option value="757 - KEB" <?php echo $bank_name === '757 - KEB' ? 'selected' : ''; ?>>757 - KEB</option>


            </select><br><br>


            <label for="branch">Branch:</label><br>
            <input type="text" id="branch" name="branch" value="<?php echo $edit_mode ? htmlspecialchars($edit_account['branch']) : ''; ?>" required><br><br>

            <label for="account_type">Account Type:</label><br>
            <select id="account_type" name="account_type" required>
                <option value="saving" <?php echo ($edit_mode && $edit_account['account_type'] === 'saving') ? 'selected' : ''; ?>>Saving</option>
                <option value="current" <?php echo ($edit_mode && $edit_account['account_type'] === 'current') ? 'selected' : ''; ?>>Current</option>
            </select><br><br>

            <label for="account_number">Account Number:</label><br>
            <input type="text" id="account_number" name="account_number" value="<?php echo $edit_mode ? htmlspecialchars($edit_account['account_number']) : ''; ?>" required><br><br>

            <label for="account_holder_name">Account Holder Name:</label><br>
            <input type="text" id="account_holder_name" name="account_holder_name" value="<?php echo $edit_mode ? htmlspecialchars($edit_account['account_holder_name']) : ''; ?>" required><br><br>

            <label for="account_holder_cpf">Account Holder CPF:</label><br>
            <input type="text" id="account_holder_cpf" name="account_holder_cpf" value="<?php echo $edit_mode ? htmlspecialchars($edit_account['account_holder_cpf']) : ''; ?>" required maxlength="11"><br><br>

            <input type="submit" value="<?php echo $edit_mode ? 'Update Bank Account' : 'Add Bank Account'; ?>">
            <a href="index.php">[ back ]</a>
        </form>
    <?php else: ?>
        <p>You have reached the maximum number of 10 bank accounts.</p>
    <?php endif; ?>



</body>

</html>

<?php $conn->close(); ?>