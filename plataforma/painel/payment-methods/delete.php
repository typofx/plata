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
    $name = $row['name'];


    $stmt->close();
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id'])) {
        die("Missing ID.");
    }

    $id = intval($_POST['id']);


    $sql = "DELETE FROM granna80_bdlinks.payment_methods WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
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
    <title>Delete Payment Method</title>
</head>
<body>
    <h1>Delete Payment Method</h1>
    <p>Are you sure you want to delete the payment method <strong><?php echo htmlspecialchars($name); ?></strong>?</p>
    <form action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id ?? ''); ?>">
        <a href="index.php">[back]</a>
        <input type="submit" value="Delete">
    </form>
</body>
</html>
