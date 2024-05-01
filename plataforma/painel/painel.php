<?php
session_start(); 

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {

    header("Location: index.php"); // 
    exit();
}


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Painel - Listing places</title>
  
    
</head>

<body>
    <?php include 'lista.php'; ?>

</body>

</html>