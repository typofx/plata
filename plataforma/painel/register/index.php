<?php
// Definir a duração da sessão para 8 horas (28800 segundos)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        table,
        th,
        td {
            text-align: center;
        }

        .highlight {
            background-color: yellow;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <h1>Users</h1>

    <a href='registerUser.php'>Add new user</a>
    <br>
    <?php
    include 'conexao.php';

    // SQL query to get data from the `users` table
    $sql = "SELECT id, username, email, password, created_at, level FROM granna80_bdlinks.users";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                      
                        <th>Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over results and fill the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . $row["username"] . "</td>
            <td>";
        
        // Obter o nome de usuário antes do "@"
        $username = substr($row["email"], 0, strpos($row["email"], '@'));
        
        // Exibir as três primeiras letras do nome de usuário e fixar asteriscos antes do "@"
        $maskedUsername = substr($username, 0, 3) . "*******";
        
        // Montar o endereço de e-mail completo
        $maskedEmail = $maskedUsername . "@" . substr($row["email"], strpos($row["email"], '@') + 1);
        
        echo $maskedEmail . "</td>
           
            <td>" . $row["level"] . "</td>
            <td>
                
                <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fas fa-trash'></i></a>
            </td>
        </tr>";
        
        }

        echo "</tbody></table>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "lengthMenu": [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                "pageLength": 50
            });
        });

        function confirmDelete(id) {
            var confirmDelete = confirm("Tem certeza que deseja deletar?");
            if (confirmDelete) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>
</body>

</html>