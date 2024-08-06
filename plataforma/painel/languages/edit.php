<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
?>
<?php
?>
<?php
// Include the file for database connection
include 'conexao.php';

// Check if the IDs were passed
if (isset($_GET['id_en']) && isset($_GET['id_es']) && isset($_GET['id_pt'])) {
    // Get the IDs passed via GET
    $id_en = $_GET['id_en'];
    $id_es = $_GET['id_es'];
    $id_pt = $_GET['id_pt'];
  

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data

        $task_goal_en = $_POST['task_goal_en'];
        $task_goal_es = $_POST['task_goal_es'];
        $task_goal_pt = $_POST['task_goal_pt'];
        $name = $_POST['name'];
      

        // Update data in the English table
        $sql_update_en = "UPDATE granna80_bdlinks.plata_texts SET name='$name', text='$task_goal_en' WHERE id=$id_en";
        $conn->query($sql_update_en);

        // Update data in the Spanish table
        $sql_update_es = "UPDATE granna80_bdlinks.plata_texts SET name='$name', text='$task_goal_es' WHERE id=$id_es";
        $conn->query($sql_update_es);

        // Update data in the Portuguese table
        $sql_update_pt = "UPDATE granna80_bdlinks.plata_texts SET name='$name', text='$task_goal_pt' WHERE id=$id_pt";
        $conn->query($sql_update_pt);



        // Redirect after update
        echo "<script>window.location.href = 'index.php';</script>"; // Redirect
        exit();
    }


    $sql_select_en = "SELECT * FROM granna80_bdlinks.plata_texts WHERE id = $id_en";
    $result_select_en = $conn->query($sql_select_en);
    $row_en = $result_select_en->fetch_assoc();


    $sql_select_es = "SELECT * FROM granna80_bdlinks.plata_texts WHERE id = $id_es";
    $result_select_es = $conn->query($sql_select_es);
    $row_es = $result_select_es->fetch_assoc();


    $sql_select_pt = "SELECT * FROM granna80_bdlinks.plata_texts WHERE id = $id_pt";
    $result_select_pt = $conn->query($sql_select_pt);
    $row_pt = $result_select_pt->fetch_assoc();


 
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



        

        <!-- Form fields to edit task data in English -->
        <label>TEXT NAME:</label>
        <input type="text" name="name" value="<?php echo $row_en['name']; ?>"><br>


        <label>TEXT EN:</label>
        <input type="text" name="task_goal_en" value="<?php echo $row_en['text']; ?>"><br>

        <!-- Form fields to edit task data in Spanish -->
        <label>TEXT ES:</label>
        <input type="text" name="task_goal_es" value="<?php echo $row_es['text']; ?>"><br>

        <!-- Form fields to edit task data in Portuguese -->
        <label>TEXT PT:</label>
        <input type="text" name="task_goal_pt" value="<?php echo $row_pt['text']; ?>"><br>

     



        <!-- Form submission button -->
        <a href="index.php">Back</a>
        <button type="submit">Save Changes</button>
    </form>

</body>

</html>