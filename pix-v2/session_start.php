<?php
session_start();
// Aqui você pode definir variáveis de sessão, se necessário
$_SESSION['user'] = 'visitante'; // Exemplo de variável de sessão

// Redirecionar para a próxima página
header("Location: pix.php");
exit();
?>
