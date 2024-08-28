<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';


$sql = "SELECT id, employee, rate, pay_type FROM  granna80_bdlinks.payout";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout List</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
    <h1>List of Payouts</h1>
    <br>
    <br>
    <a href="add.php">[add new record]</a>
    <table id="payoutTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Rate</th>
                <th>Pay Type</th>
                <th>Actions</th>



            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $cont = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>$cont</td>
                           <td><a href='work_weeks.php?employee_id={$row['id']}'>{$row['employee']}</a></td>
                            <td>{$row['rate']} USDT</td>
                            <td>{$row['pay_type']}</td>
                             <td><a href='edit.php?id={$row['id']}'>edit</a>⠀<a href='delete.php?id={$row['id']}'>delete</a></td>
                          </tr>";
                    $cont++;
                }
            } else {
                echo "<tr><td colspan='4'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#payoutTable').DataTable();
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>