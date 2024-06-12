<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

$userName = $_SESSION["user_email"];
$userName1 = $_SESSION["user_user"];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    body {
        display: flex;
        flex-direction: column; /* Ensure content is stacked vertically */
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    h1 {
        margin-top: -200px; /* Remove default margin */
    }

    ul {
        list-style-type: none;
        padding: 0;
        text-align: center;
    }

    li {
        display: inline-block;
        margin: 0 15px;
    }

    a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
        font-size: 18px;
    }
</style>
    <title>Menu</title>
</head>
<body>
    <h1>Control Panel</h1>

    <p>Hello <b> <?php echo $userName1; ?> (<?php echo $userName; ?>)</b></p>
    

    <ul>
        <li><a href="painel.php">Plataforma</a></li>
        <li><a href="roadmap">Roadmap</a></li>
        <li><a href="team">Meet the Team</a></li>
        <li><a href="email.php">Email</a></li>
        <li><a href="payments">Payments</a></li>
        <li><a href="giveaway">Giveaway-V2</a></li>
        <li><a href="recycleBin">Recycle bin</a></li>
    </ul>

   

   
</body>
</html>
