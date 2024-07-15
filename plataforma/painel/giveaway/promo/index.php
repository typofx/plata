<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Promo Codes</title>
    <!-- Include DataTables CSS -->
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
    <h2>Promo Codes</h2>
    <a href="add.php">[add promo code]</a>
    <table id="example" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Promo Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include database connection file
            include 'conexao.php';

            // SQL query to fetch all promo codes
            $sql = "SELECT id, promo_code FROM granna80_bdlinks.promo_codes";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['promo_code'] . "</td>";
                    echo "<td><a href='edit.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i></a> | <a href='delete.php?id=" . $row['id'] . "'><i style='color: red;' class='fas fa-trash'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No promo codes found.</td></tr>";
            }

            // Close database connection
            $conn->close();
            ?>
        </tbody>
    </table>

    <!-- Include jQuery and DataTables JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/numeric-comma.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>
