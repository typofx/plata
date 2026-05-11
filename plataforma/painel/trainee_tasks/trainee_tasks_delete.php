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

if (isset($_POST['id']) && !empty($_POST['id'])) {
    include DB_FILE;

    $id = $_POST['id'];

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM granna80_bdlinks.trainee_tasks WHERE trainee_task_code = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        trigger_error($conn->error, E_USER_ERROR);
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