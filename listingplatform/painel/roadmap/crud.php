<?php
include 'conexao.php';

function createTask($task_date, $task_goal, $task_done) {
    global $conn;

    $task_date = mysqli_real_escape_string($conn, $task_date);
    $task_goal = mysqli_real_escape_string($conn, $task_goal);
    $task_done = (int)$task_done;

    $sql = "INSERT INTO granna80_bdlinks.task_list (task_date, task_goal, task_done) VALUES ('$task_date', '$task_goal', $task_done)";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function editTask($task_id, $task_date, $task_goal, $task_done) {
    global $conn;

    $task_id = (int)$task_id;
    $task_date = mysqli_real_escape_string($conn, $task_date);
    $task_goal = mysqli_real_escape_string($conn, $task_goal);
    $task_done = (int)$task_done;

    $sql = "UPDATE granna80_bdlinks.task_list SET task_date = '$task_date', task_goal = '$task_goal', task_done = $task_done WHERE task_id = $task_id";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteTask($task_id) {
    global $conn;

    $task_id = (int)$task_id;

    $sql = "DELETE FROM granna80_bdlinks.task_list WHERE task_id = $task_id";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function listTasks() {
    global $conn;

    $tasks = array();

    $result = mysqli_query($conn, "SELECT * FROM granna80_bdlinks.task_list");

    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }

    return $tasks;
}
?>
