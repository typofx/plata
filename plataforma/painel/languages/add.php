<?php  include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
// Include the file for database connection
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $task_goal_en = $_POST['task_goal_en'];
    $task_goal_es = $_POST['task_goal_es'];
    $task_goal_pt = $_POST['task_goal_pt'];

    // Insert data into the English table
    $sql_insert_en = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device) VALUES ('$name', '$task_goal_en', 'en', 'desktop')";
    $conn->query($sql_insert_en);

    // Get the last inserted ID for English
    $id_en = $conn->insert_id;

    // Insert data into the Spanish table
    $sql_insert_es = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device) VALUES ('$name', '$task_goal_es', 'es', 'desktop')";
    $conn->query($sql_insert_es);

    // Get the last inserted ID for Spanish
    $id_es = $conn->insert_id;

    // Insert data into the Portuguese table
    $sql_insert_pt = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device) VALUES ('$name', '$task_goal_pt', 'pt', 'desktop')";
    $conn->query($sql_insert_pt);

    // Get the last inserted ID for Portuguese
    $id_pt = $conn->insert_id;

    // Redirect after insert
    echo "<script>window.location.href = 'index.php';</script>"; // Redirect
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add TEXT</title>
</head>

<body>

    <h2>Add TEXT</h2>

    <!-- Form for adding new data -->
    <form method="POST" action="">
        <label>TEXT NAME:</label>
        <input type="text" name="name" required><br>

        <label>TEXT EN:</label>
        <input type="text" name="task_goal_en" required><br>

        <label>TEXT ES:</label>
        <input type="text" name="task_goal_es" required><br>

        <label>TEXT PT:</label>
        <input type="text" name="task_goal_pt" required><br>

        <!-- Form submission button -->
        <a href="index.php">Back</a>
        <button type="submit">Add Task</button>
    </form>

</body>

</html>
