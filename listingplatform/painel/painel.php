<?php
session_start(); // Iniciar a sessão

// Verificar se o usuário está autenticado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // O usuário não está autenticado, redirecionar de volta para a página de login
    header("Location: index.php"); // 
    exit();
}

// Se o usuário está autenticado, continue exibindo o conteúdo da página
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Painel - Listing places</title>
  
    
</head>

<body>
    <?php include 'lista.php'; ?>

</body>

</html>