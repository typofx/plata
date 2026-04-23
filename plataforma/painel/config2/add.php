<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
$session_info = ob_get_clean();
include 'conexao.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form data
    $name = $_POST['name'];
    $link = $_POST['link'];

    // Check if checkboxes are set, assign 0 if not
    $guest = isset($_POST['guest']) ? 1 : 0;
    $trainee = isset($_POST['trainee']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;
    $root = isset($_POST['root']) ? 1 : 0;
    $office = isset($_POST['office']) ? 1 : 0;
    $block = isset($_POST['block']) ? 1 : 0;

    // Prepare SQL query to insert the data into the database
    $sql = "INSERT INTO granna80_bdlinks.user_permissions (name, link, guest, trainee, admin, root, office, block) 
            VALUES ('$name', '$link', '$guest', '$trainee', '$admin', '$root', '$office', '$block')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "Record added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-container">
        <div class="session-info"><?php echo $session_info; ?></div>
    </div>

    <div class="form-container">
        <h1>Add New Record</h1>
        <form action="add.php" method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Link:</label>
            <input type="text" name="link" required>

            <div class="checkbox-group" style="text-align: left; margin-bottom: 20px;">
                <label>Permissions:</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <label style="font-weight: normal;"><input type="checkbox" name="guest"> Guest</label>
                    <label style="font-weight: normal;"><input type="checkbox" name="trainee"> Trainee</label>
                    <label style="font-weight: normal;"><input type="checkbox" name="admin"> Admin</label>
                    <label style="font-weight: normal;"><input type="checkbox" name="root"> Root</label>
                    <label style="font-weight: normal;"><input type="checkbox" name="office"> Office</label>
                    <label style="font-weight: normal;"><input type="checkbox" name="block"> Block</label>
                </div>
            </div>

            <button type="submit">Add Record</button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php">[ Back to Permissions ]</a>
    </div>
</body>
</html>
