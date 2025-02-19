<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
// Include the database connection
include 'conexao.php';

// Initialize variables
$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $device = $_POST['device'] ?? null;
    $name = $_POST['name'] ?? null;
    $platform_link = $_POST['platform_link'] ?? null;
    $country = $_POST['country'] ?? null;
    $last_update_by = $userEmail;

    // Handle file uploads (optional)
    $logo = uploadFile('logo');
    $full_logo = uploadFile('full_logo');

    // Use prepared statements to prevent SQL injection
    $sql = "INSERT INTO granna80_bdlinks.typofx_companies (logo, full_logo, device, name, platform_link, country, last_update_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("sssssss", $logo, $full_logo, $device, $name, $platform_link, $country, $last_update_by);

        // Execute the statement
        if ($stmt->execute()) {
            $message = "Item added successfully!";
            echo "<script>window.location.href='index.php';</script>";
        } else {
            $message = "Error: Unable to add item. Please try again.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $message = "Error: Unable to prepare the SQL statement.";
    }
}

// Function to handle file uploads (optional)
function uploadFile($inputName)
{
    if (!isset($_FILES[$inputName])) {
        return null;
    }

    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Caminho absoluto fornecido por você
    $targetDir = "/home2/granna80/public_html/images/typofx-uploads/"; // Caminho absoluto
    $allowedTypes = ['png', 'jpeg', 'jpg', 'svg', 'ico']; // Tipos de arquivo permitidos
    $maxFileSize = 5 * 1024 * 1024; // Tamanho máximo de 5MB

    $fileName = basename($file['name']);
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validar tipo e tamanho do arquivo
    if (!in_array($fileExt, $allowedTypes)) {
        return null;
    }
    if ($fileSize > $maxFileSize) {
        return null;
    }

    // Gerar um nome único para o arquivo
    $uniqueFileName = uniqid() . "." . $fileExt;
    $targetFilePath = $targetDir . $uniqueFileName;

    // Mover o arquivo para o diretório de destino
    if (move_uploaded_file($fileTmp, $targetFilePath)) {
        return $uniqueFileName; // Retornar o caminho do arquivo para o banco de dados
    } else {
        return null;
    }
}


$query = "SELECT country_code, country_name FROM granna80_bdlinks.all_country ORDER BY country_name ASC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <style>
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
        }

        .image-preview {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <h1>Add Item to typofx_companies</h1>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="logo">Logo (PNG, JPEG, SVG, ICO):</label>
        <input type="file" id="logo" name="logo" accept=".png,.jpeg,.jpg,.svg,.ico">
        <div class="image-preview" id="logo-preview"></div><br><br>

        <label for="full_logo">Full Logo (PNG, JPEG, SVG, ICO):</label>
        <input type="file" id="full_logo" name="full_logo" accept=".png,.jpeg,.jpg,.svg,.ico">
        <div class="image-preview" id="full-logo-preview"></div><br><br>

        <label for="device">Device:</label>
        <select id="device" name="device">
            <option value="desktop">Desktop</option>
            <option value="mobile">Mobile</option>
        </select><br><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>

        <label for="platform_link">Platform Link:</label>
        <input type="text" id="platform_link" name="platform_link"><br><br>

        <?php

        if ($result) {
            echo '<label for="country">Country:</label>';
            echo '<select id="country" name="country">';

            // Loop através dos resultados
            while ($row = $result->fetch_assoc()) {
                $country_code = strtolower($row['country_code']); // Garante que o código esteja em minúsculas
                $country_name = $row['country_name'];
                $image_path = "images/all_flags/{$country_code}.png"; // Caminho da imagem da bandeira

                // Verifica se a imagem existe
                if (file_exists($image_path)) {
                    $image = "<img src='{$image_path}' alt='{$country_name}' style='width: 20px; height: 15px; margin-right: 10px;'>";
                } else {
                    $image = "<span style='width: 20px; height: 15px; margin-right: 10px; display: inline-block;'></span>"; // Espaço reservado se a imagem não existir
                }

                // Exibe a opção no select
                echo "<option value='{$country_code}' data-image='{$image_path}'>{$image} {$country_name}</option>";
            }

            echo '</select>';
        } else {
            echo "Erro ao carregar os países.";
        }
        ?>



        <input type="hidden" value="<?php echo $userEmail  ?>" id="last_update_by" name="last_update_by"><br><br>

        <button type="submit">Add Item</button>
        <a href="index.php">[Back]</a>
    </form>


    <script>
        // JavaScript to preview images
        document.getElementById('logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('full_logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('full-logo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Full Logo Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>

</html>