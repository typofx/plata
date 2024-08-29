<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week_id = $_GET['week'];
    $employee_id = $_GET['employee_id'];

    // Fetch current week data
    $sql_week = "SELECT * FROM granna80_bdlinks.work_weeks WHERE work_week = ? AND employee_id = ?";
    $stmt_week = $conn->prepare($sql_week);
    $stmt_week->bind_param("ii", $week_id, $employee_id);
    $stmt_week->execute();
    $result_week = $stmt_week->get_result();

    if ($result_week->num_rows > 0) {
        $week_data = $result_week->fetch_assoc();
    } else {
        echo "Week not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_week = $_POST['start_week'];
        $end_week = $_POST['end_week'];
        $status = $_POST['status'];
        $working_hours = $_POST['working_hours'];
        $currency = $_POST['currency'];
        $defi = $_POST['defi'];
        $cex = $_POST['cex'];
        $binance = $_POST['binance'];
        $sepa = $_POST['sepa'];
        $pix = $_POST['pix'];
        $amount_paid = $_POST['amount_paid'];

        // Prepare hash data
        $hash_count = $_POST['hash_count'];
        $hash = $_POST['hash'][0] ?? '';
        $hash1 = $_POST['hash'][1] ?? '';
        $hash2 = $_POST['hash'][2] ?? '';
        $hash3 = $_POST['hash'][3] ?? '';

        // Update the week data
        $sql_update = "UPDATE granna80_bdlinks.work_weeks SET start_week = ?, end_week = ?, status = ?, hash = ?, hash1 = ?, hash2 = ?, hash3 = ?, working_hours = ?, currency = ?, defi = ?, cex = ?, binance = ?, sepa = ?, pix = ?, amount_paid = ? WHERE work_week = ? AND employee_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssssssssssssii", $start_week, $end_week, $status, $hash, $hash1, $hash2, $hash3, $working_hours, $currency, $defi, $cex, $binance, $sepa, $pix, $amount_paid, $week_id, $employee_id);

        if ($stmt_update->execute()) {
            echo "Week updated successfully.";
            echo "<script>window.location.href='work_weeks.php?employee_id=" . $employee_id . "';</script>";
            exit();
        } else {
            echo "Error updating week: " . $conn->error;
        }
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Week</title>
    <script>
        function showHashFields() {
            const hashCount = document.getElementById('hash_count').value;
            for (let i = 1; i <= 4; i++) {
                const hashField = document.getElementById('hash' + i);
                if (i <= hashCount) {
                    hashField.style.display = 'block';
                } else {
                    hashField.style.display = 'none';
                }
            }
        }
    </script>
</head>

<body>
    <h1>Edit Week</h1>

    <form method="post">
        <label for="start_week">Start Week:</label><br>
        <input type="date" id="start_week" name="start_week" value="<?php echo htmlspecialchars($week_data['start_week']); ?>"><br><br>

        <label for="end_week">End Week:</label><br>
        <input type="date" id="end_week" name="end_week" value="<?php echo htmlspecialchars($week_data['end_week']); ?>"><br><br>

        <label for="working_hours">Working Hours:</label><br>
        <input type="text" id="working_hours" name="working_hours" value="<?php echo htmlspecialchars($week_data['working_hours']); ?>"><br><br>

        <label for="amount_paid">Amount paid:</label><br>
        <input type="text" id="amount_paid" name="amount_paid" value="<?php echo htmlspecialchars($week_data['amount_paid']); ?>"><br><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="Paid" <?php echo $week_data['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
            <option value="Pending" <?php echo $week_data['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Processing" <?php echo $week_data['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
        </select><br><br>

        <label for="currency">Currency:</label><br>
        <select id="currency" name="currency" required>
            <option value="USDT" <?php echo $week_data['currency'] == 'USDT' ? 'selected' : ''; ?>>USDT</option>
            <option value="PLT" <?php echo $week_data['currency'] == 'PLT' ? 'selected' : ''; ?>>PLT</option>
            <option value="EUR" <?php echo $week_data['currency'] == 'EUR' ? 'selected' : ''; ?>>EUR</option>
        </select><br><br>

        <label for="hash_count">Number of Transactions:</label><br>
        <select id="hash_count" name="hash_count" onchange="showHashFields()" required>
            <option value="1" <?php echo !empty($week_data['hash']) && empty($week_data['hash1']) ? 'selected' : ''; ?>>1</option>
            <option value="2" <?php echo !empty($week_data['hash1']) && empty($week_data['hash2']) ? 'selected' : ''; ?>>2</option>
            <option value="3" <?php echo !empty($week_data['hash2']) && empty($week_data['hash3']) ? 'selected' : ''; ?>>3</option>
            <option value="4" <?php echo !empty($week_data['hash3']) ? 'selected' : ''; ?>>4</option>
        </select><br><br>

        <div id="hash1">
            <label for="hash[0]">Transaction 1:</label><br>
            <input type="text" name="hash[0]" value="<?php echo htmlspecialchars($week_data['hash']); ?>"><br><br>
        </div>

        <div id="hash2" style="display: <?php echo !empty($week_data['hash1']) ? 'block' : 'none'; ?>">
            <label for="hash[1]">Transaction 2:</label><br>
            <input type="text" name="hash[1]" value="<?php echo htmlspecialchars($week_data['hash1']); ?>"><br><br>
        </div>

        <div id="hash3" style="display: <?php echo !empty($week_data['hash2']) ? 'block' : 'none'; ?>">
            <label for="hash[2]">Transaction 3:</label><br>
            <input type="text" name="hash[2]" value="<?php echo htmlspecialchars($week_data['hash2']); ?>"><br><br>
        </div>

        <div id="hash4" style="display: <?php echo !empty($week_data['hash3']) ? 'block' : 'none'; ?>">
            <label for="hash[3]">Transaction 4:</label><br>
            <input type="text" name="hash[3]" value="<?php echo htmlspecialchars($week_data['hash3']); ?>"><br><br>
        </div>


        <label for="defi">DeFi:</label><br>
        <input type="text" id="defi" name="defi" value="<?php echo htmlspecialchars($week_data['defi']); ?>"><br><br>

        <label for="cex">CEX:</label><br>
        <input type="text" id="cex" name="cex" value="<?php echo htmlspecialchars($week_data['cex']); ?>"><br><br>

        <label for="binance">Binance:</label><br>
        <input type="text" id="binance" name="binance" value="<?php echo htmlspecialchars($week_data['binance']); ?>"><br><br>

        <label for="sepa">SEPA:</label><br>
        <input type="text" id="sepa" name="sepa" value="<?php echo htmlspecialchars($week_data['sepa']); ?>"><br><br>

        <label for="pix">Pix:</label><br>
        <input type="text" id="pix" name="pix" value="<?php echo htmlspecialchars($week_data['pix']); ?>"><br><br>

        <input type="submit" value="Update Week">
    </form>

    <br>
    <a href="work_weeks.php?employee_id=<?php echo $employee_id; ?>">Back</a>

</body>

</html>

<?php
$stmt_week->close();
$conn->close();
?>
