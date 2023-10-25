<?php
session_start();
include 'conexao.php';
include 'crud.php';

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

$task_id = $_GET["task_id"]; // Retrieve task_id from the URL
$task_date = $_GET["task_date"]; // Retrieve task_date from the URL
$task_goal = $_GET["task_goal"]; // Retrieve task_goal from the URL
$task_done = $_GET["task_done"]; // Retrieve task_done from the URL
$task_highlighted = $_GET["task_highlighted"]; // Retrieve task_highlighted from the URL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["task_id"])) {
        $task_id = $_POST["task_id"];
        $task_date = $_POST["task_date"];
        $task_goal = $_POST["task_goal"];
        $task_done = isset($_POST["task_done"]) ? 1 : 0;
        $task_highlighted = isset($_POST["task_highlighted"]) ? 1 : 0; // Add "highlighted"

        if (editTask($task_id, $task_date, $task_goal, $task_done, $task_highlighted)) {
            echo "Task edited successfully.";
        } else {
            echo "Error editing the task.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>
    <form method="post">
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
        Date: <input type="date" name="task_date" value="<?php echo $task_date; ?>"><br>
        Goal: <input type="text" name="task_goal" value="<?php echo $task_goal; ?>"><br>
        Done: <input type="checkbox" name="task_done" value="1" <?php if ($task_done == 1) echo "checked"; ?>><br>
        Highlighted:
        <input type="radio" name="task_highlighted" value="1" <?php if ($task_highlighted == 1) echo "checked"; ?> > Yes
        <input type="radio" name="task_highlighted" value="0" <?php if ($task_highlighted == 0) echo "checked"; ?> > No
        <button type="submit">Save</button>
    </form>
</body>
</html>
