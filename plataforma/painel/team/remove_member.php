<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

// Verifica se o parâmetro remove_member foi passado na URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['remove_member'])) {
    $memberNameToRemove = $_GET['remove_member'];
    

    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/sandbox/team/team_members.json';
    // Carrega o conteúdo do arquivo JSON atual
    $json_file =  $file_path;
    $json_data = file_get_contents($json_file);

    // Decodifica o JSON
    $members = json_decode($json_data, true);
    
    // Encontra o índice do membro no JSON pelo nome
    $indexToRemove = false;
    foreach ($members['members'] as $index => $member) {
        if ($member['name'] === $memberNameToRemove) {
            $indexToRemove = $index;
            break;
        }
    }

    if ($indexToRemove !== false) {
        // Remove o membro do JSON
        array_splice($members['members'], $indexToRemove, 1);

        // Reorganiza os IDs dos membros restantes
        foreach ($members['members'] as $index => $member) {
            $members['members'][$index]['id'] = $index + 1;
        }
        
        // Codifica o JSON novamente
        $json_data = json_encode($members, JSON_UNESCAPED_SLASHES);

        // Salva o JSON de volta no arquivo
        file_put_contents($json_file, $json_data);
        
        // Redireciona de volta para a página principal após a remoção
        header("Location: form.php");
        exit();
    } else {
        // Se o membro não foi encontrado, redireciona de volta para a página principal
        header("Location: form.php");
        exit();
    }
} else {
    // Se o parâmetro remove_member não foi passado corretamente, redireciona para a página principal
    header("Location: form.php");
    exit();
}
?>
