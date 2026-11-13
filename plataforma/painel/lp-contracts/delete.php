<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php

include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record from the database
    $sql = "DELETE FROM granna80_bdlinks.lp_contracts WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "ID not specified";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>

   <script>
      function redirect () {
         setTimeout(myURL, 2000);
         var result = document.getElementById("result");
         result.innerHTML = "<b> The page will redirect after delay of 2 seconds";
      }

      function myURL() {
         document.location.href = 'https://plata.ie/plataforma/painel/lp-contracts/';
      }
   </script>
</body>
</html>
