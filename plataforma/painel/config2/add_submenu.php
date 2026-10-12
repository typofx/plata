<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
$session_info = ob_get_clean();
include 'conexao.php';

// Get parent_id from URL
if (!isset($_GET['parent_id']) || !is_numeric($_GET['parent_id'])) {
    die("Invalid parent ID");
}
$parent_id = intval($_GET['parent_id']);

// Fetch parent name
$query = "SELECT name FROM granna80_bdlinks.user_permissions WHERE id = $parent_id";
$result = mysqli_query($conn, $query);
$parent = mysqli_fetch_assoc($result);
if (!$parent) {
    die("Parent ID not found");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    
    if (!empty($name) && !empty($link)) {
        $insertQuery = "INSERT INTO granna80_bdlinks.submenus (parent_id, name, link) VALUES ($parent_id, '$name', '$link')";
        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>window.location.href='index.php';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<p style='color: red;'>Please fill all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Submenu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-container">
        <div class="session-info"><?php echo $session_info; ?></div>
    </div>

    <div class="form-container">
        <h1>Add Submenu for <?php echo htmlspecialchars($parent['name']); ?></h1>
        <form action="add_submenu.php?parent_id=<?php echo $parent_id; ?>" method="POST">
            <label for="name">Submenu Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="link">Submenu Link:</label>
            <input type="text" id="link" name="link" required>
            
            <button type="submit">Add Submenu</button>
        </form>
    </div>
    <div style="text-align: center;">
        <a href="index.php">[ Back to Permissions ]</a>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>