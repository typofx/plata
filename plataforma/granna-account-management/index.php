<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php


// Include database connection
include 'conexao.php';

// SQL query to fetch and combine data from granna_pix and granna_bank_accounts
$combinedQuery = "
    SELECT id, label, pix_key, NULL AS bank_name, pix_type AS type, NULL AS branch, NULL AS account_number, 
           NULL AS account_holder_name, NULL AS account_holder_cpf, created_at, granna_user_email, 'PIX' AS source, updated_at
    FROM granna80_bdlinks.granna_pix
    UNION
    SELECT id, label, NULL AS pix_key, bank_name, account_type AS type, branch, account_number, 
           account_holder_name, account_holder_cpf, created_at, granna_user_email, 'Bank Account' AS source, updated_at
    FROM granna80_bdlinks.granna_bank_accounts
";

$combinedResult = mysqli_query($conn, $combinedQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Granna Account Management</title>
    <!-- Include DataTables CSS and jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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

<h2>Granna Account Management</h2>
<table id="accountTable" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Label</th>
            <th>Pix Key</th>
            <th>Bank name</th>
            <th>Type</th>
            <th>Branch</th>
            <th>Account Number</th>
            <th>Account Holder Name</th>
            <th>Account Holder CPF</th>
            <th>Created At</th>
            <th>User Email</th>
            <th>Source</th>
            <th>Updated at</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cont = 1;
        
        while ($row = mysqli_fetch_assoc($combinedResult)) : ?>
            <tr>
                <td><?= $cont ?></td>
                <td><?= $row['label'] ?></td>
                <td><?= $row['pix_key'] ?></td>
                <td><?= $row['bank_name'] ?></td>
                <td><?= strtoupper($row['type'])  ?></td>
                <td><?= $row['branch'] ?></td>
                <td><?= $row['account_number'] ?></td>
                <td><?= $row['account_holder_name'] ?></td>
                <td><?= $row['account_holder_cpf'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= $row['granna_user_email'] ?></td>
                <td><?= $row['source'] ?></td>
                <td><?= $row['updated_at'] ?></td>
                <td>
                    <?php if ($row['source'] == 'PIX') : ?>
                        <a href="edit_pix.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete_pix.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php else : ?>
                        <a href="edit_bank.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete_bank.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php
    $cont++;
    
    endwhile; ?>
    </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- DataTables initialization script -->
<script>
    $(document).ready(function() {
        $('#accountTable').DataTable();
    });
</script>

</body>
</html>
