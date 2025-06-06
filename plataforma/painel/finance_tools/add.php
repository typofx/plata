<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tool</title>
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
    </script>
</head>

<body>
    <h1>Add Tool</h1>
    <br>
    <?php
    include 'conexao.php';

    // Fetch all tags from the database
    $tags = [];
    $tag_query = "SELECT DISTINCT tag FROM granna80_bdlinks.finance_tools_groups WHERE tag IS NOT NULL AND tag != ''";
    $tag_result = $conn->query($tag_query);
    if ($tag_result->num_rows > 0) {
        while($row = $tag_result->fetch_assoc()) {
            $tags[] = $row['tag'];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'] ?? null;
        $group = $_POST['group'] ?? null;
        $router_address = ($group === 'dex') ? ($_POST['router_address'] ?? null) : null;
        $logo = null;

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $imageFileType = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
            $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
            $target_file = $target_dir . $unique_filename;

            $check = getimagesize($_FILES["logo"]["tmp_name"]);
            if ($check !== false && $_FILES["logo"]["size"] <= 10000000) {
                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                    $logo = $target_file;
                }
            }
        }

        $sql = "INSERT INTO granna80_bdlinks.dex_liquidity (name, logo, type, router_address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $logo, $group, $router_address);

        if ($stmt->execute()) {
            echo "New record created successfully";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>

        <label for="group">Group:</label><br>
        <select id="group" name="group" onchange="toggleRouterFields()">
            <option value="">-- Select Group --</option>
            <?php foreach ($tags as $tag): ?>
                <option value="<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></option>
            <?php endforeach; ?>
        </select><br>

        <div id="router-address-field" style="display: none;">
            <label for="router_address">Router Address:</label><br>
            <input type="text" id="router_address" name="router_address"><br>
        </div>

        <label for="logo">Logo:</label><br>
        <input type="file" id="logo" name="logo"><br><br>

        <input type="submit" value="Add">
    </form>
    <a href='index.php'>Back to List</a>

    <script>
        document.addEventListener('DOMContentLoaded', toggleRouterFields);
    </script>
</body>
</html>