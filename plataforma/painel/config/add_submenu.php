<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }
        input[type='text'] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #67458b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #9362C6;
        }
        a {
            display: block;
            margin-top: 20px;
            color: #9362C6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Add Submenu for <?php echo htmlspecialchars($parent['name']); ?></h1>
    <form action="add_submenu.php?parent_id=<?php echo $parent_id; ?>" method="POST">
        <label for="name">Submenu Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="link">Submenu Link:</label>
        <input type="text" id="link" name="link" required>
        
        <button type="submit">Add Submenu</button>
    </form>
    <a href="index.php">[ Back to Permissions ]</a>
</body>
</html>

<?php mysqli_close($conn); ?>