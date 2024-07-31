<?php
// Caminho do arquivo JSON
$json_file = 'team_members.json';

// Lendo o conteúdo do arquivo JSON
$json_data = file_get_contents($json_file);

// Decodificando o JSON
$data = json_decode($json_data, true);

// Verificando se a decodificação foi bem-sucedida
if (json_last_error() === JSON_ERROR_NONE) {
    // Iterando sobre os membros da equipe
    foreach ($data['members'] as $member) {
        echo "ID: " . $member['id'] . "<br>";
        echo "Name: " . $member['name'] . "<br>";
        echo "Position: " . $member['position'] . "<br>";
        echo "Profile Picture: <img src='" . $member['profile_picture'] . "' alt='" . $member['name'] . "'><br>";
        
        echo "Social Media: <br>";
        foreach ($member['social_media'] as $social) {
            echo "- " . $social[1] . ": " . $social[2] . "<br>";
        }
        
        echo "<hr>";
    }
    // Exibindo os campos adicionais
    echo "Member Number: " . $data['member_number'] . "<br>";
    echo "Media Number: " . $data['media_number'] . "<br>";
} else {
    echo "Erro ao decodificar JSON: " . json_last_error_msg();
}
?>

