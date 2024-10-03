<?php
// URL do JSON
$json_url = 'https://plata.ie/plataforma/painel/wallets/wallets.json';

// Obtendo o conteúdo do JSON
$json_data = file_get_contents($json_url);

// Decodificando o JSON em um array associativo
$wallets = json_decode($json_data, true);

// Exibindo os dados
foreach ($wallets as $wallet) {
    echo "<h2>Wallet ID: " . $wallet['id'] . "</h2>";
    echo "<strong>Wallet Name:</strong> " . $wallet['wallet'] . "<br>";
    echo "<strong>Icon:</strong> <img src='uploads/" . $wallet['icon'] . "' alt='" . $wallet['wallet'] . "' style='width: 50px; height: 50px;'><br>";
    echo "<strong>Logo:</strong> " . $wallet['logo'] . "<br>";
    echo "<strong>Price:</strong> " . $wallet['price'] . "<br>";
    echo "<strong>Decimal Flag:</strong> " . $wallet['decimal_flag'] . "<br>";
    echo "<strong>Mobile Support:</strong> " . $wallet['mobile'] . "<br>";
    echo "<strong>Desktop Support:</strong> " . $wallet['desktop'] . "<br>";
    echo "<strong>Mod:</strong> " . $wallet['mod'] . "<br>";
    echo "<strong>Tax:</strong> " . $wallet['tax'] . "<br>";
    echo "<strong>Speed:</strong> " . $wallet['speed'] . "<br>";
    echo "<strong>Connection Quality:</strong> " . $wallet['connect'] . "<br>";
    echo "<strong>Joining Fee:</strong> " . $wallet['joining_fee'] . "<br>";
    echo "<strong>API:</strong> " . $wallet['api'] . "<br>";
    echo "<strong>Date:</strong> " . $wallet['date'] . "<br>";
    echo "<strong>Score:</strong> " . $wallet['score'] . "<br>";
    echo "<hr>"; // Linha separadora
}
?>
