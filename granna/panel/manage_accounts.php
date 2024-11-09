<?php
session_start();
include 'conexao.php';

// Check if the user is logged in and if the email is available
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    // Redirect to the login page if the user is not logged in
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>';
    exit();
}

// Get the logged-in user's email
$user_email = $_SESSION['code_email'];

// SQL query to union data from granna_pix and granna_bank_accounts based on user email
$sql = "
    SELECT 
        id, 
        label, 
        pix_key AS account_identifier, 
        pix_type AS account_type, 
        created_at,
        'PIX' AS account_origin,
        updated_at
    FROM 
        granna80_bdlinks.granna_pix 
    WHERE 
        granna_user_email = ?
    UNION
    SELECT 
        id, 
        label, 
        CONCAT(bank_name, ' / ', branch, ' / ', account_number) AS account_identifier, 
        account_type, 
        created_at,
        'Bank Account' AS account_origin,
        updated_at
    FROM 
        granna80_bdlinks.granna_bank_accounts 
    WHERE 
        granna_user_email = ?
    ORDER BY created_at DESC
";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_email, $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

</head>
<body>
    <h2>Manage Your Accounts</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table id="accountsTable" class="display">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Account Identifier</th>
                    <th>Account Type</th>
                    <th>Origin</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['label']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_identifier']); ?></td>
                        <td><?php echo htmlspecialchars( strtoupper($row['account_type']) ); ?></td>
                        <td><?php echo htmlspecialchars($row['account_origin']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                        <td>
                            <a href="view_account.php?id=<?php echo $row['id']; ?>&origin=<?php echo $row['account_origin']; ?>">View</a> |
                            <a href="delete_account.php?id=<?php echo $row['id']; ?>&origin=<?php echo $row['account_origin']; ?>" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No accounts found for this user.</p>
    <?php endif; ?>
    
    <br><a href="index.php">[ Back to Dashboard ]</a>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#accountsTable').DataTable();
        });
    </script>
</body>
</html>

<?php
// Close statement and connection
$stmt->close();
$conn->close();
?>
