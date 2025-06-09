<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

header('Content-Type: text/html; charset=utf-8');

$data = array();

try {
    $sql = "SELECT * FROM granna80_bdlinks.tokenomics";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
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

    <a href="update_all_balances.php">[Refresh]</a>
    <a href="add.php">[Add new record]</a>
    <a href="index.php">[Back]</a>
    <h1>Manual Tokenomics</h1>
    <table id="tokenomicsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Symbol</th>
                <th>Name</th>
                <th>Wallet adress</th>
                <th>Decimals</th>
                <th>Balance</th>
                <th>Group</th>
                <th>Wallet Name</th>
                <th>Visible?</th>
                <th>Last updated on</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['symbol'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['name'] ?? ''); ?></td>
                    <td>
                        <?php
                        $address = htmlspecialchars($row['walletAddress'] ?? '');
                        $short = substr($address, 0, 6) . '...' . substr($address, -4);
                        echo '<a href="https://polygonscan.com/address/' . $address . '" target="_blank">' . $short . '</a>';
                        ?>
                    </td>

                    <td><?php echo htmlspecialchars($row['decimals'] ?? ''); ?></td>
                    <td><?php echo number_format(((float)$row['balance']) / 10000, 4, '.', ','); ?></td>


                    <td><?php echo htmlspecialchars($row['wallet_group'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['walletname'] ?? ''); ?></td>
                    <td><?php echo isset($row['visible']) ? ($row['visible'] ? 'Yes' : 'No') : ''; ?></td>
                   <td><?php echo date('d/m/Y H:i', strtotime($row['last_updated'])); ?> UTC</td>


                    <td>
                        <a href="edit.php?id=<?php echo htmlspecialchars($row['id'] ?? ''); ?>">Edit</a> |
                        <a href="delete.php?id=<?php echo htmlspecialchars($row['id'] ?? ''); ?>">Delete</a>
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