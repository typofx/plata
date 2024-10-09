<?php
// Include the database connection file
include 'conexao.php';

// Check if the form data was received via GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Loop through the form data to update the checkboxes
    foreach ($_GET['id'] as $id) {
        // Fallback to 0 if the checkbox is unchecked
        $guest = isset($_GET['guest'][$id]) ? 1 : 0;
        $trainee = isset($_GET['trainee'][$id]) ? 1 : 0;
        $admin = isset($_GET['admin'][$id]) ? 1 : 0;
        $root = isset($_GET['root'][$id]) ? 1 : 0;
        $office = isset($_GET['office'][$id]) ? 1 : 0;
        $block = isset($_GET['block'][$id]) ? 1 : 0;

        // Update the record in the database
        $updateQuery = "UPDATE granna80_bdlinks.user_permissions 
                        SET guest = $guest, trainee = $trainee, admin = $admin, root = $root, office = $office, block = $block 
                        WHERE id = $id";
        
        // Check if the update query was successful
        if (!mysqli_query($conn, $updateQuery)) {
            echo "Error updating record with ID $id: " . mysqli_error($conn) . "<br>";
        }
    }
    
    // Close the database connection
    mysqli_close($conn);

    // Redirect back to index.php after saving
    //echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>
