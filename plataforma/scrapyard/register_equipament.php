<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';


    // Validate if the name and description were provided
    if (empty($name)) {
        echo "Please enter the equipment name.";
    } else {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_equipment (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            echo "Equipment registered successfully!";
        } else {
            echo "Error registering the equipment: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Equipment</title>
</head>

<body>
    <h1>Register Equipment</h1>
    <form method="POST">
        <label for="name">Equipment Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <button type="submit">Register</button>
        <a href="index.php">[ Back ]</a>
    </form>
</body>

</html>