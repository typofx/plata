<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $link = isset($_POST['link']) ? trim($_POST['link']) : '';

    // Validate if the name and link were provided
    if (empty($name)) {
        echo "Please enter the e-shop name.";
    } elseif (empty($link)) {
        echo "Please enter the e-shop link.";
    } elseif (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        echo "Please upload a valid logo or icon.";
    } else {
        // Validate file type and size
        $allowedTypes = ['image/png', 'image/jpeg'];
        $maxSize = 10 * 1024 * 1024; // 10 MB
        $fileType = $_FILES['logo']['type'];
        $fileSize = $_FILES['logo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            echo "Invalid file type. Only PNG and JPEG are allowed.";
        } elseif ($fileSize > $maxSize) {
            echo "File size exceeds the maximum limit of 10MB.";
        } else {
            // Move the uploaded file to the target directory
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/';
            $fileName = basename($_FILES['logo']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_eshops (name, logo, link) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $fileName, $link);

                if ($stmt->execute()) {
                    echo "E-shop registered successfully!";
                } else {
                    echo "Error registering the e-shop: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Failed to upload the file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register E-shop</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <title>Register E-shop</title>
</head>

<body>
    <h1>Register E-shop</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">E-shop Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="link">Perma Link:</label>
        <input type="url" id="link" name="link" required>
        <br>
        <label for="logo">Logo/Icon (PNG or JPEG, max 10MB):</label>
        <input type="file" id="logo" name="logo" accept="image/png, image/jpeg" required>
        <br>
        <button type="submit">Register</button>
        <a href="index.php">[ Back ]</a>
    </form>

    <h2>Manage E-shop</h2>
    <table id="eshopsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Logo</th>
                <th>Link</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT id, name, logo, link FROM granna80_bdlinks.scrapyard_eshops";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $cont = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$cont}</td>
                        <td>{$row['name']}</td>
                        <td><img src='/images/uploads-scrapyard/{$row['logo']}' alt='Logo' style='width:50px; height:50px;'></td>
                        <td><a href='{$row['link']}' target='_blank'>{$row['link']}</a></td>
                        <td>
                            <a href='edit_eshop.php?id={$row['id']}'>Edit</a> |
                            <a href='delete_eshop.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this e-shop?\")'>Delete</a>
                        </td>
                    </tr>";
                    $cont++;
                }
            } else {
                echo "<tr><td colspan='5'>No e-shops registered.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('#eshopsTable').DataTable();
        });
    </script>
</body>

</html>