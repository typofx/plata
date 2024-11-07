<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Include database connection
include 'conexao.php';

// Get ID from the URL
$id = $_GET['id'];

// Fetch the existing data for this bank account
$query = "SELECT * FROM granna80_bdlinks.granna_bank_accounts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Update bank account record if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $_POST['label'];
    $bank_name = $_POST['bank_name'];
    $branch = $_POST['branch'];
    $account_type = $_POST['account_type'];
    $account_number = $_POST['account_number'];
    $account_holder_name = $_POST['account_holder_name'];
    $account_holder_cpf = $_POST['account_holder_cpf'];

    $updateQuery = "UPDATE granna80_bdlinks.granna_bank_accounts SET label = ?, bank_name = ?, branch = ?, account_type = ?, account_number = ?, account_holder_name = ?, account_holder_cpf = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssssi", $label, $bank_name, $branch, $account_type, $account_number, $account_holder_name, $account_holder_cpf, $id);

    if ($updateStmt->execute()) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Bank Account</title>
</head>

<body>

    <h2>Edit Bank Account</h2>
    <form method="POST">
        Label: <input type="text" name="label" value="<?= $data['label'] ?>"><br>
        <label for="bank_name">Bank Name:</label>
        <select id="bank_name" name="bank_name" required>
            <option value="">Select a Bank</option>
            <option value="001 - Banco do Brasil" <?= $data['bank_name'] === '001 - Banco do Brasil' ? 'selected' : ''; ?>>001 - Banco do Brasil</option>
            <option value="003 - Banco da Amazônia" <?= $data['bank_name'] === '003 - Banco da Amazônia' ? 'selected' : ''; ?>>003 - Banco da Amazônia</option>
            <option value="004 - Banco do Nordeste" <?= $data['bank_name'] === '004 - Banco do Nordeste' ? 'selected' : ''; ?>>004 - Banco do Nordeste</option>
            <option value="021 - Banestes" <?= $data['bank_name'] === '021 - Banestes' ? 'selected' : ''; ?>>021 - Banestes</option>
            <option value="025 - Banco Alfa" <?= $data['bank_name'] === '025 - Banco Alfa' ? 'selected' : ''; ?>>025 - Banco Alfa</option>
            <option value="027 - Besc" <?= $data['bank_name'] === '027 - Besc' ? 'selected' : ''; ?>>027 - Besc</option>
            <option value="029 - Banerj" <?= $data['bank_name'] === '029 - Banerj' ? 'selected' : ''; ?>>029 - Banerj</option>
            <option value="031 - Banco Beg" <?= $data['bank_name'] === '031 - Banco Beg' ? 'selected' : ''; ?>>031 - Banco Beg</option>
            <option value="031 - Banco Beg" <?php echo $data['bank_name'] === '031 - Banco Beg' ? 'selected' : ''; ?>>031 - Banco Beg</option>
            <option value="033 - Banco Santander Banespa" <?php echo $data['bank_name'] === '033 - Banco Santander Banespa' ? 'selected' : ''; ?>>033 - Banco Santander Banespa</option>
            <option value="036 - Banco Bem" <?php echo $data['bank_name'] === '036 - Banco Bem' ? 'selected' : ''; ?>>036 - Banco Bem</option>
            <option value="037 - Banpará" <?php echo $data['bank_name'] === '037 - Banpará' ? 'selected' : ''; ?>>037 - Banpará</option>
            <option value="038 - Banestado" <?php echo $data['bank_name'] === '038 - Banestado' ? 'selected' : ''; ?>>038 - Banestado</option>
            <option value="039 - BEP" <?php echo $data['bank_name'] === '039 - BEP' ? 'selected' : ''; ?>>039 - BEP</option>
            <option value="040 - Banco Cargill" <?php echo $data['bank_name'] === '040 - Banco Cargill' ? 'selected' : ''; ?>>040 - Banco Cargill</option>
            <option value="041 - Banrisul" <?php echo $data['bank_name'] === '041 - Banrisul' ? 'selected' : ''; ?>>041 - Banrisul</option>
            <option value="044 - BVA" <?php echo $data['bank_name'] === '044 - BVA' ? 'selected' : ''; ?>>044 - BVA</option>
            <option value="045 - Banco Opportunity" <?php echo $data['bank_name'] === '045 - Banco Opportunity' ? 'selected' : ''; ?>>045 - Banco Opportunity</option>
            <option value="047 - Banese" <?php echo $data['bank_name'] === '047 - Banese' ? 'selected' : ''; ?>>047 - Banese</option>
            <option value="062 - Hipercard" <?php echo $data['bank_name'] === '062 - Hipercard' ? 'selected' : ''; ?>>062 - Hipercard</option>
            <option value="063 - Ibibank" <?php echo $data['bank_name'] === '063 - Ibibank' ? 'selected' : ''; ?>>063 - Ibibank</option>
            <option value="065 - Lemon Bank" <?php echo $data['bank_name'] === '065 - Lemon Bank' ? 'selected' : ''; ?>>065 - Lemon Bank</option>
            <option value="066 - Banco Morgan Stanley Dean Witter" <?php echo $data['bank_name'] === '066 - Banco Morgan Stanley Dean Witter' ? 'selected' : ''; ?>>066 - Banco Morgan Stanley Dean Witter</option>
            <option value="069 - BPN Brasil" <?php echo $data['bank_name'] === '069 - BPN Brasil' ? 'selected' : ''; ?>>069 - BPN Brasil</option>
            <option value="070 - Banco de Brasília – BRB" <?php echo $data['bank_name'] === '070 - Banco de Brasília – BRB' ? 'selected' : ''; ?>>070 - Banco de Brasília – BRB</option>
            <option value="072 - Banco Rural" <?php echo $data['bank_name'] === '072 - Banco Rural' ? 'selected' : ''; ?>>072 - Banco Rural</option>
            <option value="073 - Banco Popular" <?php echo $data['bank_name'] === '073 - Banco Popular' ? 'selected' : ''; ?>>073 - Banco Popular</option>
            <option value="074 - Banco J. Safra" <?php echo $data['bank_name'] === '074 - Banco J. Safra' ? 'selected' : ''; ?>>074 - Banco J. Safra</option>
            <option value="075 - Banco CR2" <?php echo $data['bank_name'] === '075 - Banco CR2' ? 'selected' : ''; ?>>075 - Banco CR2</option>
            <option value="076 - Banco KDB" <?php echo $data['bank_name'] === '076 - Banco KDB' ? 'selected' : ''; ?>>076 - Banco KDB</option>
            <option value="096 - Banco BMF" <?php echo $data['bank_name'] === '096 - Banco BMF' ? 'selected' : ''; ?>>096 - Banco BMF</option>
            <option value="104 - Caixa Econômica Federal" <?php echo $data['bank_name'] === '104 - Caixa Econômica Federal' ? 'selected' : ''; ?>>104 - Caixa Econômica Federal</option>
            <option value="107 - Banco BBM" <?php echo $data['bank_name'] === '107 - Banco BBM' ? 'selected' : ''; ?>>107 - Banco BBM</option>
            <option value="116 - Banco Único" <?php echo $data['bank_name'] === '116 - Banco Único' ? 'selected' : ''; ?>>116 - Banco Único</option>
            <option value="151 - Nossa Caixa" <?php echo $data['bank_name'] === '151 - Nossa Caixa' ? 'selected' : ''; ?>>151 - Nossa Caixa</option>
            <option value="175 - Banco Finasa" <?php echo $data['bank_name'] === '175 - Banco Finasa' ? 'selected' : ''; ?>>175 - Banco Finasa</option>
            <option value="184 - Banco Itaú BBA" <?php echo $data['bank_name'] === '184 - Banco Itaú BBA' ? 'selected' : ''; ?>>184 - Banco Itaú BBA</option>
            <option value="204 - American Express Bank" <?php echo $data['bank_name'] === '204 - American Express Bank' ? 'selected' : ''; ?>>204 - American Express Bank</option>
            <option value="208 - Banco Pactual" <?php echo $data['bank_name'] === '208 - Banco Pactual' ? 'selected' : ''; ?>>208 - Banco Pactual</option>
            <option value="212 - Banco Matone" <?php echo $data['bank_name'] === '212 - Banco Matone' ? 'selected' : ''; ?>>212 - Banco Matone</option>
            <option value="213 - Banco Arbi" <?php echo $data['bank_name'] === '213 - Banco Arbi' ? 'selected' : ''; ?>>213 - Banco Arbi</option>
            <option value="214 - Banco Dibens" <?php echo $data['bank_name'] === '214 - Banco Dibens' ? 'selected' : ''; ?>>214 - Banco Dibens</option>
            <option value="217 - Banco Joh Deere" <?php echo $data['bank_name'] === '217 - Banco Joh Deere' ? 'selected' : ''; ?>>217 - Banco Joh Deere</option>
            <option value="218 - Banco Bonsucesso" <?php echo $data['bank_name'] === '218 - Banco Bonsucesso' ? 'selected' : ''; ?>>218 - Banco Bonsucesso</option>
            <option value="222 - Banco Calyon Brasil" <?php echo $data['bank_name'] === '222 - Banco Calyon Brasil' ? 'selected' : ''; ?>>222 - Banco Calyon Brasil</option>
            <option value="224 - Banco Fibra" <?php echo $data['bank_name'] === '224 - Banco Fibra' ? 'selected' : ''; ?>>224 - Banco Fibra</option>
            <option value="225 - Banco Brascan" <?php echo $data['bank_name'] === '225 - Banco Brascan' ? 'selected' : ''; ?>>225 - Banco Brascan</option>
            <option value="229 - Banco Cruzeiro" <?php echo $data['bank_name'] === '229 - Banco Cruzeiro' ? 'selected' : ''; ?>>229 - Banco Cruzeiro</option>
            <option value="230 - Unicard" <?php echo $data['bank_name'] === '230 - Unicard' ? 'selected' : ''; ?>>230 - Unicard</option>
            <option value="233 - Banco GE Capital" <?php echo $data['bank_name'] === '233 - Banco GE Capital' ? 'selected' : ''; ?>>233 - Banco GE Capital</option>
            <option value="237 - Bradesco" <?php echo $data['bank_name'] === '237 - Bradesco' ? 'selected' : ''; ?>>237 - Bradesco</option>
            <option value="241 - Banco Clássico" <?php echo $data['bank_name'] === '241 - Banco Clássico' ? 'selected' : ''; ?>>241 - Banco Clássico</option>
            <option value="243 - Banco Stock Máxima" <?php echo $data['bank_name'] === '243 - Banco Stock Máxima' ? 'selected' : ''; ?>>243 - Banco Stock Máxima</option>
            <option value="246 - Banco ABC Brasil" <?php echo $data['bank_name'] === '246 - Banco ABC Brasil' ? 'selected' : ''; ?>>246 - Banco ABC Brasil</option>

            <option value="248 - Banco Boavista Interatlântico" <?php echo $data['bank_name'] === '248 - Banco Boavista Interatlântico' ? 'selected' : ''; ?>>248 - Banco Boavista Interatlântico</option>
            <option value="249 - Investcred Unibanco" <?php echo $data['bank_name'] === '249 - Investcred Unibanco' ? 'selected' : ''; ?>>249 - Investcred Unibanco</option>
            <option value="250 - Banco Schahin" <?php echo $data['bank_name'] === '250 - Banco Schahin' ? 'selected' : ''; ?>>250 - Banco Schahin</option>
            <option value="252 - Fininvest" <?php echo $data['bank_name'] === '252 - Fininvest' ? 'selected' : ''; ?>>252 - Fininvest</option>
            <option value="254 - Paraná Banco" <?php echo $data['bank_name'] === '254 - Paraná Banco' ? 'selected' : ''; ?>>254 - Paraná Banco</option>
            <option value="263 - Banco Cacique" <?php echo $data['bank_name'] === '263 - Banco Cacique' ? 'selected' : ''; ?>>263 - Banco Cacique</option>
            <option value="265 - Banco Fator" <?php echo $data['bank_name'] === '265 - Banco Fator' ? 'selected' : ''; ?>>265 - Banco Fator</option>
            <option value="266 - Banco Cédula" <?php echo $data['bank_name'] === '266 - Banco Cédula' ? 'selected' : ''; ?>>266 - Banco Cédula</option>
            <option value="300 - Banco de la Nación Argentina" <?php echo $data['bank_name'] === '300 - Banco de la Nación Argentina' ? 'selected' : ''; ?>>300 - Banco de la Nación Argentina</option>
            <option value="318 - Banco BMG" <?php echo $data['bank_name'] === '318 - Banco BMG' ? 'selected' : ''; ?>>318 - Banco BMG</option>
            <option value="320 - Banco Industrial e Comercial" <?php echo $data['bank_name'] === '320 - Banco Industrial e Comercial' ? 'selected' : ''; ?>>320 - Banco Industrial e Comercial</option>
            <option value="356 - ABN Amro Real" <?php echo $data['bank_name'] === '356 - ABN Amro Real' ? 'selected' : ''; ?>>356 - ABN Amro Real</option>
            <option value="341 - Itaú" <?php echo $data['bank_name'] === '341 - Itaú' ? 'selected' : ''; ?>>341 - Itaú</option>
            <option value="347 - Sudameris" <?php echo $data['bank_name'] === '347 - Sudameris' ? 'selected' : ''; ?>>347 - Sudameris</option>
            <option value="351 - Banco Santander" <?php echo $data['bank_name'] === '351 - Banco Santander' ? 'selected' : ''; ?>>351 - Banco Santander</option>
            <option value="353 - Banco Santander Brasil" <?php echo $data['bank_name'] === '353 - Banco Santander Brasil' ? 'selected' : ''; ?>>353 - Banco Santander Brasil</option>
            <option value="366 - Banco Societe Generale Brasil" <?php echo $data['bank_name'] === '366 - Banco Societe Generale Brasil' ? 'selected' : ''; ?>>366 - Banco Societe Generale Brasil</option>
            <option value="370 - Banco WestLB" <?php echo $data['bank_name'] === '370 - Banco WestLB' ? 'selected' : ''; ?>>370 - Banco WestLB</option>
            <option value="376 - JP Morgan" <?php echo $data['bank_name'] === '376 - JP Morgan' ? 'selected' : ''; ?>>376 - JP Morgan</option>
            <option value="389 - Banco Mercantil do Brasil" <?php echo $data['bank_name'] === '389 - Banco Mercantil do Brasil' ? 'selected' : ''; ?>>389 - Banco Mercantil do Brasil</option>
            <option value="394 - Banco Mercantil de Crédito" <?php echo $data['bank_name'] === '394 - Banco Mercantil de Crédito' ? 'selected' : ''; ?>>394 - Banco Mercantil de Crédito</option>
            <option value="399 - HSBC" <?php echo $data['bank_name'] === '399 - HSBC' ? 'selected' : ''; ?>>399 - HSBC</option>
            <option value="409 - Unibanco" <?php echo $data['bank_name'] === '409 - Unibanco' ? 'selected' : ''; ?>>409 - Unibanco</option>
            <option value="412 - Banco Capital" <?php echo $data['bank_name'] === '412 - Banco Capital' ? 'selected' : ''; ?>>412 - Banco Capital</option>
            <option value="422 - Banco Safra" <?php echo $data['bank_name'] === '422 - Banco Safra' ? 'selected' : ''; ?>>422 - Banco Safra</option>
            <option value="453 - Banco Rural" <?php echo $data['bank_name'] === '453 - Banco Rural' ? 'selected' : ''; ?>>453 - Banco Rural</option>
            <option value="456 - Banco Tokyo Mitsubishi UFJ" <?php echo $data['bank_name'] === '456 - Banco Tokyo Mitsubishi UFJ' ? 'selected' : ''; ?>>456 - Banco Tokyo Mitsubishi UFJ</option>
            <option value="464 - Banco Sumitomo Mitsui Brasileiro" <?php echo $data['bank_name'] === '464 - Banco Sumitomo Mitsui Brasileiro' ? 'selected' : ''; ?>>464 - Banco Sumitomo Mitsui Brasileiro</option>
            <option value="477 - Citibank" <?php echo $data['bank_name'] === '477 - Citibank' ? 'selected' : ''; ?>>477 - Citibank</option>
            <option value="479 - Itaubank (antigo Bank Boston)" <?php echo $data['bank_name'] === '479 - Itaubank (antigo Bank Boston)' ? 'selected' : ''; ?>>479 - Itaubank (antigo Bank Boston)</option>
            <option value="487 - Deutsche Bank" <?php echo $data['bank_name'] === '487 - Deutsche Bank' ? 'selected' : ''; ?>>487 - Deutsche Bank</option>
            <option value="488 - Banco Morgan Guaranty" <?php echo $data['bank_name'] === '488 - Banco Morgan Guaranty' ? 'selected' : ''; ?>>488 - Banco Morgan Guaranty</option>
            <option value="492 - Banco NMB Postbank" <?php echo $data['bank_name'] === '492 - Banco NMB Postbank' ? 'selected' : ''; ?>>492 - Banco NMB Postbank</option>
            <option value="494 - Banco la República Oriental del Uruguay" <?php echo $data['bank_name'] === '494 - Banco la República Oriental del Uruguay' ? 'selected' : ''; ?>>494 - Banco la República Oriental del Uruguay</option>
            <option value="495 - Banco La Provincia de Buenos Aires" <?php echo $data['bank_name'] === '495 - Banco La Provincia de Buenos Aires' ? 'selected' : ''; ?>>495 - Banco La Provincia de Buenos Aires</option>
            <option value="505 - Banco Credit Suisse" <?php echo $data['bank_name'] === '505 - Banco Credit Suisse' ? 'selected' : ''; ?>>505 - Banco Credit Suisse</option>
            <option value="600 - Banco Luso Brasileiro" <?php echo $data['bank_name'] === '600 - Banco Luso Brasileiro' ? 'selected' : ''; ?>>600 - Banco Luso Brasileiro</option>
            <option value="604 - Banco Industrial" <?php echo $data['bank_name'] === '604 - Banco Industrial' ? 'selected' : ''; ?>>604 - Banco Industrial</option>
            <option value="610 - Banco VR" <?php echo $data['bank_name'] === '610 - Banco VR' ? 'selected' : ''; ?>>610 - Banco VR</option>
            <option value="611 - Banco Paulista" <?php echo $data['bank_name'] === '611 - Banco Paulista' ? 'selected' : ''; ?>>611 - Banco Paulista</option>
            <option value="612 - Banco Guanabara" <?php echo $data['bank_name'] === '612 - Banco Guanabara' ? 'selected' : ''; ?>>612 - Banco Guanabara</option>
            <option value="613 - Banco Pecunia" <?php echo $data['bank_name'] === '613 - Banco Pecunia' ? 'selected' : ''; ?>>613 - Banco Pecunia</option>
            <option value="623 - Banco Panamericano" <?php echo $data['bank_name'] === '623 - Banco Panamericano' ? 'selected' : ''; ?>>623 - Banco Panamericano</option>
            <option value="626 - Banco Ficsa" <?php echo $data['bank_name'] === '626 - Banco Ficsa' ? 'selected' : ''; ?>>626 - Banco Ficsa</option>
            <option value="630 - Banco Intercap" <?php echo $data['bank_name'] === '630 - Banco Intercap' ? 'selected' : ''; ?>>630 - Banco Intercap</option>
            <option value="633 - Banco Rendimento" <?php echo $data['bank_name'] === '633 - Banco Rendimento' ? 'selected' : ''; ?>>633 - Banco Rendimento</option>


            <option value="634 - Banco Triângulo" <?php echo $data['bank_name'] === '634 - Banco Triângulo' ? 'selected' : ''; ?>>634 - Banco Triângulo</option>
            <option value="637 - Banco Sofisa" <?php echo $data['bank_name'] === '637 - Banco Sofisa' ? 'selected' : ''; ?>>637 - Banco Sofisa</option>
            <option value="638 - Banco Prosper" <?php echo $data['bank_name'] === '638 - Banco Prosper' ? 'selected' : ''; ?>>638 - Banco Prosper</option>
            <option value="643 - Banco Pine" <?php echo $data['bank_name'] === '643 - Banco Pine' ? 'selected' : ''; ?>>643 - Banco Pine</option>
            <option value="652 - Itaú Holding Financeira" <?php echo $data['bank_name'] === '652 - Itaú Holding Financeira' ? 'selected' : ''; ?>>652 - Itaú Holding Financeira</option>
            <option value="653 - Banco Indusval" <?php echo $data['bank_name'] === '653 - Banco Indusval' ? 'selected' : ''; ?>>653 - Banco Indusval</option>
            <option value="654 - Banco A.J. Renner" <?php echo $data['bank_name'] === '654 - Banco A.J. Renner' ? 'selected' : ''; ?>>654 - Banco A.J. Renner</option>
            <option value="655 - Banco Votorantim" <?php echo $data['bank_name'] === '655 - Banco Votorantim' ? 'selected' : ''; ?>>655 - Banco Votorantim</option>
            <option value="707 - Banco Daycoval" <?php echo $data['bank_name'] === '707 - Banco Daycoval' ? 'selected' : ''; ?>>707 - Banco Daycoval</option>
            <option value="719 - Banif" <?php echo $data['bank_name'] === '719 - Banif' ? 'selected' : ''; ?>>719 - Banif</option>
            <option value="721 - Banco Credibel" <?php echo $data['bank_name'] === '721 - Banco Credibel' ? 'selected' : ''; ?>>721 - Banco Credibel</option>
            <option value="734 - Banco Gerdau" <?php echo $data['bank_name'] === '734 - Banco Gerdau' ? 'selected' : ''; ?>>734 - Banco Gerdau</option>
            <option value="735 - Banco Pottencial" <?php echo $data['bank_name'] === '735 - Banco Pottencial' ? 'selected' : ''; ?>>735 - Banco Pottencial</option>
            <option value="738 - Banco Morada" <?php echo $data['bank_name'] === '738 - Banco Morada' ? 'selected' : ''; ?>>738 - Banco Morada</option>
            <option value="739 - Banco Galvão de Negócios" <?php echo $data['bank_name'] === '739 - Banco Galvão de Negócios' ? 'selected' : ''; ?>>739 - Banco Galvão de Negócios</option>
            <option value="740 - Banco Barclays" <?php echo $data['bank_name'] === '740 - Banco Barclays' ? 'selected' : ''; ?>>740 - Banco Barclays</option>
            <option value="741 - BRP" <?php echo $data['bank_name'] === '741 - BRP' ? 'selected' : ''; ?>>741 - BRP</option>
            <option value="743 - Banco Semear" <?php echo $data['bank_name'] === '743 - Banco Semear' ? 'selected' : ''; ?>>743 - Banco Semear</option>
            <option value="745 - Banco Citibank" <?php echo $data['bank_name'] === '745 - Banco Citibank' ? 'selected' : ''; ?>>745 - Banco Citibank</option>
            <option value="746 - Banco Modal" <?php echo $data['bank_name'] === '746 - Banco Modal' ? 'selected' : ''; ?>>746 - Banco Modal</option>
            <option value="747 - Banco Rabobank International" <?php echo $data['bank_name'] === '747 - Banco Rabobank International' ? 'selected' : ''; ?>>747 - Banco Rabobank International</option>
            <option value="748 - Banco Cooperativo Sicredi" <?php echo $data['bank_name'] === '748 - Banco Cooperativo Sicredi' ? 'selected' : ''; ?>>748 - Banco Cooperativo Sicredi</option>
            <option value="749 - Banco Simples" <?php echo $data['bank_name'] === '749 - Banco Simples' ? 'selected' : ''; ?>>749 - Banco Simples</option>
            <option value="751 - Dresdner Bank" <?php echo $data['bank_name'] === '751 - Dresdner Bank' ? 'selected' : ''; ?>>751 - Dresdner Bank</option>
            <option value="752 - BNP Paribas" <?php echo $data['bank_name'] === '752 - BNP Paribas' ? 'selected' : ''; ?>>752 - BNP Paribas</option>
            <option value="753 - Banco Comercial Uruguai" <?php echo $data['bank_name'] === '753 - Banco Comercial Uruguai' ? 'selected' : ''; ?>>753 - Banco Comercial Uruguai</option>
            <option value="755 - Banco Merrill Lynch" <?php echo $data['bank_name'] === '755 - Banco Merrill Lynch' ? 'selected' : ''; ?>>755 - Banco Merrill Lynch</option>
            <option value="756 - Banco Cooperativo do Brasil" <?php echo $data['bank_name'] === '756 - Banco Cooperativo do Brasil' ? 'selected' : ''; ?>>756 - Banco Cooperativo do Brasil</option>
            <option value="757 - KEB" <?php echo $data['bank_name'] === '757 - KEB' ? 'selected' : ''; ?>>757 - KEB</option>
        </select><br>
        Branch: <input type="text" name="branch" value="<?= $data['branch'] ?>"><br>
        Account Type:
        <select name="account_type">
            <option value="saving" <?= $data['account_type'] == 'saving' ? 'selected' : '' ?>>Saving</option>
            <option value="current" <?= $data['account_type'] == 'current' ? 'selected' : '' ?>>Current</option>
        </select><br>
        Account Number: <input type="text" name="account_number" value="<?= $data['account_number'] ?>"><br>
        Account Holder Name: <input type="text" name="account_holder_name" value="<?= $data['account_holder_name'] ?>"><br>
        Account Holder CPF: <input type="text" name="account_holder_cpf" value="<?= $data['account_holder_cpf'] ?>"><br>
        <button type="submit">Save Changes</button>
        <a href="index.php">[ back ]</a>
    </form>

</body>

</html>