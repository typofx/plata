<?php
// Include the file for database connection
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $task_date = $_POST['task_date'];
    $task_done = isset($_POST['task_done']) ? 1 : 0;
    $task_highlighted = isset($_POST['task_highlighted']) ? 1 : 0;
    $semester = $_POST['semester'];
    $task_goal_en = $_POST['task_goal_en'];
    $task_goal_es = $_POST['task_goal_es'];
    $task_goal_pt = $_POST['task_goal_pt'];
    $task_goal_en_mobile = $_POST['task_goal_en_mobile'];
    $task_goal_es_mobile = $_POST['task_goal_es_mobile'];
    $task_goal_pt_mobile = $_POST['task_goal_pt_mobile'];

    // Insert data into the English table
    $sql_insert_en = "INSERT INTO granna80_bdlinks.roadmap_en (task_date, task_done, task_highlighted, semester, task_goal) VALUES ('$task_date', $task_done, $task_highlighted, '$semester', '$task_goal_en')";
    $conn->query($sql_insert_en);

    // Get the ID of the last insertion in English
    $id_en = $conn->insert_id;

    // Insert data into the English mobile table
    $sql_insert_en_mobile = "INSERT INTO granna80_bdlinks.roadmap_en_mobile (task_date, task_done, task_highlighted, semester, task_goal) VALUES ('$task_date', $task_done, $task_highlighted, '$semester', '$task_goal_en_mobile')";
    $conn->query($sql_insert_en_mobile);

    // Insert data into the Spanish table
    $sql_insert_es = "INSERT INTO granna80_bdlinks.roadmap_es (task_date, task_done, task_highlighted, semester, task_goal) VALUES ('$task_date', $task_done, $task_highlighted, '$semester', '$task_goal_es')";
    $conn->query($sql_insert_es);

    // Insert data into the Portuguese table
    $sql_insert_pt = "INSERT INTO granna80_bdlinks.roadmap_pt (task_date, task_done, task_highlighted, semester, task_goal) VALUES ('$task_date', $task_done, $task_highlighted, '$semester', '$task_goal_pt')";
    $conn->query($sql_insert_pt);

    // Insert data into the Spanish mobile table
    $sql_insert_es_mobile = "INSERT INTO granna80_bdlinks.roadmap_es_mobile (task_date, task_done, task_highlighted, semester, task_goal) VALUES ('$task_date', $task_done, $task_highlighted, '$semester', '$task_goal_es_mobile')";
    $conn->query($sql_insert_es_mobile);

    // Insert data into the Portuguese mobile table
    $sql_insert_pt_mobile = "INSERT INTO granna80_bdlinks.roadmap_pt_mobile (task_date, task_done, task_highlighted, semester, task_goal) VALUES ('$task_date', $task_done, $task_highlighted, '$semester', '$task_goal_pt_mobile')";
    $conn->query($sql_insert_pt_mobile);

    // Redirect after insertion
    echo 'Successfully added';
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add New Task</title>
    <!-- Add your CSS styles here if necessary -->
</head>

<body>

    <h2>Add New Task</h2>

    <!-- Form to add a new task -->
    <form method="POST" action="">
        <!-- Form field to add task date -->
        <label>Task Date:</label>
        <input type="date" name="task_date"><br>

        <!-- Form field to add task completion -->
        <label>Task Done:</label>
        <input type="checkbox" id="task_done" name="task_done"><br>

        <!-- Form field to add task highlight -->
        <label>Task Highlighted:</label>
        <input type="checkbox" id="task_highlighted" name="task_highlighted"><br>

        <!-- Form field to add semester -->
        <label>Semester:</label>
        <input type="text" name="semester"><br>

        <h3>Desktop</h3>

        <!-- Form fields to add task data in English -->
        <label>Task Goal EN:</label>
        <input type="text" class="task-goal" name="task_goal_en" required><br>

        <!-- Form fields to add task data in Spanish -->
        <label>Task Goal ES:</label>
        <input type="text" class="task-goal" name="task_goal_es" required><br>

        <!-- Form fields to add task data in Portuguese -->
        <label>Task Goal PT:</label>
        <input type="text" class="task-goal" name="task_goal_pt" required><br>


        <h3>Mobile</h3>

        <!-- Form fields to add task data in English for mobile -->
        <label>Task Goal EN Mobile:</label>
        <input type="text" class="task-goal" name="task_goal_en_mobile" required><br>

        <!-- Form fields to add task data in Spanish for mobile -->
        <label>Task Goal ES Mobile:</label>
        <input type="text" class="task-goal" name="task_goal_es_mobile" required><br>

        <!-- Form fields to add task data in Portuguese for mobile -->
        <label>Task Goal PT Mobile:</label>
        <input type="text" class="task-goal" name="task_goal_pt_mobile" required><br>

        <!-- Add more form fields as needed -->

        <!-- Form submission button -->
        <a href="index.php">Back</a>
        <button type="submit">Add Task</button>
    </form>

    <!-- JavaScript script -->
    <script>
        window.onload = function() {
            updateTaskGoals();
            // Adding change event for the "Task Done" checkbox
            document.getElementById('task_done').addEventListener('change', function() {
                updateTaskGoals();
            });
        };

        function updateTaskGoals() {
            var taskDone = document.getElementById('task_done').checked;
            var taskGoals = document.querySelectorAll('.task-goal');

            taskGoals.forEach(function(taskGoal) {
                if (!taskDone && !taskGoal.value.startsWith('–')) {
                    taskGoal.value = '– ' + taskGoal.value;
                } else if (taskDone && taskGoal.value.startsWith('–')) {
                    taskGoal.value = taskGoal.value.substring(2);
                }
            });
        }
    </script>

</body>

</html>