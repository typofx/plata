<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Inicializa variáveis
$last_edited_by = $userEmail;
$name = $link = $status = $obs = $notes = $platform = $local = "";
$success_message = $error_message = "";

// Verifica se um ID foi passado na URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitiza o ID para garantir que seja um número inteiro

    // Busca o registro para edição
    $query = "SELECT * FROM granna80_bdlinks.plata_link_checker WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $link = $row['link'];
            $status = $row['status'];
            $obs = $row['obs'];
            $notes = $row['external_note'];
            $platform = $row['platform']; // Busca o valor da plataforma
            $local = $row['local']; // Busca o valor do local
        } else {
            $error_message = "Record not found.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    $error_message = "Invalid request.";
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitiza o ID
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $obs = mysqli_real_escape_string($conn, $_POST['obs']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $platform = mysqli_real_escape_string($conn, $_POST['platform']); // Captura o valor da plataforma
    $local = mysqli_real_escape_string($conn, $_POST['local']); // Captura o valor do local

    // Define a data manualmente em UTC
    date_default_timezone_set('UTC'); // Define o fuso horário para UTC
    $currentDateTime = date('Y-m-d H:i:s'); // Obtém a data e hora atuais em UTC

    // Atualiza o registro usando prepared statements
    $updateQuery = "UPDATE granna80_bdlinks.plata_link_checker 
                    SET name=?, link=?, status=?, obs=?, external_note=?, platform=?, local=?, last_edited_by=?, last_updated_date=? 
                    WHERE id=?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssssssi', $name, $link, $status, $obs, $notes, $platform, $local, $last_edited_by, $currentDateTime, $id);
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Record updated successfully.";
        } else {
            $error_message = "Error updating record: " . mysqli_error($conn);
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
    <title>Edit Link Record</title>
</head>
<body>
    <h2>Edit Link Record</h2>

    <?php
    // Exibe mensagens de sucesso ou erro
    if ($success_message) {
        echo "<p style='color:green;'>$success_message</p>";
    }
    if ($error_message) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <!-- Formulário de edição -->
    <form action="edit.php?id=<?php echo $id; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label>Local:</label><br>
        <input type="text" name="local" value="<?php echo htmlspecialchars($local); ?>" required><br><br>

        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label>Link:</label><br>
        <input type="url" name="link" value="<?php echo htmlspecialchars($link); ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="ok" <?php if ($status == 'ok') echo 'selected'; ?>>Ok</option>
            <option value="fail" <?php if ($status == 'fail') echo 'selected'; ?>>Fail</option>
        </select><br><br>

        <label>Platform:</label><br>
        <select name="platform" required>
            <option value="mobile" <?php if ($platform == 'mobile') echo 'selected'; ?>>Mobile</option>
            <option value="desktop" <?php if ($platform == 'desktop') echo 'selected'; ?>>Desktop</option>
        </select><br><br>

        <label>Observations (obs):</label><br>
        <textarea name="obs"><?php echo htmlspecialchars($obs); ?></textarea><br><br>

        <label>External notes:</label><br>
        <textarea name="notes"><?php echo htmlspecialchars($notes); ?></textarea><br><br>

        <button type="submit">Update Record</button>
        <a href="index.php">[back]</a>
    </form>
</body>
</html>

<?php
// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>