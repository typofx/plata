<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php

// Include database connection file
include 'conexao.php';

// Initialize variables
$last_edited_by = $userEmail;

$name = $link = $status = $obs = $platform = "";
$success_message = $error_message = "";

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the record to be edited
    $query = "SELECT * FROM granna80_bdlinks.plata_link_checker WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $link = $row['link'];
        $status = $row['status'];
        $obs = $row['obs'];
        $platform = $row['platform']; // Fetch platform value
    } else {
        $error_message = "Record not found.";
    }
} else {
    $error_message = "Invalid request.";
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $link = $_POST['link'];
    $status = $_POST['status'];
    $obs = $_POST['obs'];
    $platform = $_POST['platform']; // Capture platform value

    // Update the record
    $updateQuery = "UPDATE granna80_bdlinks.plata_link_checker 
                    SET name='$name', link='$link', status='$status', obs='$obs', platform='$platform', last_edited_by='$last_edited_by' 
                    WHERE id=$id";

    if (mysqli_query($conn, $updateQuery)) {
        $success_message = "Record updated successfully.";
    } else {
        $error_message = "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Link Record</title>
</head>
<body>
    <h2>Edit Link Record</h2>

    <?php
    // Display success or error message
    if ($success_message) {
        echo "<p style='color:green;'>$success_message</p>";
    }
    if ($error_message) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <!-- Edit form -->
    <form action="edit.php?id=<?php echo $id  ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo $name; ?>" required><br><br>

        <label>Link:</label><br>
        <input type="url" name="link" value="<?php echo $link; ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="ok" <?php if ($status == 'ok') echo 'selected'; ?>>Ok</option>
            <option value="fail" <?php if ($status == 'fail') echo 'selected'; ?>>Fail</option>
        </select><br><br>

        <label>Platform:</label><br>
        <select name="platform" required>
            <option value="mobile" <?php if ($platform == 'mobile') echo 'selected'; ?>>Mobile</option>
            <option value="desktop" <?php if ($platform == 'desktop') echo 'selected'; ?>>Desktop</option>
        </select><br><br>

        <label>Observations (obs):</label><br>
        <textarea name="obs"><?php echo $obs; ?></textarea><br><br>

        <button type="submit">Update Record</button>
        <a href="index.php">[back]</a>
    </form>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
