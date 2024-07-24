<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php

include 'conexao.php';


$sql = "SELECT * FROM granna80_bdlinks.tokenomics";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Tokenomics</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Control Panel]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <a href="add.php">[Add new record]</a>
    <a href="index.php">[Back]</a>
    <h1>Manual Tokenomics</h1>
    <table id="tokenomicsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Symbol</th>
                <th>Name</th>
                <th>Decimals</th>
                <th>Balance</th>

                <th>Wallet Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['symbol']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['decimals']); ?></td>
                    <td><?php echo htmlspecialchars($row['balance']); ?></td>

                    <td><?php echo htmlspecialchars($row['walletname']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit</a> |
                        <a href="delete.php?id=<?php echo htmlspecialchars($row['id']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#tokenomicsTable').DataTable();
        });
    </script>
</body>

</html>