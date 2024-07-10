<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_members = min((int) $_POST['num_members'], 5); 
    $members = [];

    include "conexao.php"; 

   
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
        
        $member_name = $_POST['members'][$i]['name']; 


        $sql = "SELECT * FROM granna80_bdlinks.team WHERE teamName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $member_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result->fetch_assoc();

        if ($member) {
       
            $social_media_data = [];

  
            $social_media_count = 0;

          
            $cont = 1;
            
            $media_number = 0;
            foreach ($_POST['members'][$i]['social_media'] as $platform) {
                if ($social_media_count < 4 && isset($social_media_fields[$platform]) && isset($member[$social_media_fields[$platform]])) {
               
                    $social_media_value = $member[$social_media_fields[$platform]] ?: 'none';

                
                    $social_media_data[] = [
                        $cont, 
                        $platform,
                        $social_media_value,
                    ];
                    $social_media_count++;
                } elseif ($social_media_count < 4) {
               
                    $social_media_data[] = [
                        $cont, 
                        $platform,
                        'none',
                    ];
                    $social_media_count++;
                }
                $cont++;
                $media_number++;
            }

            $member_data = [
                'id' => $i, 
                'name' => $member['teamName'],
                'position' => $member['teamPosition'],
                'profile_picture' => "https://www.plata.ie/images/{$member['teamProfilePicture']}",
                'social_media' => $social_media_data,
            ];



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




    $json_data = json_encode($final_data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);

 
    $json_data = str_replace("\\/", "/", $json_data);

    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/sandbox/team/team_members.json';
 
    file_put_contents($file_path, $json_data);

 
    header("Location: form.php");
    exit();
} else {
   
    header("Location: ../index.php");
    exit();
}
