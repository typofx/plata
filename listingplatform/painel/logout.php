<?php
session_start(); // Iniciar a sessão

// Encerrar a sessão (logout)
session_unset(); // Limpar todas as variáveis de sessão
session_destroy(); // Destruir a sessão

// Redirecionar de volta para a página de login ou outra página de sua escolha
header("Location: index.php"); // Substitua "index.php" pelo nome da página de login ou outra página
exit();
?>
