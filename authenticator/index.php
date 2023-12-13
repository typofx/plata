<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include_once ('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once ('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');
include_once ('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once ('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();



$secret = '';



if(isset($_POST['token'])){
   $token = $_POST['token'];
   if($g->checkCode($secret, $token)){
    echo 'true';
   }else{
    echo 'false';
   }
   die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Auth</title>
</head>
<body>
    <h1>Informe o token </h1>
    <form action="" method="post">
        <input type="text" name="token" id="">
        <button type="submit">submit</button>
    </form>
</body>
</html>
