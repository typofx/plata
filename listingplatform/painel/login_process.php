<?php
session_start(); 


if (isset($_POST["email"]) && isset($_POST["password"])) {
    
    $email = $_POST["email"];
    $password = $_POST["password"];

  
    $validEmail = "azulRaptor27@gmail.com";
    $validPassword = "5Fj9#pKsE7";

    if ($email === $validEmail && $password === $validPassword) {
      
        $_SESSION["user_email"] = $email;
        $_SESSION["user_logged_in"] = true;

    
        header("Location: painel.php");
        exit();
    } else {
      
        echo 'Something is wrong!';
        exit();
    }
}


?>

