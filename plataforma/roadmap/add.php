<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Function to fetch available languages
function fetchLanguages($conn, $visibleOnly = false) {
    $sql = "SELECT code FROM granna80_bdlinks.languages";
    if ($visibleOnly) {
        $sql .= " WHERE visible = 1";
    }
    $result = $conn->query($sql);

    $languages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $languages[] = $row['code'];
        }
    }
    return $languages;
}

// Function to get the next uindex based on the max uindex for "en" and "desktop"
function getNextUindex($conn) {
    // Query to find the highest numeric uindex for "en" and "desktop"
    $sql = "SELECT MAX(CAST(uindex AS UNSIGNED)) AS max_uindex FROM granna80_bdlinks.roadmap WHERE language='en' AND device_type='desktop'";
    $result = $conn->query($sql);
    
    $row = $result->fetch_assoc();
    $max_uindex = $row['max_uindex'] ?? 0;
    
    return $max_uindex + 1;
}

// Get available languages
$languages = fetchLanguages($conn);

// Get the next uindex
$uindex = getNextUindex($conn);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data and handle empty values
    $task_date = $_POST['task_date'] ?? '';
    $task_done = isset($_POST['task_done']) ? 1 : 0;
    $task_highlighted = isset($_POST['task_highlighted']) ? 1 : 0;
    $task_goals = [];
    $task_goals_mobile = [];

    foreach ($languages as $language) {
        $task_goals[$language] = $_POST['task_goal_' . $language] ?? '';
        $task_goals_mobile[$language] = $_POST['task_goal_' . $language . '_mobile'] ?? '';
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.roadmap (uindex, task_date, task_done, task_highlighted, language, device_type, task_goal) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Check if statement preparation was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind and execute statements for each language and device type
    $device_types = ['desktop', 'mobile'];
    
    foreach ($languages as $language) {
        foreach ($device_types as $device_type) {
            $task_goal = $device_type === 'mobile' ? $task_goals_mobile[$language] : $task_goals[$language];
            $stmt->bind_param("issssss", $uindex, $task_date, $task_done, $task_highlighted, $language, $device_type, $task_goal);
            if (!$stmt->execute()) {
                die('Execute failed: ' . htmlspecialchars($stmt->error));
            }
        }
    }

    // Close the statement
    $stmt->close();

    // Redirect after insertion
    echo '<script>window.location.href = "index.php";</script>';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Task</title>
</head>
<body>

<h2>Add New Task</h2>

<!-- Form to add a new task -->
<form method="POST" action="">
    <!-- Form field to add task date -->
    <label>Task Date:</label>
    <input type="date" name="task_date" required><br>

    <!-- Form field to add task completion -->
    <label>Task Done:</label>
    <input type="checkbox" id="task_done" name="task_done"><br>

    <!-- Form field to add task highlight -->
    <label>Task Highlighted:</label>
    <input type="checkbox" id="task_highlighted" name="task_highlighted"><br>

    <h3>Desktop</h3>

    <?php foreach ($languages as $language): ?>
        <!-- Form fields to add task data for each language -->
        <label>Task Goal <?= strtoupper($language) ?>:</label>
        <input type="text" name="task_goal_<?= $language ?>" ><br>
    <?php endforeach; ?>

    <h3>Mobile</h3>

    <?php foreach ($languages as $language): ?>
        <!-- Form fields to add task data for each language for mobile -->
        <label>Task Goal <?= strtoupper($language) ?> Mobile:</label>
        <input type="text" name="task_goal_<?= $language ?>_mobile" ><br>
    <?php endforeach; ?>

    <!-- Form submission button -->
    <a href="index.php">Back</a>
    <button type="submit">Add Task</button>
</form>

</body>
</html>
