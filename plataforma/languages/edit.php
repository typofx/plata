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

// Check if the uindex was passed
$uindex = isset($_GET['uindex']) ? $_GET['uindex'] : null;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $texts = [];

    foreach ($languages as $language) {
        $texts[$language] = $_POST["task_goal_$language"];
    }

    // Update or insert data for each language
    foreach ($languages as $language) {
        // Check if a record with the given uindex and language already exists
        $sql_check = "SELECT COUNT(*) as count FROM granna80_bdlinks.plata_texts WHERE uindex=? AND language=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("is", $uindex, $language);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $exists = $result_check->fetch_assoc()['count'] > 0;

        if ($exists) {
            // Update existing record
            $sql_update = "UPDATE granna80_bdlinks.plata_texts SET name=?, text=? WHERE uindex=? AND language=?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssis", $name, $texts[$language], $uindex, $language);
        } else {
            // Insert new record
            $sql_insert = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device, uindex) VALUES (?, ?, ?, 'desktop', ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sssi", $name, $texts[$language], $language, $uindex);
        }
        $stmt->execute();
    }

    // Redirect after update/insert
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

// Fetch current data for each language if uindex is present
$data = [];
if ($uindex) {
    foreach ($languages as $language) {
        $sql_select = "SELECT * FROM granna80_bdlinks.plata_texts WHERE uindex = ? AND language = ?";
        $stmt = $conn->prepare($sql_select);
        $stmt->bind_param("is", $uindex, $language);
        $stmt->execute();
        $result = $stmt->get_result();
        $data[$language] = $result->fetch_assoc();
    }
} else {
    // Default empty values for new entries
    foreach ($languages as $language) {
        $data[$language] = ['name' => '', 'text' => ''];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Task</title>
</head>

<body>

    <h2>Edit Task</h2>

    <!-- Example form for editing data -->
    <form method="POST" action="">

        <!-- Form field to edit task name -->
        <label>TEXT NAME:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($data[$languages[0]]['name'] ?? ''); ?>"><br>

        <?php foreach ($languages as $language): ?>
            <!-- Form fields to edit task data for each language -->
            <label>TEXT <?php echo strtoupper($language); ?>:</label>
            <input type="text" name="task_goal_<?php echo $language; ?>" value="<?php echo htmlspecialchars($data[$language]['text'] ?? ''); ?>"><br>
        <?php endforeach; ?>

        <!-- Form submission button -->
        <a href="index.php">Back</a>
        <button type="submit">Save Changes</button>
    </form>

</body>

</html>
