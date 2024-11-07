<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Include database connection
include 'conexao.php';

// Get ID from the URL
$id = $_GET['id'];

// Fetch the existing data for this PIX record
$query = "SELECT * FROM granna80_bdlinks.granna_pix WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Update PIX record if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $label = $_POST['label'];
    $pix_key = $_POST['pix_key'];
    $pix_type = $_POST['pix_type'];

    $updateQuery = "UPDATE granna80_bdlinks.granna_pix SET label = ?, pix_key = ?, pix_type = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $label, $pix_key, $pix_type, $id);
    
    if ($updateStmt->execute()) {

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
    <title>Edit PIX</title>
</head>
<body>

<h2>Edit PIX Record</h2>
<form method="POST">
    Label: <input type="text" name="label" value="<?= $data['label'] ?>"><br>
    Pix Key: <input type="text" name="pix_key" value="<?= $data['pix_key'] ?>"><br>
    Pix Type:
    <select name="pix_type">
        <option value="cpf" <?= $data['pix_type'] == 'cpf' ? 'selected' : '' ?>>CPF</option>
        <option value="email" <?= $data['pix_type'] == 'email' ? 'selected' : '' ?>>Email</option>
        <option value="phone" <?= $data['pix_type'] == 'phone' ? 'selected' : '' ?>>Phone</option>
        <option value="random" <?= $data['pix_type'] == 'random' ? 'selected' : '' ?>>Random</option>
    </select><br>
    <button type="submit">Save Changes</button>
    <a href="index.php">[ back ]</a>
</form>

</body>
</html>
