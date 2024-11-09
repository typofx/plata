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

// Validate origin and build the query based on table
if ($origin === 'PIX') {
    $sql = "SELECT id, label, pix_key, pix_type, created_at FROM granna80_bdlinks.granna_pix WHERE id = ? AND granna_user_email = ?";
} elseif ($origin === 'Bank Account') {
    $sql = "SELECT id, label, bank_name, branch, account_type, account_number, account_holder_name, account_holder_cpf, created_at FROM granna80_bdlinks.granna_bank_accounts WHERE id = ? AND granna_user_email = ?";
} else {
    echo "Invalid account origin.";
    exit();
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$user_email = $_SESSION['code_email'];
$stmt->bind_param("is", $id, $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $account = $result->fetch_assoc();
} else {
    echo "Account not found or access denied.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Account</title>
</head>
<body>
    <h2>Account Details</h2>
    <?php if ($origin === 'PIX'): ?>
        <p><strong>Label:</strong> <?php echo htmlspecialchars($account['label']); ?></p>
        <p><strong>PIX Key:</strong> <?php echo htmlspecialchars($account['pix_key']); ?></p>
        <p><strong>PIX Type:</strong> <?php echo htmlspecialchars($account['pix_type']); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($account['created_at']); ?></p>
    <?php elseif ($origin === 'Bank Account'): ?>
        <p><strong>Label:</strong> <?php echo htmlspecialchars($account['label']); ?></p>
        <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($account['bank_name']); ?></p>
        <p><strong>Branch:</strong> <?php echo htmlspecialchars($account['branch']); ?></p>
        <p><strong>Account Type:</strong> <?php echo htmlspecialchars($account['account_type']); ?></p>
        <p><strong>Account Number:</strong> <?php echo htmlspecialchars($account['account_number']); ?></p>
        <p><strong>Account Holder Name:</strong> <?php echo htmlspecialchars($account['account_holder_name']); ?></p>
        <p><strong>Account Holder CPF:</strong> <?php echo htmlspecialchars($account['account_holder_cpf']); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($account['created_at']); ?></p>
    <?php endif; ?>
    <br><a href="manage_accounts.php">[ Back to Manage Accounts ]</a>
</body>
</html>
