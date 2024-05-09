<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar membro</title>
</head>
<body>
    <h2>Adicionar membro</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="profilePicture">Foto do perfil:</label><br>
        <input type="file" id="profilePicture" name="profilePicture"><br>
        
        <label for="name">Nome:</label><br>
        <input type="text" id="name" name="name"><br>

        <label for="position">Posição:</label><br>
        <input type="text" id="position" name="position"><br>
        
        <label for="socialMedia">Rede Social 1:</label><br>
        <input type="text" id="socialMedia" name="socialMedia"><br>
        
        <label for="socialMedia1">Rede Social 2:</label><br>
        <input type="text" id="socialMedia1" name="socialMedia1"><br>
        
        <label for="socialMedia2">Rede Social 3:</label><br>
        <input type="text" id="socialMedia2" name="socialMedia2"><br>
        
        <input type="submit" value="Enviar">
    </form>

    <?php
    // Verifica se os dados foram submetidos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Inclui o arquivo de configuração do banco de dados
        include "conexao.php";

        // Verifica se foi enviado um arquivo
        if(isset($_FILES['profilePicture'])){
            $errors= array();
            $file_name = $_FILES['profilePicture']['name'];
            $file_size = $_FILES['profilePicture']['size'];
            $file_tmp = $_FILES['profilePicture']['tmp_name'];
            $file_type = $_FILES['profilePicture']['type'];
            $file_ext=strtolower(end(explode('.',$_FILES['profilePicture']['name'])));
            
            $extensions= array("jpeg","jpg","png");
            
            if(in_array($file_ext,$extensions)=== false){
               $errors[]="Extensão não permitida, por favor escolha um arquivo JPEG ou PNG.";
            }
            
            if($file_size > 2097152) {
               $errors[]='Tamanho do arquivo deve ser no máximo 2 MB';
            }
            
            if(empty($errors)==true) {
               move_uploaded_file($file_tmp,"uploads/".$file_name);
               echo "Upload do arquivo ".$file_name." realizado com sucesso!";
            }else{
               print_r($errors);
            }
         }
        

        // Obtém os dados do formulário
        $profilePicture = "uploads/".$_FILES['profilePicture']['name'];
        $position = $_POST["position"];
        $name = $_POST["name"];
        $socialMedia = $_POST["socialMedia"];
        $socialMedia1 = $_POST["socialMedia1"];
        $socialMedia2 = $_POST["socialMedia2"];

        // Prepara e executa a consulta SQL para inserir os dados na tabela
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.team (teamProfilePicture, teamPosition, teamName, teamSocialMedia0, teamSocialMedia1, teamSocialMedia2) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $profilePicture, $position, $name, $socialMedia, $socialMedia1, $socialMedia2);
        $stmt->execute();

        // Verifica se a inserção foi bem sucedida
        if ($stmt->affected_rows > 0) {
            echo "Equipe adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar equipe.";
        }

        // Fecha a declaração e a conexão com o banco de dados
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
