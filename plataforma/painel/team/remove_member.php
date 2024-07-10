<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['remove_member'])) {
    $memberNameToRemove = $_GET['remove_member'];
    

    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/sandbox/team/team_members.json';

    $json_file =  $file_path;
    $json_data = file_get_contents($json_file);

    
    $members = json_decode($json_data, true);
    
 
    $indexToRemove = false;
    foreach ($members['members'] as $index => $member) {
        if ($member['name'] === $memberNameToRemove) {
            $indexToRemove = $index;
            break;
        }
    }

    if ($indexToRemove !== false) {
       
        array_splice($members['members'], $indexToRemove, 1);


        foreach ($members['members'] as $index => $member) {
            $members['members'][$index]['id'] = $index + 1;
        }
        
   
        $json_data = json_encode($members, JSON_UNESCAPED_SLASHES);


        file_put_contents($json_file, $json_data);
        
  
        header("Location: form.php");
        exit();
    } else {
     
        header("Location: form.php");
        exit();
    }
} else {

    header("Location: form.php");
    exit();
}
?>
