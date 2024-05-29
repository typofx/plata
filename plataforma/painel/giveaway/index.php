<?php 
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
    <title>CoinMarketCap Dexscan UpVote</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        table,
        th,
        td {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>CoinMarketCap Dexscan UpVote</h1>

    <a href='add.php'>Add new record</a>
    <br>
    <?php
    include 'conexao.php';

    // SQL query to get data from the `payments` table
    $sql = "SELECT id, evm_wallet, vote_image, vote_number, time FROM granna80_bdlinks.votes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Time</th>
                        <th>EVM Wallet</th>
                        <th>Print</th>
                        <th>Vote</th>
                      
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over results and fill the table
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td><b>" . date('d/m/Y H:i:s', strtotime($row["time"])) . "</b></td>
                    <td>" . $row["evm_wallet"] . "</td>
                    <td><img src='https://plata.ie/giveaway/" . $row['vote_image'] . "' width='50' height='50' alt='Image'></td>
                    <td>" . $row["vote_number"] . "</td>
              
                    <td>
                        <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                        <a href='delete.php?id=" . $row["id"] . "'><i style='color: red;' class='fa-solid fa-trash'></i></i></a>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>
</html>
