<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Documents - List</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        .copy-btn {
            cursor: pointer;
        }

        table, th, td {
          
            text-align: center;
            
        }
    </style>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $(document).ready(function() {
            $('#teamDocsTable').DataTable();

            $('.copy-btn').on('click', function() {
                const text = $(this).data('text');
                navigator.clipboard.writeText(text).then(function() {
                    alert('Copied to clipboard: ' + text);
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            });
        });
    </script>
</head>
<body>
    <h2>Team Documents - List</h2>
     <a href="add.php">[Add new member]</a>
     <a href="https://plata.ie/plataforma/painel/menu.php">[Root Menu]</a>
     <a href="https://plata.ie/plataforma/painel/team/index.php">[Edit Team]</a>
     <br>
     <br>
     <br>
    <table id="teamDocsTable" class="display">
        <thead>
            <tr>
                <th>Member Name</th>
                <th>DeFi Wallet</th>
                <th>CEX Wallet</th>
                <th>BinanceID</th>
                <th>BinanceNickname</th>
                <th>CPF</th>
                <th style='white-space: nowrap;'>Passport N°</th>
                <th style='white-space: nowrap;'>Passport Photo</th>
                <th>Pix</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection (replace with your credentials)
            include 'conexao.php';

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to retrieve all team documents
            $sql = "SELECT * FROM granna80_bdlinks.team_docs";
            $result = $conn->query($sql);

            // If there are results, display them in the table
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['member_name'] . "</td>";
                    echo "<td style='white-space: nowrap;'>" . $row['defi_wallet'] . " <button class='copy-btn' data-text='" . $row['defi_wallet'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td style='white-space: nowrap;'>" . $row['cex_wallet'] . " <button class='copy-btn' data-text='" . $row['cex_wallet'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td style='white-space: nowrap;'>" . $row['binance_id'] . " <button class='copy-btn' data-text='" . $row['binance_id'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td style='white-space: nowrap;'>" . $row['binanceName'] . " <button class='copy-btn' data-text='" . $row['binanceName'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td style='white-space: nowrap;'>" . $row['cpf'] . " <button class='copy-btn' data-text='" . $row['cpf'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td style='white-space: nowrap;'>" . $row['passport'] . " <button class='copy-btn' data-text='" . $row['passport'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td><img src='/images/uploads-docs/" . $row['passport_photo'] . "' alt='Passport Photo' style='max-width:50px;'></td>";
                    echo "<td style='white-space: nowrap;'>" . $row['pix'] . " <button class='copy-btn' data-text='" . $row['pix'] . "'><i class='fa-solid fa-copy'></i></button></td>";
                    echo "<td><a href='edit.php?id=" . $row['id'] . "'><i class='fa-solid fa-pen-to-square'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No records found.</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
