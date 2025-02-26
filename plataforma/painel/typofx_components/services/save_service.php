<?php
 include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $link = $_POST['link'];
    $date = $_POST['date'];
    $created_by = $_POST['created_by'];
    $visible = $_POST['visible'];

    // Processa o upload da imagem
    if (isset($_FILES['logo'])) {

        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-services/';
        $target_dir = $upload_dir;
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
        $logo = basename($_FILES["logo"]["name"]);
    }

    // Insere os dados no banco de dados
    $query = "INSERT INTO granna80_bdlinks.typofx_services (name, logo, link, date, created_by, visible) 
              VALUES ('$name', '$logo', '$link', '$date', '$created_by', '$visible')";
    if ($conn->query($query)) {
        echo "Serviço salvo com sucesso!";
    } else {
        echo "Erro ao salvar o serviço: " . $conn->error;
    }
}
