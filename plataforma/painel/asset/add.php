<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../../index.php");
    exit();
}

require __DIR__ . '/vendor/autoload.php'; // Path to Composer autoload

use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;

// Function to handle contract response
function handleResponse($result) {
    if (is_array($result)) {
        $result = reset($result); // Get the first value from the array
    }
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Select Infura endpoint URL based on the selected network
    $network = $_POST['network'];
    $infuraProjectId = 

    if ($network == 'ethereum') {
        $infuraUrl = "https://mainnet.infura.io/v3/{$infuraProjectId}";
    } elseif ($network == 'polygon') {
        $infuraUrl = "https://polygon-mainnet.infura.io/v3/{$infuraProjectId}";
    } else {
        die('Invalid network selected.');
    }

    // Token contract you want to query
    $contractAddress = $_POST['contract_address']; // Contract address sent by the form

    // Create an instance of the HTTP provider for Infura
    $httpProvider = new HttpProvider($infuraUrl);

    // Create an instance of the Web3 object connected to Infura
    $web3 = new Web3($httpProvider);

    // Ethereum contract ABI you want to interact with (example of a USDC contract)
    $contractABI = '[{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"}]';

    // Create an instance of the contract with the HTTP provider and ABI
    $contract = new Contract($web3->provider, json_decode($contractABI, true));

    // Initialize variables to store results
    $ticker_symbol = '';
    $decimal_value = '';

    // Methods and parameters to call directly
    $methods = [
        'symbol' => [],     // symbol method without parameters
        'decimals' => [],   // decimals method without parameters
    ];

    // Loop to call each method
    foreach ($methods as $method => $params) {
        $contract->at($contractAddress)->call($method, $params, function ($err, $result) use ($method, &$ticker_symbol, &$decimal_value) {
            if ($err !== null) {
                echo 'Error fetching ' . $method . ': ' . $err->getMessage() . '<br>';
                return;
            }

            if ($method == 'symbol') {
                $ticker_symbol = handleResponse($result);
            } elseif ($method == 'decimals') {
                $decimal_value = handleResponse($result);
            }
        });
    }

    // Connect to the database
    include 'conexao.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.assets (contract_name, ticker_symbol, decimal_value, network) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $contractAddress, $ticker_symbol, $decimal_value, $network);

    // Execute the insertion into the database
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Asset</title>
</head>
<body>
    <h1>Add Asset</h1>
    <form action="" method="post">
        <label for="network">Network:</label>
        <select id="network" name="network" required>
            <option value="ethereum">Ethereum</option>
            <option value="polygon">Polygon</option>
        </select><br><br>
        
        <label for="contract_address">Contract:</label>
        <input type="text" id="contract_address" name="contract_address" required><br><br>
        
        <input type="submit" value="Save">
    </form>
</body>
</html>
