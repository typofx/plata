<?php
session_start();
include 'conexao.php';
include 'crud.php';

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    echo '<script>window.location.href = "../index.php";</script>';
    exit();
}

$userName = $_SESSION["user_email"];
$task_id = 0;
$task_date = "";
$task_goal = "";
$task_done = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
    if (isset($_POST["task_id"])) {
        if (editTask($_POST["task_id"], $_POST["task_date"], $_POST["task_goal"], $_POST["task_done"])) {
            echo "Task edited successfully.";
        } else {
            echo "Error editing the task.";
        }
    } else {
        if (createTask($_POST["task_date"], $_POST["task_goal"], $_POST["task_done"])) {
            echo "Task created successfully.";
        } else {
            echo "Error creating the task.";
        }
    }
}

if (isset($_GET["delete"])) {
    if (deleteTask($_GET["delete"])) {
        echo "Task deleted successfully.";
    } else {
        echo "Error deleting the task.";
    }
}

$tasks = listTasks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task CRUD</title>
</head>
<body>
    <h1>RoadMap</h1>
    <form method="post">
        <?php if (isset($_GET["edit"])): ?>
            <input type="hidden" name="task_id" value="<?php echo $_GET["edit"]; ?>">
        <?php endif; ?>
        Date: <input type="date" name="task_date" value="<?php echo $task_date; ?>"><br>
        Goal: <input type="text" name "task_goal" value="<?php echo $task_goal; ?>"><br>
        Done: <input type="checkbox" name="task_done" value="1" <?php if ($task_done == 1) echo "checked"; ?>><br>
        <button type="submit">Submit</button>
    </form>

    <h2>RoadMap - List</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <?php echo $task["task_date"]; ?> - <?php echo $task["task_goal"]; ?> - Done: <?php echo ($task["task_done"] == 1 ? "Yes" : "No"); ?> -
                <a href="edit_task.php?task_id=<?php echo $task["task_id"]; ?>&task_date=<?php echo $task["task_date"]; ?>&task_goal=<?php echo $task["task_goal"]; ?>&task_done=<?php echo $task["task_done"]; ?>">Edit</a>
                |
                <a href="?delete=<?php echo $task["task_id"]; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
