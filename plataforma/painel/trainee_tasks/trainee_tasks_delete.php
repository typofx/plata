<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php


// Bloqueio de acesso para não-root
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    include 'conexao.php';

    $id = $_GET['id'];

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
        echo "<script>window.location.href = 'index.php';</script>";
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