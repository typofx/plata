<?php
session_start();
include 'conexao.php';

// Check if the user is logged in and if the email code is set
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>';
    exit();
}

$user_email = $_SESSION['code_email'];

// Retrieve up to 10 PIX keys for the logged-in user
$sql = "SELECT id, pix_key, pix_type, label FROM granna80_bdlinks.granna_pix WHERE granna_user_email = '$user_email' LIMIT 10";
$result = $conn->query($sql);
$pix_keys = $result->fetch_all(MYSQLI_ASSOC);

// Check if an edit ID is provided to prepopulate the form
$edit_pix = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int) $_GET['edit_id'];
    $sql = "SELECT id, pix_key, pix_type, label FROM granna80_bdlinks.granna_pix WHERE id = '$edit_id' AND granna_user_email = '$user_email'";
    $edit_result = $conn->query($sql);
    $edit_pix = $edit_result->fetch_assoc();
}

// Handle form submission for adding or updating PIX key
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pix_key = $conn->real_escape_string($_POST['pix_key']);
    $pix_type = $conn->real_escape_string($_POST['pix_type']);
    $label = $conn->real_escape_string($_POST['label']);
    $pix_id = isset($_POST['pix_id']) ? (int) $_POST['pix_id'] : null;

    if ($pix_id) {
        // Update existing PIX key
        $sql = "UPDATE granna80_bdlinks.granna_pix SET pix_key = '$pix_key', pix_type = '$pix_type', label = '$label' WHERE id = '$pix_id' AND granna_user_email = '$user_email'";
        $conn->query($sql);
    } else {
        // Insert new PIX key if limit not exceeded
        if (count($pix_keys) < 10) {
            $sql = "INSERT INTO granna80_bdlinks.granna_pix (pix_key, pix_type, label, granna_user_email) VALUES ('$pix_key', '$pix_type', '$label', '$user_email')";
            $conn->query($sql);
        }
    }
    echo '<script>window.location.href = "add_pix.php";</script>';
    exit();
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $sql = "DELETE FROM granna80_bdlinks.granna_pix WHERE id = '$delete_id' AND granna_user_email = '$user_email'";
    $conn->query($sql);
    echo '<script>window.location.href = "add_pix.php";</script>';
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage PIX Keys</title>
    <style>
        table,
        th,
        td {
            text-align: center;
        }

        .highlight {
            background-color: yellow;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pixTable').DataTable({
                "pageLength": 10,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
</head>
<body>

<h2>Manage PIX Keys</h2>

<!-- Display existing PIX keys or a message if none exist -->


<!-- Add or Edit PIX Key Form -->
<h3><?php echo $edit_pix ? 'Edit PIX Key' : 'Add PIX Key'; ?></h3>
<form method="POST" action="">
    <label for="label">Label:</label>
    <input type="text" id="label" name="label" value="<?php echo htmlspecialchars($edit_pix['label'] ?? ''); ?>" required><br><br>
    
    <label for="pix_key">PIX Key:</label>
    <input type="text" id="pix_key" name="pix_key" value="<?php echo htmlspecialchars($edit_pix['pix_key'] ?? ''); ?>" required><br><br>
    
    <label for="pix_type">PIX Type:</label>
    <select id="pix_type" name="pix_type" required>
        <option value="cpf" <?php echo ($edit_pix['pix_type'] ?? '') === 'cpf' ? 'selected' : ''; ?>>CPF</option>
        <option value="email" <?php echo ($edit_pix['pix_type'] ?? '') === 'email' ? 'selected' : ''; ?>>Email</option>
        <option value="phone" <?php echo ($edit_pix['pix_type'] ?? '') === 'phone' ? 'selected' : ''; ?>>Phone</option>
        <option value="random" <?php echo ($edit_pix['pix_type'] ?? '') === 'random' ? 'selected' : ''; ?>>Random</option>
    </select><br><br>
    
    <?php if ($edit_pix): ?>
        <input type="hidden" name="pix_id" value="<?php echo (int)$edit_pix['id']; ?>">
    <?php endif; ?>
    
    <input type="submit" value="<?php echo $edit_pix ? 'Update PIX Key' : 'Add PIX Key'; ?>">
    <a href="https://www.granna.ie/panel/">[ Back ]</a>
</form>

</body>
</html>
