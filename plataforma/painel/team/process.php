<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_members = (int) $_POST['num_members'];
    $selected_members = [];

    include "conexao.php"; // Include the file for database connection

    for ($i = 1; $i <= $num_members; $i++) {
        $member_id = $_POST['members'][$i]['id'];
        
        // Query to fetch data of the selected member
        $sql = "SELECT * FROM granna80_bdlinks.team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result->fetch_assoc();
        
        // Get selected social media
        $social_media_keys = array_slice($_POST['members'][$i]['social_media'], 0, 3);
        $social_media_data = [];
        foreach ($social_media_keys as $key) {
            $social_media_data[$key] = $member[$key];
        }

        $member_data = [
            'id' => (int)$member_id,
            'name' => $member['teamName'],
            'position' => $member['teamPosition'],
            'profile_picture' => $member['teamProfilePicture'],
            'social_media' => $social_media_data
        ];

        $selected_members[] = $member_data;
    }

    $conn->close();

    $json_data = json_encode($selected_members, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
    // Fix for escaped slashes
    $json_data = str_replace("\\/", "/", $json_data);

    file_put_contents('team_members.json', $json_data);

    echo '<script>window.location.replace("form.php");</script>';
}
?>
