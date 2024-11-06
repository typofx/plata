<?php
session_start();
include 'conexao.php';

// Check if the user is logged in and if the email code is set
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>';
    exit();
}

$user_email = $_SESSION['code_email'];

// Check if a PIX key already exists for the logged-in user
$sql = "SELECT pix_key, pix_type FROM granna80_bdlinks.granna_pix WHERE granna_user_email = '$user_email'";
$result = $conn->query($sql);

$existing_pix_key = '';
$existing_pix_type = '';

if ($result->num_rows > 0) {
    // If a PIX key exists, fetch it and populate the variables
    $row = $result->fetch_assoc();
    $existing_pix_key = $row['pix_key'];
    $existing_pix_type = $row['pix_type'];
}

// Handle form submission for adding or updating PIX key
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pix_key = $conn->real_escape_string($_POST['pix_key']);
    $pix_type = $conn->real_escape_string($_POST['pix_type']);

    if ($existing_pix_key) {
        // If a PIX key already exists, update it
        $sql = "UPDATE granna80_bdlinks.granna_pix SET pix_key = '$pix_key', pix_type = '$pix_type' WHERE granna_user_email = '$user_email'";
        if ($conn->query($sql) === TRUE) {
            echo "PIX key updated successfully!";
            echo '<script>window.location.href = "add_pix.php";</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // If no PIX key exists, insert a new one
        $sql = "INSERT INTO granna80_bdlinks.granna_pix (pix_key, pix_type, granna_user_email) VALUES ('$pix_key', '$pix_type', '$user_email')";
        if ($conn->query($sql) === TRUE) {
            echo "PIX key added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add or Edit PIX Key</title>
</head>
<body>

<h2><?php echo $existing_pix_key ? 'Edit PIX Key' : 'Add PIX Key'; ?></h2>
<form method="POST" action="">
    <label for="pix_key">PIX Key:</label>
    <input type="text" id="pix_key" name="pix_key" value="<?php echo htmlspecialchars($existing_pix_key); ?>" required><br><br>
    
    <label for="pix_type">PIX Type:</label>
    <select id="pix_type" name="pix_type" required>
        <option value="cpf" <?php echo $existing_pix_type === 'cpf' ? 'selected' : ''; ?>>CPF</option>
        <option value="email" <?php echo $existing_pix_type === 'email' ? 'selected' : ''; ?>>Email</option>
        <option value="phone" <?php echo $existing_pix_type === 'phone' ? 'selected' : ''; ?>>Phone</option>
        <option value="random" <?php echo $existing_pix_type === 'random' ? 'selected' : ''; ?>>Random</option>
    </select><br><br>
    
    <input type="submit" value="<?php echo $existing_pix_key ? 'Update PIX Key' : 'Add PIX Key'; ?>">
    <a href="https://www.granna.ie/panel/">[ Back ]</a>
</form>

</body>
</html>
