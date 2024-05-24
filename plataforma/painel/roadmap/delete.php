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

    // Delete entry from the English table
    $sql_delete_en = "DELETE FROM granna80_bdlinks.roadmap_en WHERE task_id = $id_en";
    $conn->query($sql_delete_en);

    // Delete entry from the Spanish table
    $sql_delete_es = "DELETE FROM granna80_bdlinks.roadmap_es WHERE task_id = $id_es";
    $conn->query($sql_delete_es);

    // Delete entry from the Portuguese table
    $sql_delete_pt = "DELETE FROM granna80_bdlinks.roadmap_pt WHERE task_id = $id_pt";
    $conn->query($sql_delete_pt);

    // Delete entry from the English table for mobile devices
    $sql_delete_en_mobile = "DELETE FROM granna80_bdlinks.roadmap_en_mobile WHERE task_id = $id_en_mobile";
    $conn->query($sql_delete_en_mobile);

    // Delete entry from the Spanish table for mobile devices
    $sql_delete_es_mobile = "DELETE FROM granna80_bdlinks.roadmap_es_mobile WHERE task_id = $id_es_mobile";
    $conn->query($sql_delete_es_mobile);

    // Delete entry from the Portuguese table for mobile devices
    $sql_delete_pt_mobile = "DELETE FROM granna80_bdlinks.roadmap_pt_mobile WHERE task_id = $id_pt_mobile";
    $conn->query($sql_delete_pt_mobile);

    // Redirect after deletion
echo 'Delete success';
    exit();
} else {
    // If the IDs were not passed correctly, redirect or display an error message
    echo "Error"; // Redirect to an error page or back to the previous page
    exit();
}
?>
