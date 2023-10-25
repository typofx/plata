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
$task_highlighted = 0; // Adicionado um valor padrão para o campo "highlighted"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["task_id"])) {
        $task_id = $_POST["task_id"];
        $task_date = $_POST["task_date"];
        $task_goal = $_POST["task_goal"];
        $task_done = isset($_POST["task_done"]) ? 1 : 0;
        $task_highlighted = isset($_POST["task_highlighted"]) ? 1 : 0; // Atualização do valor de "highlighted"

        if (editTask($task_id, $task_date, $task_goal, $task_done, $task_highlighted)) {
            echo "Task edited successfully.";
        } else {
            echo "Error editing the task.";
        }
    } else {
        if (createTask($task_date, $task_goal, $task_done, $task_highlighted)) {
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
    <title>Edit RoadMap - ES</title>
</head>
<body>
    <h1>RoadMap ES</h1>
    <form method="post">
        <?php if (isset($_GET["edit"])): ?>
            <input type="hidden" name="task_id" value="<?php echo $_GET["edit"]; ?>">
        <?php endif; ?>
        Date: <input type="date" name="task_date" value="<?php echo $task_date; ?>"><br>
        Goal: <input type="text" name="task_goal" value="<?php echo $task_goal; ?>"><br>
        Done: <input type="checkbox" name="task_done" value="1" <?php if ($task_done == 1) echo "checked"; ?>><br>
        Highlighted:
        <input type="radio" name="task_highlighted" value="1" <?php if ($task_highlighted == 1) echo "checked"; ?>> Yes
        <input type="radio" name="task_highlighted" value="0" <?php if ($task_highlighted == 0) echo "checked"; ?>> No
        <button type="submit">Submit</button>
    </form>

    <h2>RoadMap ES - List</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <?php echo $task["task_date"]; ?> - <?php echo $task["task_goal"]; ?> - Done: <?php echo ($task["task_done"] == 1 ? "Yes" : "No"); ?> - Highlighted: <?php echo ($task["task_highlighted"] == 1 ? "Yes" : "No"); ?>
                <a href="edit_task.php?task_id=<?php echo $task["task_id"]; ?>&task_date=<?php echo $task["task_date"]; ?>&task_goal=<?php echo $task["task_goal"]; ?>&task_done=<?php echo $task["task_done"]; ?>&task_highlighted=<?php echo $task["task_highlighted"]; ?>">Edit</a>
                |
                <a href="?delete=<?php echo $task["task_id"]; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="../index.php">RoadMap EN</a><br>
    <a href="../PT/index.php">RoadMap PT</a>
</body>
</html>
