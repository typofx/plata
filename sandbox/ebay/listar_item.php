<?php

$appID = 'YOUR_APP_ID';
$certID = 'YOUR_CERT_ID';
$devID = 'YOUR_DEV_ID';
$authToken = 'YOUR_AUTH_TOKEN';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar dados do formulário
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $imagePath = $_FILES['image']['tmp_name'];

    // Enviar imagem ao eBay
    $imageData = base64_encode(file_get_contents($imagePath));

    // Endpoint da API do eBay
    $endpoint = 'https://api.ebay.com/ws/api.dll';

    // Cabeçalhos da requisição para UploadSiteHostedPictures
    $headers = [
        'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
        'X-EBAY-API-DEV-NAME: ' . $devID,
        'X-EBAY-API-APP-NAME: ' . $appID,
        'X-EBAY-API-CERT-NAME: ' . $certID,
        'X-EBAY-API-CALL-NAME: UploadSiteHostedPictures',
        'X-EBAY-API-SITEID: 0',
        'Content-Type: text/xml'
    ];

    // Corpo da requisição XML para UploadSiteHostedPictures
    $requestBody = '<?xml version="1.0" encoding="utf-8"?>
    <UploadSiteHostedPicturesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
      <RequesterCredentials>
        <eBayAuthToken>' . $authToken . '</eBayAuthToken>
      </RequesterCredentials>
      <WarningLevel>High</WarningLevel>
      <PictureName>' . $title . '</PictureName>
      <PictureData>' . $imageData . '</PictureData>
    </UploadSiteHostedPicturesRequest>';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Erro ao fazer a requisição: ' . curl_error($ch);
    } else {
        $responseXml = simplexml_load_string($response);
        if ($responseXml->Ack != 'Failure') {
            $pictureURL = (string) $responseXml->SiteHostedPictureDetails->FullURL;
            echo "Imagem enviada com sucesso! URL da Imagem: " . $pictureURL . "\n";

            // Criar a listagem do item com a URL da imagem
            $headers = [
                'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
                'X-EBAY-API-DEV-NAME: ' . $devID,
                'X-EBAY-API-APP-NAME: ' . $appID,
                'X-EBAY-API-CERT-NAME: ' . $certID,
                'X-EBAY-API-CALL-NAME: AddFixedPriceItem',
                'X-EBAY-API-SITEID: 0',
                'Content-Type: text/xml'
            ];

            $requestBody = '<?xml version="1.0" encoding="utf-8"?>
            <AddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
              <RequesterCredentials>
                <eBayAuthToken>' . $authToken . '</eBayAuthToken>
              </RequesterCredentials>
              <ErrorLanguage>en_US</ErrorLanguage>
              <WarningLevel>High</WarningLevel>
              <Item>
                <Title>' . $title . '</Title>
                <Description>' . $description . '</Description>
                <PrimaryCategory>
                  <CategoryID>' . $category . '</CategoryID>
                </PrimaryCategory>
                <StartPrice>' . $price . '</StartPrice>
                <ConditionID>1000</ConditionID>
                <Country>US</Country>
                <Currency>USD</Currency>
                <DispatchTimeMax>3</DispatchTimeMax>
                <ListingDuration>GTC</ListingDuration>
                <ListingType>FixedPriceItem</ListingType>
                <PaymentMethods>PayPal</PaymentMethods>
                <PayPalEmailAddress>seu-email-paypal@example.com</PayPalEmailAddress>
                <PostalCode>95125</PostalCode>
                <Quantity>' . $quantity . '</Quantity>
                <ReturnPolicy>
                  <ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
                </ReturnPolicy>
                <ShippingDetails>
                  <ShippingType>Flat</ShippingType>
                  <ShippingServiceOptions>
                    <ShippingServicePriority>1</ShippingServicePriority>
                    <ShippingService>USPSMedia</ShippingService>
                    <ShippingServiceCost>2.50</ShippingServiceCost>
                  </ShippingServiceOptions>
                </ShippingDetails>
                <PictureDetails>
                  <PictureURL>' . $pictureURL . '</PictureURL>
                </PictureDetails>
                <Site>US</Site>
              </Item>
            </AddFixedPriceItemRequest>';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if ($response === false) {
                echo 'Erro ao fazer a requisição: ' . curl_error($ch);
            } else {
                $responseXml = simplexml_load_string($response);
                if ($responseXml->Ack != 'Failure') {
                    echo "Item listado com sucesso! Item ID: " . $responseXml->ItemID . "\n";
                } else {
                    echo "Erro ao listar o item: " . $responseXml->Errors->LongMessage . "\n";
                }
            }

            curl_close($ch);
        } else {
            echo "Erro ao enviar a imagem: " . $responseXml->Errors->LongMessage . "\n";
        }
    }

    curl_close($ch);
} else {
    echo "Por favor, envie o formulário.";
}
?>
