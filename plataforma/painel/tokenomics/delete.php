<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';


$id = $_GET['id'];


$sql = "DELETE FROM granna80_bdlinks.tokenomics WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();


$conn->close();



echo "<script>window.location.href = 'menu.php';</script>";


exit();
?>
