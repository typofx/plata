<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php include 'conexao.php'; ?>

<?php
// Fetch all tags from the database
$tags = [];
$tag_query = "SELECT DISTINCT tag FROM granna80_bdlinks.finance_tools_groups WHERE tag IS NOT NULL AND tag != ''";
$tag_result = $conn->query($tag_query);
if ($tag_result->num_rows > 0) {
    while($row = $tag_result->fetch_assoc()) {
        $tags[] = $row['tag'];
    }
}

// Check if 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to select specific data by ID
    $sql = "SELECT id, name, logo, type, router_address FROM granna80_bdlinks.dex_liquidity WHERE id = ?";
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
    $group = $_POST['group'];
    $router_address = ($group === 'dex') ? $_POST['router_address'] : null;
    $uploadOk = 1;
    $logo = $row['logo']; 

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
        $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["logo"]["size"] > 10000000) { // 10MB
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1 && move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $logo = $target_file;
        } else {
            $uploadOk = 0;
        }
    }

    if ($uploadOk) {
        $sql = "UPDATE granna80_bdlinks.dex_liquidity SET name=?, logo=?, type=?, router_address=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssi", $name, $logo, $group, $router_address, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
          
            $row['name'] = $name;
            $row['type'] = $group;
            $row['router_address'] = $router_address;
            if (isset($target_file)) $row['logo'] = $target_file;
            echo "<script>window.location.href = 'index.php';</script>";
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
    <title>Edit Tool</title>
    <script>
        function toggleRouterFields() {
            const group = document.getElementById('group').value;
            const routerAddressField = document.getElementById('router-address-field');
            
            if (group === 'dex') {
                routerAddressField.style.display = 'block';
            } else {
                routerAddressField.style.display = 'none';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            toggleRouterFields();
        });
    </script>
</head>

<body>
    <h1>Edit Tool</h1>
    <br>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br>

        <label for="group">Group:</label><br>
        <select id="group" name="group" onchange="toggleRouterFields()" required>
            <option value="">-- Select Group --</option>
            <?php foreach ($tags as $tag): ?>
                <option value="<?php echo htmlspecialchars($tag); ?>" 
                    <?php if ($row['type'] == $tag) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($tag); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <div id="router-address-field" style="display: <?php echo ($row['type'] == 'dex') ? 'block' : 'none'; ?>;">
            <label for="router_address">Router Address:</label><br>
            <input type="text" id="router_address" name="router_address" 
                   value="<?php echo ($row['type'] == 'dex') ? htmlspecialchars($row['router_address']) : ''; ?>"><br>
        </div>

        <label for="logo">Logo:</label><br>
        <img src="<?php echo $row['logo']; ?>" width="200" height="200" alt="Logo"><br>
        <input type="file" id="logo" name="logo"><br><br>

        <input type="submit" value="Update">
    </form>
    <a href="index.php">Back to List</a>
</body>
</html>