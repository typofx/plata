<?php
session_start(); // Iniciar a sessão

// Verifique se há dados de login enviados pelo formulário
if (isset($_POST["email"]) && isset($_POST["password"])) {
    // Obtenha os valores enviados pelo formulário
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Verifique as credenciais (Você pode substituir esta verificação com o seu método de autenticação, por exemplo, verificar em um banco de dados)
    $validEmail = "azulRaptor27@gmail.com"; // Substitua com o email válido
    $validPassword = "5Fj9#pKsE7"; // Substitua com a senha válida

    if ($email === $validEmail && $password === $validPassword) {
        // Credenciais corretas, configurar informações na sessão
        $_SESSION["user_email"] = $email;
        $_SESSION["user_logged_in"] = true;

        // Redirecionar para a página de boas-vindas ou qualquer outra página protegida
        header("Location: painel.php");
        exit();
    } else {
        // Credenciais inválidas, redirecione de volta para a página de login com uma mensagem de erro
        echo 'Algo está errado!';
        exit();
    }
}

// Resto do código aqui
?>

