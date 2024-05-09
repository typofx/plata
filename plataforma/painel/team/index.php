<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Membros da Equipe</title>
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
         
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .highlighted {
            background-color: yellow;
            display: inline;
            padding: 0;
        }
    </style>
</head>
<body>
    <h2>Lista de Membros da Equipe</h2>
    <a href="add.php">Add new</a>

    <table>
        <tr>
            <th>Foto do Perfil</th>
            <th>Nome</th>
            <th>Posição</th>
            <th>Redes Sociais</th>
            <th>Actions</th>
        </tr>

        <?php
        // Inclui o arquivo de configuração do banco de dados
        include "conexao.php";

        // Consulta SQL para selecionar todos os membros da equipe
        $sql = "SELECT * FROM granna80_bdlinks.team";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Exibir cada membro da equipe em uma linha da tabela
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img src='" . $row['teamProfilePicture'] . "' width='100'></td>";
                echo "<td>" . $row['teamName'] . "</td>";
                echo "<td>" . $row['teamPosition'] . "</td>";
                echo "<td>";
                echo "Rede Social 1: " . $row['teamSocialMedia0'] . "<br>";
                echo "Rede Social 2: " . $row['teamSocialMedia1'] . "<br>";
                echo "Rede Social 3: " . $row['teamSocialMedia2'];
                echo "</td>";
                echo "<td>Edit</td>";
                echo "</tr>";

            }
        } else {
            echo "Nenhum membro da equipe encontrado.";
        }
        // Fecha a conexão com o banco de dados
        $conn->close();
        ?>
    </table>
</body>
</html>
