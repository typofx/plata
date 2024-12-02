<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week_id = $_GET['week'];
    $employee_id = $_GET['employee_id'];

    // Prepare the SQL statement to delete the week
    $sql_delete = "DELETE FROM granna80_bdlinks.work_weeks WHERE work_week = ? AND employee_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $week_id, $employee_id);

    if ($stmt_delete->execute()) {
        echo "Week deleted successfully.";
   
        echo "<script>window.location.href='work_weeks_ireland.php?employee_id=" . $employee_id . "';</script>";
        exit();
    } else {
        echo "Error deleting week: " . $conn->error;
    }
} else {
    echo "Invalid request.";
    exit();
}

$stmt_delete->close();
$conn->close();
?>
