<?php
session_start(); // Iniciar a sessão
include "conexao.php"; 
ob_start(); include '../en/mobile/price.php'; ob_end_clean();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['error'] = "Acesso negado.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

if (!isset($_POST['evm_wallet']) || !isset($_POST['vote_number'])) {
    $_SESSION['error'] = "Dados do formulário ausentes.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

if ($conn->connect_error) {
    $_SESSION['error'] = "Falha na conexão: " . $conn->connect_error;
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$evm_wallet = $_POST['evm_wallet'];
$vote_number = $_POST['vote_number'];

if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $evm_wallet)) {
    $_SESSION['error'] = "Endereço EVM inválido.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$allowed_duplicate_wallets = array('0xc*****94b550', '0x5******F59B1Fe');
if (!in_array($evm_wallet, $allowed_duplicate_wallets)) {
    // Verificar a duplicidade apenas se não for um dos endereços permitidos
    $check_sql = "SELECT COUNT(*) AS count FROM granna80_bdlinks.votes WHERE evm_wallet = '$evm_wallet'";
    $result = $conn->query($check_sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["count"] > 0) {
            $_SESSION['error'] = "Este endereço de carteira Ethereum já foi registrado.";
            echo "<script>window.location.href='index.php';</script>";
            exit();
        }
    } else {
        $_SESSION['error'] = "Erro ao verificar o banco de dados para endereço de carteira existente.";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}

if ($vote_number != 0) {
    $check_vote_sql = "SELECT COUNT(*) AS count FROM granna80_bdlinks.votes WHERE vote_number = '$vote_number'";
    $vote_result = $conn->query($check_vote_sql);
    if ($vote_result && $vote_result->num_rows > 0) {
        $vote_row = $vote_result->fetch_assoc();
        if ($vote_row["count"] > 0) {
            $_SESSION['error'] = "Já existe um voto com o mesmo número.";
            echo "<script>window.location.href='index.php';</script>";
            exit();
        }
    } else {
        $_SESSION['error'] = "Erro ao verificar o banco de dados para voto existente.";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}

$target_dir = "uploads/";
$imageFileType = strtolower(pathinfo($_FILES["vote_image"]["name"], PATHINFO_EXTENSION));
$unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
$target_file = $target_dir . $unique_filename;

if (!isset($_FILES["vote_image"]) || $_FILES["vote_image"]["size"] == 0) {
    $target_file = "platatoken400px.png";
} else {
    $check = getimagesize($_FILES["vote_image"]["tmp_name"]);
    if($check === false) {
        $_SESSION['error'] = "O arquivo não é uma imagem.";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
    if ($_FILES["vote_image"]["size"] > 10000000) { // 10MB
        $_SESSION['error'] = "Desculpe, seu arquivo é muito grande.";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
    if (!move_uploaded_file($_FILES["vote_image"]["tmp_name"], $target_file)) {
        $_SESSION['error'] = "Desculpe, houve um erro ao enviar seu arquivo.";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}

$sql = "INSERT INTO granna80_bdlinks.votes (evm_wallet, vote_image, vote_number, MATICPLT, MATICUSD) VALUES ('$evm_wallet', '$target_file', '$vote_number', '$MATICPLT', '$MATICUSD')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "Novo registro criado com sucesso";
} else {
    $_SESSION['error'] = "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: index.php");
exit();
?>
