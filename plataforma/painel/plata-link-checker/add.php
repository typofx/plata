<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Inicializa variáveis
$name = $link = $status = $obs = $platform = $local = "";
$success_message = $error_message = "";
$last_edited_by = $userEmail;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitiza e valida as entradas
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $obs = mysqli_real_escape_string($conn, $_POST['obs']);
    $platform = mysqli_real_escape_string($conn, $_POST['platform']);
    $local = mysqli_real_escape_string($conn, $_POST['local']);

    // Define a data manualmente em UTC
    date_default_timezone_set('UTC'); // Define o fuso horário para UTC
    $currentDateTime = date('Y-m-d H:i:s'); // Obtém a data e hora atuais em UTC

    // Insere os dados usando prepared statements
    $insertQuery = "INSERT INTO granna80_bdlinks.plata_link_checker 
                    (name, link, status, obs, platform, local, last_edited_by, last_updated_date, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insertQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssssss', $name, $link, $status, $obs, $platform, $local, $last_edited_by, $currentDateTime, $currentDateTime);
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Record added successfully.";
        } else {
            $error_message = "Error adding record: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Error preparing statement: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Link Record</title>
</head>
<body>
    <h2>Add New Link Record</h2>

    <?php
    if ($success_message) {
        echo "<p style='color:green;'>$success_message</p>";
    }
    if ($error_message) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <form action="" method="post">
        <label>Local:</label><br>
        <input type="text" name="local" value="<?php echo htmlspecialchars($local); ?>" required><br><br>
        
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label>Link:</label><br>
        <input type="url" name="link" value="<?php echo htmlspecialchars($link); ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="ok" <?php if($status == "ok") echo "selected"; ?>>Ok</option>
            <option value="fail" <?php if($status == "fail") echo "selected"; ?>>Fail</option>
        </select><br><br>

        <label>Platform:</label><br>
        <select name="platform" required>
            <option value="mobile" <?php if($platform == "mobile") echo "selected"; ?>>Mobile</option>
            <option value="desktop" <?php if($platform == "desktop") echo "selected"; ?>>Desktop</option>
        </select><br><br>

        <label>Observations (obs):</label><br>
        <textarea name="obs"><?php echo htmlspecialchars($obs); ?></textarea><br><br>

        <button type="submit">Add Record</button>
        <a href="index.php">[back]</a>
    </form>
</body>
</html>