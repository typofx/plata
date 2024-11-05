<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
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







//sem o getUrl ele não funciona
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
    <h1>registre a autenticação em 2 fatores</h1>
    <img src="<?php echo $g->getUrl('Typo FX',' (Plata Token) ',$secret)?>">


</body>
</html>
