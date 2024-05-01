<?php
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

include 'conexao.php';
include 'crud.php';

// Variáveis para o formulário
$task_date = "";
$task_goal = "";
$task_done = 0;
$task_highlighted = 0;

// Processar a exclusão, se necessário
if (isset($_GET["delete"])) {
    $task_id_to_delete = $_GET["delete"];
    
    if (deleteTask($task_id_to_delete)) {
        echo "Task deleted successfully.";
    } else {
        echo "Error deleting the task.";
    }
}

// Processar o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "add") {
        // Verifique se o campo 'task_goal' foi definido no array $_POST
        if (isset($_POST["task_goal"])) {
            $task_date = $_POST["task_date"];
            $task_goal = $_POST["task_goal"];
            $task_done = isset($_POST["task_done"]) ? 1 : 0;
            $task_highlighted = isset($_POST["task_highlighted"]) ? 1 : 0;

            if (createTask($task_date, $task_goal, $task_done, $task_highlighted)) {
                echo "Task added successfully.";
            } else {
                echo "Error adding the task.";
            }
        }
    }
}

$tasks = listTasks();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit RoadMap - EN</title>
    <style>
        .highlighted {
            background-color: yellow;
            display: inline;
            padding: 0;
        }
    </style>
</head>

<body>
    <h1>RoadMap EN</h1>
    <form method="post">
        <input type="hidden" name="action" value="add"> <!-- Adicione este campo oculto -->
        Date: <input type="date" name="task_date" value="<?php echo $task_date; ?>"><br>
        Goal: <input type="text" name="task_goal" value="<?php echo $task_goal; ?>"><br>
        Done: <input type="checkbox" name="task_done" value="1" <?php if ($task_done == 1) echo "checked"; ?>><br>
        Highlighted:
        <input type="checkbox" name="task_highlighted" value="1" <?php if ($task_highlighted == 1) echo "checked"; ?>> Yes
       
        <button type="submit">Add Task</button> <!-- Atualize o texto do botão para "Add Task" -->
    </form>

    <h2>RoadMap - List</h2>
    <ul>
        <?php if (!empty($tasks)) : ?>
            <?php foreach ($tasks as $task) : ?>
                <li>
                    <?php if ($task["task_done"] == 1) : ?>
                        &#x2713;
                    <?php endif; ?>
                    <?php if ($task["task_highlighted"] == 1) : ?>
                        <span class="highlighted">
                            <?php echo $task["task_date"]; ?> - <?php echo $task["task_goal"]; ?>
                        </span>
                    <?php else : ?>
                        <?php echo $task["task_date"]; ?> - <?php echo $task["task_goal"]; ?>
                    <?php endif; ?>
                    <a href="edit_task.php?task_id=<?php echo $task["task_id"]; ?>&task_date=<?php echo $task["task_date"]; ?>&task_goal=<?php echo $task["task_goal"]; ?>&task_done=<?php echo $task["task_done"]; ?>&task_highlighted=<?php echo $task["task_highlighted"]; ?>">Edit</a>
                    |
                    <a href="?delete=<?php echo $task["task_id"]; ?>">Delete</a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>No tasks found.</li>
        <?php endif; ?>
    </ul>
    <a href="ES">RoadMap ES</a><br>
    <a href="PT">RoadMap PT</a>
</body>

</html>
