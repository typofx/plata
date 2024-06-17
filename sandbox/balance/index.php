<?php

require 'vendor/autoload.php'; // Load Composer's autoloader

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\Contract;
use Web3\Utils;

// Configure Polygon (Matic) RPC URL using Infura


$web3 = new Web3(new HttpProvider($rpcUrl));

$walletBalanceWei = null;
$tokenBalance = null;
$tokenSymbol = null;
$tokenName = null;
$tokenDecimals = null;

// Function to get Ethereum wallet balance
function getWalletBalance($web3, $walletAddress, &$walletBalanceWei) {
    // Asynchronous call to get balance
    $web3->eth->getBalance($walletAddress, function ($err, $balance) use ($walletAddress, &$walletBalanceWei) {
        if ($err !== null) {
            echo 'Error getting wallet balance for ' . $walletAddress . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        // Check if $balance is an array (as it appears to be)
        if (is_array($balance)) {
            echo 'Unexpected response getting wallet balance for ' . $walletAddress . PHP_EOL;
            var_dump($balance); // Debug: output what was returned
            return;
        }

        // Set balance in Wei
        $walletBalanceWei = $balance->toString();
    });
}

// Function to get ERC20 token info
function getTokenInfo($web3, $walletAddress, $tokenContract, &$tokenBalance, &$tokenSymbol, &$tokenName, &$tokenDecimals) {
    // ERC20 contract ABI
    $erc20Abi = json_decode('[{"constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"}]', true);

    $contract = new Contract($web3->provider, $erc20Abi);

    // Get token balance
    $contract->at($tokenContract)->call('balanceOf', $walletAddress, function ($err, $balance) use ($walletAddress, $tokenContract, &$tokenBalance) {
        if ($err !== null) {
            echo 'Error getting token balance ' . $tokenContract . ' for wallet ' . $walletAddress . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        // Check if $balance is an array (as it appears to be)
        if (is_array($balance) && isset($balance['balance'])) {
            $balance = $balance['balance']; // Access balance value
        } else {
            echo 'Token balance not available. Contract response:' . PHP_EOL;
            var_dump($balance); // Added for debugging
            return;
        }

        // Set token balance
        try {
            $balanceBn = Utils::toBn($balance);
            $tokenBalance = $balanceBn->toString();
        } catch (Exception $e) {
            echo 'Error converting balance to BigNumber: ' . $e->getMessage() . PHP_EOL;
        }
    });

    // Get token symbol
    $contract->at($tokenContract)->call('symbol', [], function ($err, $symbol) use ($tokenContract, &$tokenSymbol) {
        if ($err !== null) {
            echo 'Error getting token symbol ' . $tokenContract . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        // Check if $symbol is an array (as it appears to be)
        if (is_array($symbol) && isset($symbol[0])) {
            $symbol = $symbol[0]; // Access first element of array
        } else {
            echo 'Token symbol not available. Contract response:' . PHP_EOL;
            var_dump($symbol); // Added for debugging
            return;
        }

        // Set token symbol
        $tokenSymbol = $symbol;
    });

    // Get token name
    $contract->at($tokenContract)->call('name', [], function ($err, $name) use ($tokenContract, &$tokenName) {
        if ($err !== null) {
            echo 'Error getting token name ' . $tokenContract . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        // Check if $name is an array (as it appears to be)
        if (is_array($name) && isset($name[0])) {
            $name = $name[0]; // Access first element of array
        } else {
            echo 'Token name not available. Contract response:' . PHP_EOL;
            var_dump($name); // Added for debugging
            return;
        }

        // Set token name
        $tokenName = $name;
    });

    // Get token decimals
    $contract->at($tokenContract)->call('decimals', [], function ($err, $decimals) use ($tokenContract, &$tokenDecimals) {
        if ($err !== null) {
            echo 'Error getting token decimals ' . $tokenContract . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        // Check if $decimals is an array (as it appears to be)
        if (is_array($decimals) && isset($decimals[0])) {
            $decimals = $decimals[0]; // Access first element of array
        } else {
            echo 'Token decimals not available. Contract response:' . PHP_EOL;
            var_dump($decimals); // Added for debugging
            return;
        }

        // Set token decimals
        $tokenDecimals = $decimals;
    });
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $walletAddress = $_POST['walletAddress'];
    $tokenContract = $_POST['tokenContract'];

    // Get Ethereum wallet balance
    getWalletBalance($web3, $walletAddress, $walletBalanceWei);

    // Get ERC20 token info
    getTokenInfo($web3, $walletAddress, $tokenContract, $tokenBalance, $tokenSymbol, $tokenName, $tokenDecimals);

    // Wait to ensure all asynchronous calls are completed before displaying results
    sleep(1);

    // Display results
    echo '<h3>Results</h3>';
    echo '<p>Wallet Address: ' . htmlspecialchars($walletAddress) . '</p>';
    echo '<p>Token Contract: ' . htmlspecialchars($tokenContract) . '</p>';
    $walletBalanceWei = number_format(substr($walletBalanceWei, 0, -18) . '.' . substr($walletBalanceWei, -18), 5);
    echo '<p>Wallet balance ' . $walletAddress . ': <b>' . $walletBalanceWei . '</b> MATIC </p>';
  
    if ($tokenSymbol !== null) {
        echo '<p>Symbol : ' . $tokenSymbol . '</p>';
    } else {
        echo '<p>Token symbol not available.</p>';
    }
    if ($tokenName !== null) {
        echo '<p>Name : ' . $tokenName . '</p>';
    } else {
        echo '<p>Token name not available.</p>';
    }
    if ($tokenDecimals !== null) {
        echo '<p>Decimals : ' . $tokenDecimals . '</p>';
    } else {
        echo '<p>Token decimals not available.</p>';
    }

   
    if ($tokenBalance !== null && $tokenDecimals !== null) {
        // Convert token balance to the correct decimal format
        $tokenDecimalsStr = (int) $tokenDecimals->toString(); 
    
        // Increment tokenDecimalsStr
      
    
        // Use bcdiv para divisão precisa de números grandes
        $tokenBalanceFormatted = bcdiv($tokenBalance, bcpow(10, $tokenDecimalsStr), $tokenDecimalsStr);
    
        // Format the balance for display
        $formattedBalance = number_format((float) $tokenBalanceFormatted, $tokenDecimalsStr, '.', ',');
    
        echo 'Balance: <b>' . $formattedBalance . '</b>';
    } else {
        echo '<p>Token balance not available.</p>';
    }
    
    



    
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Wallet Balance on Polygon (Matic)</title>
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/0f8eed42e7.js" crossorigin="anonymous"></script>
</head>
<body>
    <h2>Check Wallet Balance</h2>
    <form method="post" action="">
        <label for="walletAddress">Ethereum Wallet Address:</label>
        <input type="text" id="walletAddress" name="walletAddress" onfocusout="isValidEtherWallet()" placeholder="Ethereum Wallet Address"><br><br>
        
        <label for="tokenContract">ERC20 Token Contract Address:</label>
        <input type="text" id="tokenContract" name="tokenContract" placeholder="ERC20 Token Contract Address"><br><br>
        
        <input type="submit" name="submit" value="Check Balance">
    </form>

    <script>
        function isValidEtherWallet() {
            let address = document.getElementById("walletAddress").value;
            let result = Web3.utils.isAddress(address);
            if (result != true) document.getElementById("walletAddress").value = "";
            console.log(result); // => true?
        }
    </script>
</body>
</html>
