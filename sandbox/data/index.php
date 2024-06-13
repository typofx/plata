<?php

require 'vendor/autoload.php'; // Carrega o autoloader do Composer

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\Utils;

// Configura o URL RPC da Polygon (Matic) usando Infura
$rpcUrl = 'https://polygon-mainnet.infura.io/v3/55525f35a6194564837bdb0f6d842255'; // Substitua pelo seu URL RPC do Infura

$web3 = new Web3(new HttpProvider($rpcUrl));

// Função para obter o saldo da carteira Ethereum
function getWalletBalance($web3, $walletAddress) {
    // Realiza a chamada assíncrona para obter o saldo
    $web3->eth->getBalance($walletAddress, function ($err, $balance) use ($walletAddress) {
        if ($err !== null) {
            echo 'Erro ao obter saldo da carteira ' . $walletAddress . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        // Verifica se $balance é um array (como parece ser o caso)
        if (is_array($balance)) {
            echo 'Resposta inesperada ao obter saldo da carteira ' . $walletAddress . PHP_EOL;
            var_dump($balance); // Debug: exibe o que foi retornado
            return;
        }

        // Exibe o saldo em Ether sem tentar converter
        echo 'Saldo da carteira ' . $walletAddress . ': ' . $balance->toString() . ' wei' . PHP_EOL;
        // Convertendo de wei para ether
      
    });
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $walletAddress = $_POST['walletAddress'];
    $tokenContract = $_POST['tokenContract'];

    // Exibir resultados
    echo '<h3>Resultados</h3>';
    echo '<p>Endereço da Carteira: ' . htmlspecialchars($walletAddress) . '</p>';
    echo '<p>Contrato do Token: ' . htmlspecialchars($tokenContract) . '</p>';

    // Obter saldo da carteira Ethereum
    getWalletBalance($web3, $walletAddress);

    // Aqui você pode adicionar lógica para interagir com o contrato do token ERC20, se necessário
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verificar Saldo da Carteira na Polygon (Matic)</title>
</head>
<body>
    <h2>Verificar Saldo da Carteira</h2>
    <form method="post" action="">
        <label for="walletAddress">Endereço da Carteira Ethereum:</label>
        <input type="text" id="walletAddress" name="walletAddress" placeholder="Endereço da Carteira Ethereum"><br><br>
        
        <label for="tokenContract">Endereço do Contrato do Token ERC20:</label>
        <input type="text" id="tokenContract" name="tokenContract" placeholder="Endereço do Contrato do Token ERC20"><br><br>
        
        <input type="submit" name="submit" value="Verificar Saldo">
    </form>
</body>
</html>
