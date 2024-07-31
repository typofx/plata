<?php
if (isset($_COOKIE['appearance'])) {
    $appearance = $_COOKIE['appearance'];
} else {
    $appearance = 'off'; 
}

if (isset($_POST['change_appearance'])) {
    if ($appearance == 'off') {
        $appearance = 'on';
    } else {
        $appearance = 'off';
    }

    setcookie('appearance', $appearance, time() + (86400 * 30), '/', '', true, true);  


    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($appearance === 'on') {
    $backgroundColor = "#000000"; 
    $textColor = "#ffffff"; 
} else {
    $backgroundColor = "#ffffff"; 
    $textColor = "#000000"; 
}

if (isset($_POST['set_cookie_value'])) {
    $value = $_POST['set_cookie_value'];
    setcookie('appearance', $value, time() + (86400 * 30), '/', '', true, true); 
    $appearance = $value;
}
?>

<body style="background-color: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>">
    <h1><?php echo htmlspecialchars($appearance); ?></h1>
    <form method="post" action="">
        <button type="submit" name="change_appearance">Button</button>
    </form>
</body>
