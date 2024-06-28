<?php
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

include 'conexao.php';
?>
<?php
// Check if 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to select specific data by ID
    $sql = "SELECT name, logo, type FROM granna80_bdlinks.dex_liquidity WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No record found";
        exit();
    }
} else {
    echo "No ID provided";
    exit();
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];

    // Check if a new logo file was uploaded
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
        $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        $uploadOk = 1;
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["logo"]["size"] > 10000000) { // 10MB
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                // Update logo path only if upload was successful
                $logo = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                $uploadOk = 0;
            }
        }
    } else {
        // Keep current logo path if no new file was uploaded
        $logo = $row['logo'];
    }

    // If logo upload was successful or no new file was uploaded, update the record in the database
    if ($uploadOk || empty($_FILES['logo']['name'])) {
        $sql = "UPDATE granna80_bdlinks.dex_liquidity SET name=?, logo=?, type=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind parameters and execute statement
        $stmt->bind_param("sssi", $name, $logo, $type, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
            // Redirect to the edit page again after update
            echo "<script>window.location.href = 'edit.php?id=$id';</script>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dex</title>
</head>

<body>
    <h1>Edit Dex</h1>

    <br>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"><br>

        <label for="type">Type:</label><br>
        <select id="type" name="type">
            <option value="dex" <?php if ($row['type'] == 'dex') echo 'selected'; ?>>DEX</option>
            <option value="cex" <?php if ($row['type'] == 'cex') echo 'selected'; ?>>CEX</option>
            <option value="lending" <?php if ($row['type'] == 'lending') echo 'selected'; ?>>Lending</option>
            <option value="farming" <?php if ($row['type'] == 'farming') echo 'selected'; ?>>Farming</option>
            <option value="locker" <?php if ($row['type'] == 'locker') echo 'selected'; ?>>Locker</option>
        </select><br>


        <label for="logo">Logo:</label><br>
        <img src="<?php echo $row['logo']; ?>" width="200" height="200" alt="Logo"><br>
        <input type="file" id="logo" name="logo"><br>
        <br>

        <input type="submit" value="Update">
    </form>
    <a href="index.php">Back to List</a>
</body>

</html>