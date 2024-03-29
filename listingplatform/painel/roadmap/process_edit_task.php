<?php
include 'conexao.php';
include 'crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["task_id"])) {
        $task_id = $_POST["task_id"];
        $task_date = $_POST["task_date"];
        $task_goal = $_POST["task_goal"];
        $task_done = isset($_POST["task_done"]) ? 1 : 0;
        $task_highlighted = isset($_POST["task_highlighted"]) ? 1 : 0; // Add "highlighted"

        if (editTask($task_id, $task_date, $task_goal, $task_done, $task_highlighted)) {
            // Task edited successfully, redirect back to the main page or display a success message
            echo 'Saved';
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        } else {
            // Error editing the task, redirect back to the edit page or display an error message
            echo    'erro';
            header("Location: edit_task.php?task_id=$task_id&error=true");
            exit();
        }
    }
}
?>
