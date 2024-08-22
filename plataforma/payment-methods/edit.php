<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);


    $sql = "SELECT * FROM granna80_bdlinks.payment_methods WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Method not found.");
    }

    $row = $result->fetch_assoc();
    $iconName = $row['icon'];
    $name = $row['name'];
    $description = $row['description'];
    $link = $row['link'];
    $visibled = $row['visibled'] ? 'checked' : '';
    $enabled = $row['enabled'] ? 'checked' : '';

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['description']) || !isset($_POST['link'])) {
        die("Missing data.");
    }

    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $visibled = isset($_POST['visibled']) ? 1 : 0;
    $enabled = isset($_POST['enabled']) ? 1 : 0;


    if (!empty($_FILES['icon']['name'])) {

        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $targetFile = $targetDir . basename($_FILES["icon"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


        $check = getimagesize($_FILES["icon"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }


        if ($_FILES["icon"]["size"] > 10000000) {
            die("File is too large.");
        }


        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

 
        if (!move_uploaded_file($_FILES["icon"]["tmp_name"], $targetFile)) {
            die("Error uploading file.");
        }

        $iconName = basename($_FILES["icon"]["name"]);
    } else {
  
        $sql = "SELECT icon FROM granna80_bdlinks.payment_methods WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $iconName = $row['icon'];
        $stmt->close();
    }

    $sql = "UPDATE granna80_bdlinks.payment_methods SET name = ?, icon = ?, description = ?, link = ?, visibled = ?, enabled = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $name, $iconName, $description, $link, $visibled, $enabled, $id);
    $stmt->execute();


    $stmt->close();
    $conn->close();


    echo "<script>window.location.href='index.php';</script>";
    exit();
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment Method</title>
</head>
<body>
    <h1>Edit Payment Method</h1>
    <form action="edit.php?=<?php echo htmlspecialchars($id);  ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id ?? ''); ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        <br>

        <label for="icon">Icon:</label>
        <input type="file" id="icon" name="icon">
        <br>
        <?php if (isset($iconName)): ?>
            <img src="/images/<?php echo htmlspecialchars($iconName); ?>" height="50">
        <?php endif; ?>
        <br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
        <br>

        <label for="link">Link:</label>
        <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($link ?? ''); ?>" required>
        <br>

        <label for="visibled">Visible:</label>
        <input type="checkbox" id="visibled" name="visibled" value="1" <?php echo $visibled; ?>>
        <br>

        <label for="enabled">Enabled:</label>
        <input type="checkbox" id="enabled" name="enabled" value="1" <?php echo $enabled; ?>>
        <br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
