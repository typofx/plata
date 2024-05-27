<?php
session_start();

// Iniciar a sessão e definir a variável de sessão se o parâmetro 'iniciar' estiver presente na URL
if (isset($_GET['iniciar'])) {
    $_SESSION['user'] = 'visitante';
    // Redirecionar para pix.php
    header('Location: email.php');
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
