<?php
include 'conexao.php';
function copiarPasta($origem, $destino) {

    if (!file_exists($destino)) {
        mkdir($destino, 0777, true);
    }
    
 
    $diretorio = opendir($origem);
    
  
    while (($arquivo = readdir($diretorio)) !== false) {
        if ($arquivo != "." && $arquivo != "..") {
            $caminhoOrigem = $origem . DIRECTORY_SEPARATOR . $arquivo;
            $caminhoDestino = $destino . DIRECTORY_SEPARATOR . $arquivo;
            
          
            if (is_dir($caminhoOrigem)) {
                copiarPasta($caminhoOrigem, $caminhoDestino);
            } else {
          
                copy($caminhoOrigem, $caminhoDestino);
            }
        }
    }
    
    closedir($diretorio);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $pastaPadrao = $_SERVER['DOCUMENT_ROOT'] . '/base'; 
    $destino = $_SERVER['DOCUMENT_ROOT'] . '/' . $code;
    

    if (empty($code) || empty($name)) {
        echo "Código e nome são obrigatórios.";
    } else {
      
        copiarPasta($pastaPadrao, $destino);
        
       
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.languages (code, name) VALUES (?, ?)");
        $stmt->bind_param("ss", $code, $name);
        if ($stmt->execute()) {
            echo "Idioma adicionado com sucesso!";
        } else {
            echo "Erro ao adicionar idioma: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add idiom</title>
</head>
<body>
    <h1>Add Idiom</h1>
    <form action="add_language.php" method="post">
        <label for="code">Language code:</label>
        <input type="text" id="code" name="code" required>
        <br>
        <label for="name">Language Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <a href="index.php">back</a>
        <button type="submit">Add</button>
    </form>
</body>
</html>
