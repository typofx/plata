<?php

require 'vendor/autoload.php'; // Load Composer's autoloader

use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\Contract;
use Web3\Utils;

// Configure Polygon (Matic) RPC URL using Infura


$web3 = new Web3(new HttpProvider($rpcUrl));

$tokenBalance = null;
$tokenDecimals = null;

// Function to get ERC20 token balance and decimals
function getTokenInfo($web3, $walletAddress, $tokenContract, &$tokenBalance, &$tokenDecimals) {
    // ERC20 contract ABI
    $erc20Abi = json_decode('[{"constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"}]', true);

    $contract = new Contract($web3->provider, $erc20Abi);

    // Get token balance
    $contract->at($tokenContract)->call('balanceOf', $walletAddress, function ($err, $balance) use ($walletAddress, $tokenContract, &$tokenBalance) {
        if ($err !== null) {
            echo 'Error getting token balance ' . $tokenContract . ' for wallet ' . $walletAddress . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        if (is_array($balance) && isset($balance['balance'])) {
            $balance = $balance['balance'];
        } else {
            echo 'Token balance not available. Contract response:' . PHP_EOL;
            var_dump($balance);
            return;
        }

        try {
            $balanceBn = Utils::toBn($balance);
            $tokenBalance = $balanceBn->toString();
        } catch (Exception $e) {
            echo 'Error converting balance to BigNumber: ' . $e->getMessage() . PHP_EOL;
        }
    });

    // Get token decimals
    $contract->at($tokenContract)->call('decimals', [], function ($err, $decimals) use ($tokenContract, &$tokenDecimals) {
        if ($err !== null) {
            echo 'Error getting token decimals ' . $tokenContract . ': ' . $err->getMessage() . PHP_EOL;
            return;
        }

        if (is_array($decimals) && isset($decimals[0])) {
            $decimals = $decimals[0];
        } else {
            echo 'Token decimals not available. Contract response:' . PHP_EOL;
            var_dump($decimals);
            return;
        }

        $tokenDecimals = $decimals;
    });
}

// Check if form was submitted

  

?>


