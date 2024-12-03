<?php
session_start();


if (isset($_GET['iniciar'])) {
    $_SESSION['user'] = 'visitante';
   
    header('Location: email');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessão</title>
</head>
<body>
    <!-- Link para iniciar a sessão -->
    <a href="?iniciar=1">Clique aqui para iniciar a sessão</a>
</body>
</html>
