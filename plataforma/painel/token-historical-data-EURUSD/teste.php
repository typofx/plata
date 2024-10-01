<?php

$data = json_encode(array(
    'currency' => 'USD',
    'coin' => 'USDT',
    'meta' => true
));

$context_options = array(
    'http' => array(
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n" .
                    "x-api-key: 135b0af8-e18a-42a4-bce7-ed193b2932e6\r\n",
        'content' => $data
    )
);

$context = stream_context_create($context_options);

$response = @file_get_contents('https://api.livecoinwatch.com/coins/single', false, $context);

if ($response === FALSE) {
    echo "An error occurred while making the request.";
    $error = error_get_last();
    echo " Error details: " . $error['message'];
} else {
    echo "API Response:\n";
    echo $response;
}

