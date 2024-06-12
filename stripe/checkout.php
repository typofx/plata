<?php

require __DIR__ . "/vendor/autoload.php";



\Stripe\Stripe::setApiKey($stripe_secret_key);


$unitAmount = $_POST["unit_amount_display"];
    

$unitAmount = str_replace(['.', ','], '', $unitAmount);



$currency = $_POST["currency"];
$email = $_POST["customer_email"];
$PLTwanted = $_POST["PLTvalue"];
$web3wallet = $_POST["web_wallet"];

$emailSubject = "Purchasing Plata Token via Stripe";
$headers = "From: noreply@plata.ie";

date_default_timezone_set('UTC');
$date = date("Y-m-d H:i:s");
$Expdate = strtotime(date("Y-m-d H:i:s")) + 900; // 15*60=900 seconds
$Expdate = date("Y-m-d H:i:s", $Expdate);
$identificador = rand(100, 999);
$bank = 'stripe';

$emailMessage =
    "Bank: " . $bank . "\n\n" .
    "User Email: " . $email . "\n\n" .
    "Order Executed At: " . $date . "\n" .
    "Expected Value (" . strtoupper($currency) . "): ";
$valor_final = $unitAmount / 100;
$emailMessage .= number_format($valor_final, 2, '.', '') . "\n"; // Removed formatting here

$emailMessage .= "Plata Tokens Expected (PLT): " . $PLTwanted . "\n" .
    "Web3 Wallet: " . $web3wallet . "\n" .
    "Chain ID: Polygon (137)" . "\n" .
    "Identifier: " . $identificador . "\n" .
    "Budget Valid Until: " . $Expdate;

mail($email, $emailSubject, $emailMessage, $headers);
mail('salesdone@plata.ie', $emailSubject, $emailMessage, $headers);
mail('uarloque@live.com', $emailSubject, $emailMessage, $headers);

include "conexao.php"; 

$date = date("Y-m-d H:i:s");
$plata = $PLTwanted;
$amount = $valor_final; // Removed formatting here
$asset =  strtoupper($currency);
$address = $web3wallet;
$txid = "polygon"; 
$email; // Define the email variable
$status = "pending"; 

// SQL query to insert data into the payments table
$sql = "INSERT INTO granna80_bdlinks.payments (date, bank, plata, amount, asset, address, txid, email, status) 
        VALUES ('$date', '$bank', '$plata', '$amount', '$asset', '$address', '$txid', '$email', '$status')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    $text1 = "Payment data inserted successfully.";
} else {
    $text2 = "Error inserting payment data: " . $conn->error;
}

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "https://plata.ie/stripe/success.php",     //URLS MUST BEGIN WITH 'HTTP' OR 'HTML' IF IT DOESN'T GENERATE AN ERROR
    "cancel_url" => "https://plata.ie/stripe/index.php",
    "locale" => "auto",
    "customer_email" => $email,
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => $currency,
                "unit_amount" => $unitAmount,
                "product_data" => [
                    "name" => "Plata token"
                ]
            ]
        ],

    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
