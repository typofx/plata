<?php
include 'conexao.php'; // Include database connection file

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the record corresponding to the ID
    $sql = "SELECT * FROM granna80_bdlinks.wallets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the record was found
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit;
    }
} else {
    echo "ID not provided.";
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $wallet = $_POST['wallet'];
    $logo = $_POST['logo'];
    $price = $_POST['price'];
    $decimal = $_POST['decimal'];
    $mobile = $_POST['mobile'];
    $desktop = $_POST['desktop'];
    $mod = $_POST['mod'];
    $tax = $_POST['tax'];
    $speed = $_POST['speed'];
    $connect = $_POST['connect'];
    $joining_fee = $_POST['joining_fee'];
    $api = $_POST['api'];
    $date = $_POST['date'];
    $score = $_POST['score'];

    // Initialize icon path
    $iconPath = $row['icon'];

    // Handle file upload for the icon
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
        $iconTmpPath = $_FILES['icon']['tmp_name'];
        $iconFileName = $_FILES['icon']['name'];
        $iconFileType = $_FILES['icon']['type'];

        // Define allowed file types
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($iconFileType, $allowedFileTypes)) {
            // Move the uploaded file to the desired directory
            $iconUploadPath = 'uploads/' . basename($iconFileName); // Make sure the 'uploads' directory exists
            if (move_uploaded_file($iconTmpPath, $iconUploadPath)) {
                $iconPath = $iconUploadPath; // Use the new icon path
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
        }
    }

    // Update the record in the database
    $updateSql = "UPDATE granna80_bdlinks.wallets SET wallet = ?, logo = ?, price = ?, decimal_flag = ?, mobile = ?, desktop = ?, mod_flag = ?, tax = ?, speed = ?, connect = ?, joining_fee = ?, api = ?, date = ?, score = ?, icon = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssssssssssssssi", $wallet, $logo, $price, $decimal, $mobile, $desktop, $mod, $tax, $speed, $connect, $joining_fee, $api, $date, $score, $iconPath, $id);

    if ($updateStmt->execute()) {
        echo "Record updated successfully.";
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wallet</title>
</head>
<body>

<h1>Edit Wallet</h1>

<form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
    <label for="icon">Icon (upload wallet icon):</label><br>
    <img src="uploads/<?php echo htmlspecialchars($row['icon']); ?>" alt="Current Icon" style="max-width: 100px; display: block; margin-bottom: 10px;">
    <span>Current Image: <?php echo htmlspecialchars($row['icon']); ?></span><br>
    <input type="file" name="icon"><br><br> <!-- File upload for icon (optional) -->

    <label for="wallet">Wallet Name:</label>
    <input type="text" name="wallet" value="<?php echo htmlspecialchars($row['wallet']); ?>" ><br><br>

    <label for="logo">Logo:</label>
    <select name="logo" >
        <option value="1" <?php echo $row['logo'] ? 'selected' : ''; ?>>Yes</option>
        <option value="0" <?php echo !$row['logo'] ? 'selected' : ''; ?>>No</option>
    </select><br><br>

    <label for="price">Price:</label>
    <select name="price" >
        <option value="1" <?php echo $row['price'] ? 'selected' : ''; ?>>Yes</option>
        <option value="0" <?php echo !$row['price'] ? 'selected' : ''; ?>>No</option>
    </select><br><br>

    <label for="decimal">Decimal:</label>
    <select name="decimal" >
        <option value="1" <?php echo $row['decimal_flag'] ? 'selected' : ''; ?>>Yes</option>
        <option value="0" <?php echo !$row['decimal_flag'] ? 'selected' : ''; ?>>No</option>
    </select><br><br>

    <label for="mobile">Mobile:</label>
    <select name="mobile" >
        <option value="1" <?php echo $row['mobile'] ? 'selected' : ''; ?>>Yes</option>
        <option value="0" <?php echo !$row['mobile'] ? 'selected' : ''; ?>>No</option>
    </select><br><br>

    <label for="desktop">Desktop:</label>
    <select name="desktop" >
        <option value="1" <?php echo $row['desktop'] ? 'selected' : ''; ?>>Yes</option>
        <option value="0" <?php echo !$row['desktop'] ? 'selected' : ''; ?>>No</option>
    </select><br><br>

    <label for="mod">MOD:</label>
    <select name="mod" >
        <option value="1" <?php echo $row['mod_flag'] ? 'selected' : ''; ?>>Yes</option>
        <option value="0" <?php echo !$row['mod_flag'] ? 'selected' : ''; ?>>No</option>
    </select><br><br>

    <label for="tax">Tax:</label>
    <input type="number" step="0.01" name="tax" value="<?php echo $row['tax']; ?>" ><br><br>

    <label for="speed">Speed:</label>
    <select name="speed" >
        <option value="excellent" <?php echo $row['speed'] === 'excellent' ? 'selected' : ''; ?>>Excellent</option>
        <option value="good" <?php echo $row['speed'] === 'good' ? 'selected' : ''; ?>>Good</option>
        <option value="poor" <?php echo $row['speed'] === 'poor' ? 'selected' : ''; ?>>Poor</option>
    </select><br><br>

    <label for="connect">Connect:</label>
    <select name="connect" >
        <option value="excellent" <?php echo $row['connect'] === 'excellent' ? 'selected' : ''; ?>>Excellent</option>
        <option value="good" <?php echo $row['connect'] === 'good' ? 'selected' : ''; ?>>Good</option>
        <option value="poor" <?php echo $row['connect'] === 'poor' ? 'selected' : ''; ?>>Poor</option>
    </select><br><br>

    <label for="joining_fee">Joining Fee:</label>
    <input type="number" step="0.01" name="joining_fee" value="<?php echo $row['joining_fee']; ?>" ><br><br>

    <label for="api">API:</label>
    <input type="text" name="api" value="<?php echo htmlspecialchars($row['api']); ?>" ><br><br>

    <label for="date">Date:</label>
    <input type="date" name="date" value="<?php echo $row['date']; ?>" ><br><br>

    <label for="score">Score:</label>
    <input type="number" step="0.01" name="score" value="<?php echo $row['score']; ?>" ><br><br>

    <input type="submit" value="Update Wallet"><br><br>
    <a href="index.php">[back]</a>
</form>

</body>
</html>
