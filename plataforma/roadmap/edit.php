<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if uindex is passed
if (!isset($_GET['uindex'])) {
    echo "Error: uindex not provided.";
    exit();
}

$uindex = $_GET['uindex'];

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

// Get available languages
$languages = fetchLanguages($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and handle empty values
    $task_date = $_POST['task_date'] ?? '';
    $task_done = isset($_POST['task_done']) ? 1 : 0;
    $task_highlighted = isset($_POST['task_highlighted']) ? 1 : 0;
    $task_goals = [];
    $task_goals_mobile = [];

    foreach ($languages as $language) {
        $task_goals[$language] = $_POST['task_goal_' . $language] ?? '';
        $task_goals_mobile[$language] = $_POST['task_goal_' . $language . '_mobile'] ?? '';
    }

    // Update or insert data for each language and device type
    foreach ($languages as $language) {
        $task_goal = $task_goals[$language];
        $task_goal_mobile = $task_goals_mobile[$language];

        // Check if record exists for desktop
        $sql_check_desktop = "SELECT * FROM granna80_bdlinks.roadmap WHERE uindex='$uindex' AND language='$language' AND device_type='desktop'";
        $result_check_desktop = $conn->query($sql_check_desktop);

        if ($result_check_desktop->num_rows > 0) {
            // Update existing desktop record
            $sql_update_desktop = "
                UPDATE granna80_bdlinks.roadmap
                SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, task_goal='$task_goal'
                WHERE uindex='$uindex' AND language='$language' AND device_type='desktop'
            ";
            $conn->query($sql_update_desktop);
        } else {
            // Insert new desktop record
            $sql_insert_desktop = "
                INSERT INTO granna80_bdlinks.roadmap (uindex, language, device_type, task_date, task_done, task_highlighted, task_goal)
                VALUES ('$uindex', '$language', 'desktop', '$task_date', $task_done, $task_highlighted, '$task_goal')
            ";
            $conn->query($sql_insert_desktop);
        }

        // Check if record exists for mobile
        $sql_check_mobile = "SELECT * FROM granna80_bdlinks.roadmap WHERE uindex='$uindex' AND language='$language' AND device_type='mobile'";
        $result_check_mobile = $conn->query($sql_check_mobile);

        if ($result_check_mobile->num_rows > 0) {
            // Update existing mobile record
            $sql_update_mobile = "
                UPDATE granna80_bdlinks.roadmap
                SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, task_goal='$task_goal_mobile'
                WHERE uindex='$uindex' AND language='$language' AND device_type='mobile'
            ";
            $conn->query($sql_update_mobile);
        } else {
            // Insert new mobile record
            $sql_insert_mobile = "
                INSERT INTO granna80_bdlinks.roadmap (uindex, language, device_type, task_date, task_done, task_highlighted, task_goal)
                VALUES ('$uindex', '$language', 'mobile', '$task_date', $task_done, $task_highlighted, '$task_goal_mobile')
            ";
            $conn->query($sql_insert_mobile);
        }
    }

    // Redirect after update
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

// Fetch existing data for the given uindex
$data = [];
foreach ($languages as $language) {
    // Fetch desktop data
    $result_desktop = $conn->query("SELECT * FROM granna80_bdlinks.roadmap WHERE uindex='$uindex' AND language='$language' AND device_type='desktop'");
    $data[$language]['desktop'] = $result_desktop->fetch_assoc();

    // Fetch mobile data
    $result_mobile = $conn->query("SELECT * FROM granna80_bdlinks.roadmap WHERE uindex='$uindex' AND language='$language' AND device_type='mobile'");
    $data[$language]['mobile'] = $result_mobile->fetch_assoc();
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
    <!-- Form field to edit task date -->
    <label>Task Date:</label>
    <input type="date" name="task_date" value="<?php echo isset($data['en']['desktop']['task_date']) ? htmlspecialchars($data['en']['desktop']['task_date']) : ''; ?>"><br>
    
    <!-- Form field to edit task completion -->
    <label>Task Done:</label>
    <input type="checkbox" name="task_done" <?php echo (isset($data['en']['desktop']['task_done']) && $data['en']['desktop']['task_done'] == 1) ? 'checked' : ''; ?>><br>
    
    <!-- Form field to edit task highlight -->
    <label>Task Highlighted:</label>
    <input type="checkbox" name="task_highlighted" <?php echo (isset($data['en']['desktop']['task_highlighted']) && $data['en']['desktop']['task_highlighted'] == 1) ? 'checked' : ''; ?>><br>
    
    <h3>Desktop</h3>
    <?php foreach ($languages as $language): ?>
        <!-- Form fields to edit task data for each language -->
        <label>Task Goal <?= strtoupper($language) ?>:</label>
        <input type="text" name="task_goal_<?= $language ?>" value="<?php echo isset($data[$language]['desktop']['task_goal']) ? htmlspecialchars($data[$language]['desktop']['task_goal']) : ''; ?>"><br>
    <?php endforeach; ?>

    <h3>Mobile</h3>
    <?php foreach ($languages as $language): ?>
        <!-- Form fields to edit task data for each language -->
        <label>Task Goal <?= strtoupper($language) ?> Mobile:</label>
        <input type="text" name="task_goal_<?= $language ?>_mobile" value="<?php echo isset($data[$language]['mobile']['task_goal']) ? htmlspecialchars($data[$language]['mobile']['task_goal']) : ''; ?>"><br>
    <?php endforeach; ?>

    <!-- Add more form fields as needed -->

    <!-- Form submission button -->
    <a href="index.php">Back</a>
    <button type="submit">Save Changes</button>
</form>

</body>
</html>
