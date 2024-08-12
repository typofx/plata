<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Function to fetch available languages
function fetchLanguages($conn) {
    $sql = "SELECT code FROM granna80_bdlinks.languages";
    $result = $conn->query($sql);

    $languages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $languages[] = $row['code'];
        }
    }
    return $languages;
}

// Get available languages
$languages = fetchLanguages($conn);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $texts = [];

    foreach ($languages as $language) {
        $texts[$language] = $_POST["task_goal_$language"];
    }

    // Insert data for each language
    foreach ($languages as $language) {
        $sql_insert = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device) VALUES (?, ?, ?, 'desktop')";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("sss", $name, $texts[$language], $language);
        $stmt->execute();
    }

    // Redirect after insert
    echo "<script>window.location.href = 'index.php';</script>";
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

        <?php foreach ($languages as $language): ?>
            <!-- Form fields to add task data for each language -->
            <label>TEXT <?php echo strtoupper($language); ?>:</label>
            <input type="text" name="task_goal_<?php echo $language; ?>" required><br>
        <?php endforeach; ?>

        <!-- Form submission button -->
        <a href="index.php">Back</a>
        <button type="submit">Add Task</button>
    </form>

</body>

</html>
