<!DOCTYPE html>
<html>
<head>
    <title>Upload de Imagem</title>
</head>
<body>
    <h2>Envie uma imagem (PNG ou JPG) com até 2MB e tamanho máximo de 1000x1000 pixels:</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="imagem" accept=".jpg, .png" required>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
