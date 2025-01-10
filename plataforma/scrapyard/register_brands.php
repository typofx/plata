<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

    // Validate if the brand name was provided
    if (empty($brand_name)) {
        echo "Please enter the brand name.";
    } else {
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_brands (brand_name) VALUES (?)");
        $stmt->bind_param("s", $brand_name);

        if ($stmt->execute()) {
            echo "Brand registered successfully!";
        } else {
            echo "Error registering the brand: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Brands</title>
</head>
<body>
    <h1>Register Brand</h1>
    <form method="POST">
        <label for="brand_name">Brand Name:</label>
        <input type="text" id="brand_name" name="brand_name" required>
        <button type="submit">Register</button>
        <a href="index.php">[ Back ]</a>
        <a href="register_models.php"> [ Register models ]</a>
    </form>
</body>
</html>
