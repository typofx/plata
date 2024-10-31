<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>

<?php
// Include database connection file
include 'conexao.php';

// Initialize variables to store form data and messages
$name = $link = $status = $obs =  "";
$success_message = $error_message = "";


$last_edited_by = $userEmail;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $name = $_POST['name'];
    $link = $_POST['link'];
    $status = $_POST['status'];
    $obs = $_POST['obs'];
   

    // Insert data into the database
    $insertQuery = "INSERT INTO granna80_bdlinks.plata_link_checker (name, link, status, obs, last_edited_by) 
                    VALUES ('$name', '$link', '$status', '$obs', '$last_edited_by')";

    if (mysqli_query($conn, $insertQuery)) {
        $success_message = "Record added successfully.";
    } else {
        $error_message = "Error adding record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Link Record</title>
</head>
<body>
    <h2>Add New Link Record</h2>

    <?php
    // Display success or error message
    if ($success_message) {
        echo "<p style='color:green;'>$success_message</p>";
    }
    if ($error_message) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <!-- Form to add a new link record -->
    <form action="" method="post">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo $name; ?>" required><br><br>

        <label>Link:</label><br>
        <input type="url" name="link" value="<?php echo $link; ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="ok" <?php if($status == "ok") echo "selected"; ?>>Ok</option>
            <option value="fail" <?php if($status == "fail") echo "selected"; ?>>Fail</option>
        </select><br><br>

        <label>Observations (obs):</label><br>
        <textarea name="obs"><?php echo $obs; ?></textarea><br><br>

     

        <button type="submit">Add Record</button>
        <a href="index.php">[back]</a>
    </form>
</body>
</html>
