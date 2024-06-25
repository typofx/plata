<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: index.php");
    exit();
}


$userName = $_SESSION["user_email"];
$userName1 = $_SESSION["user_user"];
$userLevel = $_SESSION["user_level"];
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

    <p>Hello <b><?php echo $userName1; ?> (<?php echo $userName; ?>) (<?php echo $userLevel; ?>)</b></p>
    
    <?php if ($userLevel == 'guest') { ?>
        <p>You do not have access to the platform.</p>
    <?php } elseif ($userLevel == 'admin') { ?>
        <ul>
            <li><a href="team">Meet the Team</a></li>
            <li><a href="painel.php">Plataforma</a></li>
            <li><a href="roadmap">Roadmap</a></li>
            <li><a href="painel.php">Plataforma</a></li>
        </ul>
    <?php } elseif ($userLevel == 'root') { ?>
        <ul>
            <li><a href="painel.php">Plataforma</a></li>
            <li><a href="roadmap">Roadmap</a></li>
            <li><a href="team">Meet the Team</a></li>
            <li><a href="email.php">Email</a></li>
            <li><a href="payments">Payments</a></li>
            <br>
            <br>
            <li><a href="giveaway">Giveaway-V2</a></li>
            <li><a href="recycleBin">Recycle bin</a></li>
            <li><a href="register">Plataforma Users</a></li>
            <li><a href="order-book">CEX Order Book</a></li>
            <br>
            <br>
            <li><a href="dex-liquidity">Exchanges</a></li>
            <li><a href="lp-contracts">LP Contracts</a></li>
            <li><a href="liquidity">Liquidity</a></li>
        </ul>
    <?php } ?>
</body>
</html>
