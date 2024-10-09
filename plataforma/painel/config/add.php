<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
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

<!-- HTML form -->
<form action="add.php" method="POST">
    Name: <br><input type="text" name="name" required><br>
    Link: <br><input type="text" name="link" required><br>

    Guest: <br><input type="checkbox" name="guest"><br>
    Trainee: <br><input type="checkbox" name="trainee"><br>
    Admin: <br><input type="checkbox" name="admin"><br>
    Root: <br><input type="checkbox" name="root"><br>
    Office: <br><input type="checkbox" name="office"><br>
    Block: <br><input type="checkbox" name="block"><br>




    <br>
    <br>
<a href="index.php">[ Back ]</a>
    <input type="submit" value="Add Record">
</form>
