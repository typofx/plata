<?php

require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "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";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$unitAmount = $_POST["unit_amount"]; 
$currency = $_POST["currency"];
$email = $_POST["customer_email"];

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
