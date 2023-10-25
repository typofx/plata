<?php
include 'conexao.php';

function createTask($task_date, $task_goal, $task_done, $task_highlighted) {
    global $conn;

    $task_date = mysqli_real_escape_string($conn, $task_date);
    $task_goal = mysqli_real_escape_string($conn, $task_goal);
    $task_done = (int)$task_done;
    $task_highlighted = (int)$task_highlighted; // Convert "highlighted" to an integer

    $sql = "INSERT INTO granna80_bdlinks.roadmap_es (task_date, task_goal, task_done, task_highlighted) 
            VALUES ('$task_date', '$task_goal', $task_done, $task_highlighted)";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function editTask($task_id, $task_date, $task_goal, $task_done, $task_highlighted) {
    global $conn;

    $task_id = (int)$task_id;
    $task_date = mysqli_real_escape_string($conn, $task_date);
    $task_goal = mysqli_real_escape_string($conn, $task_goal);
    $task_done = (int)$task_done;
    $task_highlighted = (int)$task_highlighted; // Convert "highlighted" to an integer

    $sql = "UPDATE granna80_bdlinks.roadmap_es
            SET task_date = '$task_date', task_goal = '$task_goal', task_done = $task_done, task_highlighted = $task_highlighted 
            WHERE task_id = $task_id";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function deleteTask($task_id) {
    global $conn;

    $task_id = (int)$task_id;

    $sql = "DELETE FROM granna80_bdlinks.roadmap_es WHERE task_id = $task_id";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}

function listTasks() {
    global $conn;

    $tasks = array();

    $result = mysqli_query($conn, "SELECT * FROM granna80_bdlinks.roadmap_es");

    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }

    return $tasks;
}
?>
