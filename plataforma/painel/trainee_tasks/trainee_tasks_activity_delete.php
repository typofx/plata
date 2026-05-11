<?php
include __DIR__ . '/bootstrap.php';
include AUTH_FILE;

// Block non-root access
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index");
    exit();
}

// CSRF validation
if (!isset($_POST['token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['token'])) {
    die("Invalid CSRF token.");
}

include DB_FILE;

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
    $_SESSION['message'] = "Error deleting activity: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo "<script>window.location.href = 'index';</script>";
exit();
?>