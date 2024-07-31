<?php
// Verifica se o parâmetro 'name' foi passado na query string da URL
if (isset($_GET['name'])) {
    // Obtém o nome e formata como esperado (minúsculas e com extensão .php)
    $name = $_GET['name'];
    $fileName = strtolower($name) . ".php";

    // Inclui o arquivo se ele existir no mesmo diretório
    if (file_exists($fileName)) {
        include $fileName;
    } else {
        echo "Arquivo não encontrado: $fileName";
    }
} else {
    echo "Parâmetro 'name' não encontrado na URL.";
}
?>
