<?php
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<?php
include 'conexao.php';

// Select the database
$dbname = 'granna80_bdlinks';
if (!$conn->select_db($dbname)) {
    die("Failed to select database: " . $conn->error);
}

// Query to get data from the payments_trash table
$sql = "SELECT trash_id, id, date, bank, plata, amount, asset, address, txid, email, status FROM payments_trash";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Trash</title>
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
    <h1>Payment Trash</h1>
    <table id='example' class='display'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Payment ID</th>
                <th>Date</th>
                <th>Bank</th>
                <th>Platform</th>
                <th>Amount</th>
                <th>Asset</th>
                <th>Address</th>
                <th>TxID</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                      <td>{$row['trash_id']}</td>
                        <td>{$row['id']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['bank']}</td>
                        <td>{$row['plata']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['asset']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['txid']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['status']}</td>
                           <td>
                
                <a href='#' onclick='confirmDelete(" . $row["trash_id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
            </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script>
        function confirmDelete(id) {
            var confirmDelete = confirm("Are you sure you want to delete it permanently? It will not be possible to restore deleted data.");
            if (confirmDelete) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').dataTable({
                "lengthMenu": [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                "pageLength": 50
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
