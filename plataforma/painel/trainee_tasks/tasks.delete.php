<? include $_SERVER['DOCUMENT_ROOT']. '/plataforma/panel/is_logged.php'?>
<? include $_SERVER['DOCUMENT_ROOT']. '/.scr/conexao.php'?>
<?php
// Dynamic module loader: attempts to auto-detect module from directory, falls back to 'tasks' for stability.
$folder = file_exists(__DIR__ . '/' . basename(__DIR__) . '.language.helper.php') ? basename(__DIR__) : 'tasks';
include __DIR__ . '/' . $folder . '.language.helper.php';
?>

<?php

// Block non-root access
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index");
    exit();
}

// CSRF validation
if (!isset($_POST['token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['token'])) {
    die("Invalid CSRF token.");
}

if (isset($_POST['id']) && !empty($_POST['id'])) {

    $id = $_POST['id'];

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM granna80_bdlinks.trainee_tasks WHERE trainee_task_code = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Tasks: Delete prepare failed: " . $conn->error);
        die("An error occurred.");
    }

    // Bind parameters and execute the statement
    $stmt->bind_param('s', $id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect back to the payments page
        echo "<script>window.location.href = 'index';</script>";
    } else {
        echo "Error deleting record.";
    }

    // Close the statement
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid or unspecified ID.";
}
?>
