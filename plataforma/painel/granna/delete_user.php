<?php
// Include database connection file
include 'conexao.php';

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user from the database
    $deleteQuery = "DELETE FROM granna80_bdlinks.granna_users WHERE id = $id";
    
    if (mysqli_query($conn, $deleteQuery)) {
        echo "User deleted successfully.";
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "No user ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
</head>
<body>
    <a href="index.php">Back to User List</a>
</body>
</html>
