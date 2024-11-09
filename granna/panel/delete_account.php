<?php
session_start();
include 'conexao.php';

// Check if the user is logged in and if the email is available
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>';
    exit();
}

// Get ID and origin from the query parameters
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$origin = isset($_GET['origin']) ? $_GET['origin'] : '';

// Validate origin and build the delete query based on table
if ($origin === 'PIX') {
    $sql = "DELETE FROM granna80_bdlinks.granna_pix WHERE id = ? AND granna_user_email = ?";
} elseif ($origin === 'Bank Account') {
    $sql = "DELETE FROM granna80_bdlinks.granna_bank_accounts WHERE id = ? AND granna_user_email = ?";
} else {
    echo "Invalid account origin.";
    exit();
}

// Prepare and execute the delete statement
$stmt = $conn->prepare($sql);
$user_email = $_SESSION['code_email'];
$stmt->bind_param("is", $id, $user_email);

if ($stmt->execute()) {
    echo "<script>alert('Account deleted successfully.'); window.location.href = 'manage_accounts.php';</script>";
} else {
    echo "<script>alert('Error deleting account.'); window.location.href = 'manage_accounts.php';</script>";
}

$stmt->close();
$conn->close();
?>
