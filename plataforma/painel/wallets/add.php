<?php
include 'conexao.php'; // Conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $wallet = $_POST['wallet'];
    $logo = $_POST['logo'];
    $price = $_POST['price'];
    $decimal = $_POST['decimal'];
    $mobile = $_POST['mobile'];
    $desktop = $_POST['desktop'];
    $mod = $_POST['mod'];
    $tax = $_POST['tax'];
    $speed = $_POST['speed'];
    $connect = $_POST['connect'];
    $joining_fee = $_POST['joining_fee'];
    $api = $_POST['api'];
    $date = $_POST['date'];
    $score = $_POST['score'];

    // Upload do ícone da wallet
    $icon = $_FILES['icon']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["icon"]["name"]);
    move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file);

    // Insere os dados no banco de dados
    $sql = "INSERT INTO granna80_bdlinks.wallets (icon, wallet, logo, price, decimal_flag, mobile, desktop, mod_flag, tax, speed, connect, joining_fee, api, date, score)
            VALUES ('$icon', '$wallet', '$logo', '$price', '$decimal', '$mobile', '$desktop', '$mod', '$tax', '$speed', '$connect', '$joining_fee', '$api', '$date', '$score')";

    if (mysqli_query($conn, $sql)) {
        echo "New wallet added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Wallet</title>
</head>

<body>
    <h1>Add new Wallet</h1>

    
    <form action="add.php" method="POST" enctype="multipart/form-data">
    <label for="icon">Icon (upload wallet icon):</label><br><br>
    <input type="file" name="icon" ><br><br>

    <label for="wallet">Wallet Name:</label><br><br>
    <input type="text" name="wallet" ><br><br>

    <label for="logo">Logo:</label><br><br>
    <select name="logo" >
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <label for="price">Price:</label><br><br>
    <select name="price" >
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <label for="decimal">Decimal:</label><br><br>
    <select name="decimal" >
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <label for="mobile">Mobile:</label><br><br>
    <select name="mobile" >
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <label for="desktop">Desktop:</label><br><br>
    <select name="desktop" >
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <label for="mod">MOD:</label><br><br>
    <select name="mod" >
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <label for="tax">Tax:</label><br><br>
    <input type="number" step="0.01" name="tax" ><br><br>

    <label for="speed">Speed:</label><br><br>
    <select name="speed" >
        <option value="excellent">Excellent</option>
        <option value="good">Good</option>
        <option value="poor">Poor</option>
    </select><br><br>

    <label for="connect">Connect:</label><br><br>
    <select name="connect" >
        <option value="excellent">Excellent</option>
        <option value="good">Good</option>
        <option value="poor">Poor</option>
    </select><br><br>

    <label for="joining_fee">Joining Fee:</label><br><br>
    <input type="number" step="0.01" name="joining_fee" ><br><br>

    <label for="api">API:</label><br><br>
    <input type="text" name="api" ><br><br>

    <label for="date">Done:</label><br><br>
    <input type="date" name="date" ><br><br>

    <label for="score">Score:</label><br><br>
    <input type="number" step="0.01" name="score" ><br><br>

    <input type="submit" value="Add Wallet"><br><br>
</form>

</body>

</html>
