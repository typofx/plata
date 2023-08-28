<?php
include '../conexao.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM granna80_bdlinks.links WHERE ID = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Returns record details in JSON format
    } else {
        echo json_encode(["error" => "Register not found."]);
    }

    $conn->close();
}
?>
