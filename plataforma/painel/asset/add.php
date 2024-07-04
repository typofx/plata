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
function handleResponse($result)
{
    if (is_array($result)) {
        $result = reset($result); // Get the first value from the array
    }
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Select Infura endpoint URL based on the selected network
    $network = $_POST['network'];
    $infuraProjectId = // Replace with your Infura project ID

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
    $contractABI = '[{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"}]';

    // Create an instance of the contract with the HTTP provider and ABI
    $contract = new Contract($web3->provider, json_decode($contractABI, true));

    // Initialize variables to store results
    $contract_name = '';
    $ticker_symbol = '';
    $decimal_value = '';

    // Methods and parameters to call directly
    $methods = [
        'name' => [],       // name method without parameters
        'symbol' => [],     // symbol method without parameters
        'decimals' => [],   // decimals method without parameters
    ];

    // Loop to call each method
    foreach ($methods as $method => $params) {
        $contract->at($contractAddress)->call($method, $params, function ($err, $result) use ($method, &$contract_name, &$ticker_symbol, &$decimal_value) {
            if ($err !== null) {
                echo 'Error fetching ' . $method . ': ' . $err->getMessage() . '<br>';
                return;
            }

            if ($method == 'name') {
                $contract_name = handleResponse($result);
            } elseif ($method == 'symbol') {
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
    $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.assets (name, contract_name, ticker_symbol, decimal_value, network) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $contract_name, $contractAddress, $ticker_symbol, $decimal_value, $network);

    // Execute the insertion into the database
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        $_SESSION['message'] = "New record created successfully. ID: " . $last_id . " <a href='edit.php?id=" . $last_id . "'><i class='fa-solid fa-pen-to-square'>edit</i></a>";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect to the same page to show the message
    echo "<script>window.location.href = 'add.php';</script>";
    exit();
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Asset</title>
</head>

<body>
    <h1>Add Asset</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
 

    <form action="" method="post">
        <label for="network">Network:</label>
        <select id="network" name="network" required>
            <option value="polygon" selected>Polygon</option>
            <!--<option value="ethereum">Ethereum</option>-->
        </select><br><br>

        <label for="contract_address">Contract:</label>
        <input type="text" id="contract_address" name="contract_address" required><br><br>

        <input type="submit" value="Save">  <a href="https://plata.ie/plataforma/painel/asset/">Return</a>
    </form>



</body>

</html>
