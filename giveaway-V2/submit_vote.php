<?php
include "conexao.php"; 

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Access denied.");
}

// Verificar se os dados esperados foram recebidos
if (!isset($_POST['evm_wallet']) || !isset($_POST['vote_number']) || !isset($_FILES["vote_image"])) {
    die("Missing form data.");
}

// Checar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegar dados do formulário
    $evm_wallet = $_POST['evm_wallet'];
    $vote_number = $_POST['vote_number'];
    
    // Processar upload da imagem
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo($_FILES["vote_image"]["name"], PATHINFO_EXTENSION));

    // Gerar um nome de arquivo único
    $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
    
    // Caminho completo do arquivo alvo
    $target_file = $target_dir . $unique_filename;
    
    $uploadOk = 1;
    
    // Verificar se o arquivo é uma imagem real
    $check = getimagesize($_FILES["vote_image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    // Verificar tamanho do arquivo
    if ($_FILES["vote_image"]["size"] > 10000000) { // 10MB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Verificar se $uploadOk está definido como 0 por um erro
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // Se tudo estiver ok, tentar fazer o upload do arquivo
    } else {
        if (move_uploaded_file($_FILES["vote_image"]["tmp_name"], $target_file)) {
            echo "The file ". basename($unique_filename). " has been uploaded.";
            
            // Inserir dados na tabela
            $sql = "INSERT INTO granna80_bdlinks.votes (evm_wallet, vote_image, vote_number) VALUES ('$evm_wallet', '$target_file', '$vote_number')";
            
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
