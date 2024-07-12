<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_members = min((int) $_POST['num_members'], 5); // Limita a no máximo 5 membros
    $members = [];

    include "conexao.php"; // Inclui o arquivo para conexão com o banco de dados

    // Mapeia as chaves das redes sociais para os campos do banco de dados
    $social_media_fields = [
        'WhatsApp' => 'teamSocialMedia0',
        'Instagram' => 'teamSocialMedia1',
        'Telegram' => 'teamSocialMedia2',
        'Facebook' => 'teamSocialMedia3',
        'GitHub' => 'teamSocialMedia4',
        'Email' => 'teamSocialMedia5',
        'Twitter' => 'teamSocialMedia6',
        'LinkedIn' => 'teamSocialMedia7',
        'Twitch' => 'teamSocialMedia8',
        'Medium' => 'teamSocialMedia9',
    ];
    $member_number = 0;
    for ($i = 1; $i <= $num_members; $i++) {
        
        $member_name = $_POST['members'][$i]['name']; // Ajuste para acessar corretamente o nome do membro

        // Consulta para buscar os dados do membro selecionado com base em 'teamName'
        $sql = "SELECT * FROM granna80_bdlinks.team WHERE teamName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $member_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result->fetch_assoc();

        if ($member) {
            // Inicializa o array para armazenar os dados das redes sociais escolhidas
            $social_media_data = [];

            // Contador para limitar a 3 redes sociais por membro
            $social_media_count = 0;

            // Preenche os dados das redes sociais do membro baseado no que foi enviado via POST
            $cont = 1;
            
            $media_number = 0;
            foreach ($_POST['members'][$i]['social_media'] as $platform) {
                if ($social_media_count < 4 && isset($social_media_fields[$platform]) && isset($member[$social_media_fields[$platform]])) {
                    // Verifica se o valor da rede social está vazio
                    $social_media_value = $member[$social_media_fields[$platform]] ?: 'none';

                    // Adiciona apenas o número do ID associado ao membro
                    $social_media_data[] = [
                        $cont, // ID numérico associado ao membro
                        $platform,
                        $social_media_value,
                    ];
                    $social_media_count++;
                } elseif ($social_media_count < 4) {
                    // Se a rede social não estiver disponível, adiciona 'none'
                    $social_media_data[] = [
                        $cont, // ID numérico associado ao membro
                        $platform,
                        'none',
                    ];
                    $social_media_count++;
                }
                $cont++;
                $media_number++;
            }

            // Monta o array $member_data com as redes sociais escolhidas
            $member_data = [
                'id' => $i, // ID numérico do membro
                'name' => $member['teamName'],
                'position' => $member['teamPosition'],
                'profile_picture' => "https://www.plata.ie/images/{$member['teamProfilePicture']}",
                'social_media' => $social_media_data,
            ];


            // Adiciona os dados do membro ao array de membros
            $members[] = $member_data;
        }
        $member_number++;
    }

    $conn->close();


    $final_data = [
        'members' => $members,
        'member_number' => $member_number,
        'media_number' => $media_number,
    ];



    // Codifica os dados para JSON com JSON_NUMERIC_CHECK para garantir que números sejam tratados como inteiros
    $json_data = json_encode($final_data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);

    // Correção para barras invertidas escapadas
    $json_data = str_replace("\\/", "/", $json_data);

    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/sandbox/team/team_members.json';
    // Salva os dados JSON em um arquivo
    file_put_contents($file_path, $json_data);

    // Redireciona de volta para a página do formulário
    header("Location: form.php");
    exit();
} else {
    // Se o método de solicitação não for POST, redireciona para index.php ou página apropriada
    header("Location: ../index.php");
    exit();
}
