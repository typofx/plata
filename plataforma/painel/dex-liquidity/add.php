<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add DEX</title>
    <script>
        function toggleRouterFields() {
            const type = document.getElementById('type').value;
            const routerAddressField = document.getElementById('router-address-field');
            
            if (type === 'dex') {
                routerAddressField.style.display = 'block';
            } else {
                routerAddressField.style.display = 'none';
            }
        }
    </script>
</head>

<body>
    <h1>Add DEX</h1>
    <br>
    <?php
    include 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'] ?? null;
        $type = $_POST['type'] ?? null;
        $router_address = ($type === 'dex') ? ($_POST['router_address'] ?? null) : null;
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
        $stmt->bind_param("ssss", $name, $logo, $type, $router_address);

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

        <label for="type">Type:</label><br>
        <select id="type" name="type" onchange="toggleRouterFields()">
            <option value="">-- Select Type --</option>
            <option value="dex">DEX</option>
            <option value="cex">CEX</option>
            <option value="lending">Lending</option>
            <option value="farming">Farming</option>
            <option value="locker">Locker</option>
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