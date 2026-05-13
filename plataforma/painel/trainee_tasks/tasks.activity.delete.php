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


$task_code = $_POST['task_code'] ?? '';
$activity_code = $_POST['activity_code'] ?? '';

if (empty($task_code) || empty($activity_code)) {
    echo "Task Code and Activity Code are required.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.trainee_activity WHERE trainee_task_code = ? AND trainee_activity_code = ?");
$stmt->bind_param("ss", $task_code, $activity_code);

if ($stmt->execute()) {
    $_SESSION['message'] = "Activity deleted successfully.";
} else {
    error_log("Tasks: Error deleting activity: " . $stmt->error);
    $_SESSION['message'] = "An error occurred. Please try again.";
}

$stmt->close();
$conn->close();

echo "<script>window.location.href = 'index';</script>";
exit();
?>
