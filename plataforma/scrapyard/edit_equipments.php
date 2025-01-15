<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. Equipment ID is required.");
}

$id = intval($_GET['id']);

// Fetch the equipment details
$stmt = $conn->prepare("SELECT id, name FROM granna80_bdlinks.scrapyard_equipment WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Equipment not found.");
}

$equipment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (empty($name)) {
        echo "Please enter the equipment name.";
    } else {
        // Update the equipment data
        $updateStmt = $conn->prepare("UPDATE granna80_bdlinks.scrapyard_equipment SET name = ? WHERE id = ?");
        $updateStmt->bind_param("si", $name, $id);

        if ($updateStmt->execute()) {
            echo "Equipment updated successfully!";
            header("Location: your_main_page.php"); // Redirect to the main page (update the filename)
            echo "<script>window.location.href='register_equipament.php';</script>";
            exit;
        } else {
            echo "Error updating equipment: " . $updateStmt->error;
        }

        $updateStmt->close();
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Equipment</title>
</head>

<body>
    <h1>Edit Equipment</h1>
    <form method="POST">
        <label for="name">Equipment Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required>
        <br>
        <button type="submit">Update</button>
        <a href="your_main_page.php">[ Back ]</a> <!-- Update the filename -->
    </form>
</body>

</html>
