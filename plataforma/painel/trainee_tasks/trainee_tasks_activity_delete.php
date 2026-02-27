<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php


// Bloqueio de acesso para não-root
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index.php");
    exit();
}

include 'conexao.php';

$task_code = isset($_GET['task_code']) ? $_GET['task_code'] : '';
$activity_code = isset($_GET['activity_code']) ? $_GET['activity_code'] : '';

if (empty($task_code) || empty($activity_code)) {
    echo "Task Code and Activity Code are required.";
    exit;
}

// Optional: Add confirmation step here if not handled by JS confirm()
// But typically delete links have onclick="return confirm(...)"

$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.trainee_activity WHERE trainee_task_code = ? AND trainee_activity_code = ?");
$stmt->bind_param("ss", $task_code, $activity_code);

if ($stmt->execute()) {
    $_SESSION['message'] = "Activity deleted successfully.";
} else {
    $_SESSION['message'] = "Error deleting activity: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo "<script>window.location.href = 'index.php';</script>";
exit();
?>