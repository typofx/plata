<!DOCTYPE html>
<html>
<head>
    <title>Listar Item no eBay</title>
</head>
<body>
    <form action="listar_item.php" method="post" enctype="multipart/form-data">
        <label for="title">Título do Item:</label>
        <input type="text" name="title" id="title" required><br><br>

        <label for="description">Descrição do Item:</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="category">ID da Categoria:</label>
        <input type="text" name="category" id="category" required><br><br>

        <label for="price">Preço:</label>
        <input type="text" name="price" id="price" required><br><br>

        <label for="quantity">Quantidade:</label>
        <input type="text" name="quantity" id="quantity" required><br><br>

        <label for="image">Imagem do Item:</label>
        <input type="file" name="image" id="image" required><br><br>

        <input type="submit" value="Listar Item">
    </form>
</body>
</html>
