<?php 
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
} 
?>
<?php
// Include the file for database connection
include 'conexao.php';

// Check if the IDs were passed
if (isset($_GET['id_en']) && isset($_GET['id_es']) && isset($_GET['id_pt']) && isset($_GET['id_en_mobile']) && isset($_GET['id_es_mobile']) && isset($_GET['id_pt_mobile'])) {
    // Get the IDs passed via GET
    $id_en = $_GET['id_en'];
    $id_es = $_GET['id_es'];
    $id_pt = $_GET['id_pt'];
    $id_en_mobile = $_GET['id_en_mobile'];
    $id_es_mobile = $_GET['id_es_mobile'];
    $id_pt_mobile = $_GET['id_pt_mobile'];

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

        // Update data in the English table
        $sql_update_en = "UPDATE granna80_bdlinks.roadmap_en SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_en' WHERE task_id=$id_en";
        $conn->query($sql_update_en);

        // Update data in the Spanish table
        $sql_update_es = "UPDATE granna80_bdlinks.roadmap_es SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_es' WHERE task_id=$id_es";
        $conn->query($sql_update_es);

        // Update data in the Portuguese table
        $sql_update_pt = "UPDATE granna80_bdlinks.roadmap_pt SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_pt' WHERE task_id=$id_pt";
        $conn->query($sql_update_pt);

        // Update data in the English table for mobile devices
        $sql_update_en_mobile = "UPDATE granna80_bdlinks.roadmap_en_mobile SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_en_mobile' WHERE task_id=$id_en_mobile";
        $conn->query($sql_update_en_mobile);

        // Update data in the Spanish table for mobile devices
        $sql_update_es_mobile = "UPDATE granna80_bdlinks.roadmap_es_mobile SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_es_mobile' WHERE task_id=$id_es_mobile";
        $conn->query($sql_update_es_mobile);

        // Update data in the Portuguese table for mobile devices
        $sql_update_pt_mobile = "UPDATE granna80_bdlinks.roadmap_pt_mobile SET task_date='$task_date', task_done=$task_done, task_highlighted=$task_highlighted, semester='$semester', task_goal='$task_goal_pt_mobile' WHERE task_id=$id_pt_mobile";
        $conn->query($sql_update_pt_mobile);

        // Redirect after update
        echo "<script>window.location.href = 'index.php';</script>"; // Redirect
        exit();
    }


    $sql_select_en = "SELECT * FROM granna80_bdlinks.roadmap_en WHERE task_id = $id_en";
    $result_select_en = $conn->query($sql_select_en);
    $row_en = $result_select_en->fetch_assoc();


    $sql_select_es = "SELECT * FROM granna80_bdlinks.roadmap_es WHERE task_id = $id_es";
    $result_select_es = $conn->query($sql_select_es);
    $row_es = $result_select_es->fetch_assoc();

  
    $sql_select_pt = "SELECT * FROM granna80_bdlinks.roadmap_pt WHERE task_id = $id_pt";
    $result_select_pt = $conn->query($sql_select_pt);
    $row_pt = $result_select_pt->fetch_assoc();

 
    $sql_select_en_mobile = "SELECT * FROM granna80_bdlinks.roadmap_en_mobile WHERE task_id = $id_en_mobile";
    $result_select_en_mobile = $conn->query($sql_select_en_mobile);
    $row_en_mobile = $result_select_en_mobile->fetch_assoc();

    $sql_select_es_mobile = "SELECT * FROM granna80_bdlinks.roadmap_es_mobile WHERE task_id = $id_es_mobile";
    $result_select_es_mobile = $conn->query($sql_select_es_mobile);
    $row_es_mobile = $result_select_es_mobile->fetch_assoc();

 
    $sql_select_pt_mobile = "SELECT * FROM granna80_bdlinks.roadmap_pt_mobile WHERE task_id = $id_pt_mobile";
    $result_select_pt_mobile = $conn->query($sql_select_pt_mobile);
    $row_pt_mobile = $result_select_pt_mobile->fetch_assoc();


} else {

    echo "Error"; 
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>

</head>
<body>

<h2>Edit Task</h2>

<!-- Example form for editing data -->
<form method="POST" action="">
    <!-- Form field to edit task date -->
    <label>Task Date:</label>
    <input type="date" name="task_date" value="<?php echo $row_en['task_date']; ?>"><br>
    
    <!-- Form field to edit task completion -->
    <label>Task Done:</label>
    <input type="checkbox" name="task_done" <?php echo ($row_en['task_done'] == 1) ? 'checked' : ''; ?>><br>
    
    <!-- Form field to edit task highlight -->
    <label>Task Highlighted:</label>
    <input type="checkbox" name="task_highlighted" <?php echo ($row_en['task_highlighted'] == 1) ? 'checked' : ''; ?>><br>
    
    <!-- Form field to edit semester -->
    <label>Semester:</label>
    <input type="text" name="semester" value="<?php echo $row_en['semester']; ?>"><br>
    
<h3>Desktop</h3>

    <!-- Form fields to edit task data in English -->
    <label>Task Goal EN:</label>
    <input type="text" name="task_goal_en" value="<?php echo $row_en['task_goal']; ?>"><br>

    <!-- Form fields to edit task data in Spanish -->
    <label>Task Goal ES:</label>
    <input type="text" name="task_goal_es" value="<?php echo $row_es['task_goal']; ?>"><br>

    <!-- Form fields to edit task data in Portuguese -->
    <label>Task Goal PT:</label>
    <input type="text" name="task_goal_pt" value="<?php echo $row_pt['task_goal']; ?>"><br>

<h3>Mobile</h3>

    <!-- Form fields to edit task data in English for mobile devices -->
    <label>Task Goal EN Mobile:</label>
    <input type="text" name="task_goal_en_mobile" value="<?php echo $row_en_mobile['task_goal']; ?>"><br>

    <!-- Form fields to edit task data in Spanish for mobile devices -->
    <label>Task Goal ES Mobile:</label>
    <input type="text" name="task_goal_es_mobile" value="<?php echo $row_es_mobile['task_goal']; ?>"><br>

    <!-- Form fields to edit task data in Portuguese for mobile devices -->
    <label>Task Goal PT Mobile:</label>
    <input type="text" name="task_goal_pt_mobile" value="<?php echo $row_pt_mobile['task_goal']; ?>"><br>

    <!-- Add more form fields as needed -->

    <!-- Form submission button -->
    <a href="index.php">Back</a>
    <button type="submit">Save Changes</button>
</form>

</body>
</html>
