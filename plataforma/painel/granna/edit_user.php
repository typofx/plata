<?php
// Include database connection file
include 'conexao.php';

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the user data from the database
    $query = "SELECT * FROM granna80_bdlinks.granna_users WHERE id = $id";
    $result = mysqli_query($conn, $query);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "No user ID provided.";
    exit;
}

// Check if form is submitted to update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $birth = $_POST['birth'];
    $ddi = $_POST['ddi'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];

    // Update user data in the database
    $updateQuery = "UPDATE granna80_bdlinks.granna_users SET 
                    name = '$name', 
                    last_name = '$last_name', 
                    email = '$email', 
                    birth = '$birth', 
                    ddi = '$ddi', 
                    phone = '$phone', 
                    username = '$username' 
                    WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "User updated successfully.";
        echo '<script>window.location.href = "index.php";</script>';
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form action="" method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <label>Birth Date:</label>
        <input type="date" name="birth" value="<?php echo $user['birth']; ?>" required><br>

        <label>DDI:</label>
        <input type="text" name="ddi" value="<?php echo $user['ddi']; ?>"><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $user['phone']; ?>"><br>

        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>

        <button type="submit">Save Changes</button>
        <a href="index.php">Back to User List</a>
    </form>
</body>
</html>
