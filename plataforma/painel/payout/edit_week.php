<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';





if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week_id = $_GET['week'];
    $employee_id = $_GET['employee_id'];

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

    //echo $PLTUSD;
    //echo $EURUSD;


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $generated_at = $_POST['generated_at'];
        $datetime = date('Y-m-d H:i:s', strtotime($generated_at));

        $start_week = $_POST['start_week'];
        $end_week = $_POST['end_week'];
        $status = $_POST['status'];
        $working_hours = !empty($_POST['working_hours']) && is_numeric($_POST['working_hours']) ? $_POST['working_hours'] : 0;
        $weekly_value_pltusd = $_POST['weekly_value_pltusd'];
        $weekly_value_plteur = $_POST['weekly_value_plteur'];

        $PLTUSD = isset($week_data['weekly_value_pltusd']) ? (float)$week_data['weekly_value_pltusd'] : (float)$weekly_value_pltusd;
        $EURUSD = isset($week_data['weekly_value_plteur']) ? (float)$week_data['weekly_value_plteur'] : (float)$weekly_value_plteur;



        $hash_count = $_POST['hash_count'];
        $transactions = [];
        for ($i = 0; $i < $hash_count; $i++) {
            $currency = $_POST['currency'][$i] ?? '';
            $amount = $_POST['amount'][$i] ?? '';
            $amount = str_replace(',', '', $amount);
            $amount = (float)$amount;
            $plt_value = null;
            $pltusd_value = null;

            if ($currency === 'PLT') {
                $plt_value = $amount;
                $pltusd_value = $amount * $PLTUSD;
                $plteur_value = $pltusd_value / $EURUSD; // Agora dividimos por EURUSD
            } else if ($currency === 'USDT') {
                $plt_value = ($amount / $PLTUSD);
                $plteur_value = $amount / $EURUSD; // Agora dividimos por EURUSD
                $pltusd_value = $amount;
            } else {
                $plt_value = ($amount / $PLTUSD);
                $pltusd_value = $amount;
            }


            $transactions[] = [
                'hash' => $_POST['hash'][$i] ?? '',
                'type' => $_POST['type'][$i] ?? '',
                'currency' => $currency,
                'amount' => $amount,
                'plt' => $plt_value,
                'pltusd' => $pltusd_value,
                'plteur' => $plteur_value
            ];
        }

        $sql_update = "UPDATE granna80_bdlinks.work_weeks 
        SET created_at  = ?, start_week = ?, end_week = ?, status = ?, working_hours = ?, weekly_value_pltusd = ?, weekly_value_plteur = ?,
            hash0 = ?, type0 = ?, currency0 = ?, amount0 = ?, pltusd0 = ?, plt0 = ?, plteur0 = ?,
            hash1 = ?, type1 = ?, currency1 = ?, amount1 = ?, pltusd1 = ?, plt1 = ?, plteur1 = ?,
            hash2 = ?, type2 = ?, currency2 = ?, amount2 = ?, pltusd2 = ?, plt2 = ?, plteur2 = ?,
            hash3 = ?, type3 = ?, currency3 = ?, amount3 = ?, pltusd3 = ?, plt3 = ?, plteur3 = ?
        WHERE work_week = ? AND employee_id = ?";

        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param(
            "sssssssssssssssssssssssssssssssssssii",
            $datetime,
            $start_week,
            $end_week,
            $status,
            $working_hours,
            $weekly_value_pltusd,
            $weekly_value_plteur,

            $transactions[0]['hash'],
            $transactions[0]['type'],
            $transactions[0]['currency'],
            $transactions[0]['amount'],
            $transactions[0]['pltusd'],
            $transactions[0]['plt'],
            $transactions[0]['plteur'],
            $transactions[1]['hash'],
            $transactions[1]['type'],
            $transactions[1]['currency'],
            $transactions[1]['amount'],
            $transactions[1]['pltusd'],
            $transactions[1]['plt'],
            $transactions[1]['plteur'],
            $transactions[2]['hash'],
            $transactions[2]['type'],
            $transactions[2]['currency'],
            $transactions[2]['amount'],
            $transactions[2]['pltusd'],
            $transactions[2]['plt'],
            $transactions[2]['plteur'],
            $transactions[3]['hash'],
            $transactions[3]['type'],
            $transactions[3]['currency'],
            $transactions[3]['amount'],
            $transactions[3]['pltusd'],
            $transactions[3]['plt'],
            $transactions[3]['plteur'],
            $week_id,
            $employee_id
        );

        if ($stmt_update->execute()) {
            echo "Week updated successfully.";
             echo "<script>window.location.href='work_weeks.php?employee_id=" . $employee_id . "';</script>";
            exit();
        } else {
            echo "Error updating week: " . $conn->error;
        }
    }
} else {
    echo "Requisição inválida.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Week</title>
    <script>
        function showHashFields() {
            const hashCount = document.getElementById('hash_count').value;
            for (let i = 1; i <= 4; i++) {
                const hashField = document.getElementById('hash' + i);
                const currencyField = document.getElementById('currency' + i);
                const amountField = document.getElementById('amount' + i);
                if (i <= hashCount) {
                    hashField.style.display = 'inline';
                    currencyField.style.display = 'inline';
                    amountField.style.display = 'inline';
                } else {
                    hashField.style.display = 'none';
                    currencyField.style.display = 'none';
                    amountField.style.display = 'none';
                }
            }
        }

        function showAmountField(index) {
            const transactionType = document.getElementById('type' + index).value;
            const amountField = document.getElementById('amount' + index);

            if (transactionType) {
                amountField.style.display = 'inline';
            } else {
                amountField.style.display = 'none';
            }
        }
    </script>
</head>

<body>
    <h1>Edit Week</h1>

    <form method="post">


    <label for="generated_at">Generated At:</label><br>
<input type="datetime-local" id="generated_at" name="generated_at" 
       value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($week_data['created_at']))); ?>"><br><br>


        <label for="work_week"># Work week:</label><br>
        <input type="number" id="work_week" name="work_week" value="<?php echo htmlspecialchars($week_data['work_week']); ?>"><br><br>

        <label for="start_week">Start of the Week:</label><br>
        <input type="date" id="start_week" name="start_week" value="<?php echo htmlspecialchars($week_data['start_week']); ?>"><br><br>

        <label for="end_week">End of the Week:</label><br>
        <input type="date" id="end_week" name="end_week" value="<?php echo htmlspecialchars($week_data['end_week']); ?>"><br><br>

        <label for="working_hours">Hours Worked:</label><br>
        <input type="text" id="working_hours" name="working_hours" value="<?php echo htmlspecialchars($week_data['working_hours']); ?>"><br><br>



        <label for="status">Status:</label><br>
<select id="status" name="status">
    <option value="Paid" <?php echo $week_data['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
    <option value="Pending" <?php echo $week_data['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
    <option value="Processing" <?php echo $week_data['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
    <option value="Holiday" <?php echo $week_data['status'] == 'Holiday' ? 'selected' : ''; ?>>Holiday</option>
    <option value="Holiday (Paid)" <?php echo $week_data['status'] == 'Holiday (Paid)' ? 'selected' : ''; ?>>Holiday (Paid)</option>
</select><br><br>



        <label for="weekly_value_plteur">PLTEUR:</label><br>
        <input type="number" id="weekly_value_plteur" name="weekly_value_plteur" value="<?php echo htmlspecialchars($week_data['weekly_value_plteur']); ?>" step="0.0000000001" min="0"><br><br>

        <label for="weekly_value_pltusd">PLTUSD:</label><br>
        <input type="number" id="weekly_value_pltusd" name="weekly_value_pltusd" value="<?php echo htmlspecialchars($week_data['weekly_value_pltusd']); ?>" step="0.0000000001" min="0"><br><br>



        <label for="hash_count">Number of Transactions:</label>
        <select id="hash_count" name="hash_count" onchange="showHashFields()">
            <option value="1" <?php echo !empty($week_data['hash']) && empty($week_data['hash1']) ? 'selected' : ''; ?>>1</option>
            <option value="2" <?php echo !empty($week_data['hash1']) && empty($week_data['hash2']) ? 'selected' : ''; ?>>2</option>
            <option value="3" <?php echo !empty($week_data['hash2']) && empty($week_data['hash3']) ? 'selected' : ''; ?>>3</option>
            <option value="4" <?php echo !empty($week_data['hash3']) ? 'selected' : ''; ?>>4</option>
        </select><br><br>

        <!-- Campos de Transação -->
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div id="hash<?php echo $i; ?>" style="display: <?php echo !empty($week_data['hash' . ($i - 1)]) ? 'inline' : ($i === 1 ? 'inline' : 'none'); ?>">
                <label style="display: inline; " for="hash[<?php echo $i - 1; ?>]">Transaction Hash <?php echo $i; ?>:</label>
                <input style="display: inline; " type="text" name="hash[<?php echo $i - 1; ?>]" value="<?php echo htmlspecialchars($week_data['hash' . ($i - 1)]); ?>" size="65">

                <label style="display: inline; " for="type[<?php echo $i - 1; ?>]">Transaction Type:</label>
                <select style="display: inline; " id="type<?php echo $i - 1; ?>" name="type[<?php echo $i - 1; ?>]" onchange="showAmountField(<?php echo $i - 1; ?>)">
                    <option value="">Select...</option>
                    <option value="DeFi" <?php echo $week_data['type' . ($i - 1)] == 'DeFi' ? 'selected' : ''; ?>>DeFi</option>
                    <option value="CEX" <?php echo $week_data['type' . ($i - 1)] == 'CEX' ? 'selected' : ''; ?>>CEX</option>
                    <option value="Binance" <?php echo $week_data['type' . ($i - 1)] == 'Binance' ? 'selected' : ''; ?>>Binance</option>
                    <option value="SEPA" <?php echo $week_data['type' . ($i - 1)] == 'SEPA' ? 'selected' : ''; ?>>SEPA</option>
                    <option value="Pix" <?php echo $week_data['type' . ($i - 1)] == 'Pix' ? 'selected' : ''; ?>>Pix</option>
                </select>

                <label style="display: inline; " for="currency[<?php echo $i - 1; ?>]">Coin:</label>
                <select style="display: inline; " id="currency<?php echo $i - 1; ?>" name="currency[<?php echo $i - 1; ?>]">
                    <option value="USDT" <?php echo $week_data['currency' . ($i - 1)] == 'USDT' ? 'selected' : ''; ?>>USDT</option>
                    <option value="PLT" <?php echo $week_data['currency' . ($i - 1)] == 'PLT' ? 'selected' : ''; ?>>PLT</option>
                    <option value="EUR" <?php echo $week_data['currency' . ($i - 1)] == 'EUR' ? 'selected' : ''; ?>>EUR</option>
                </select>

                <div style="display: inline; " id="amount<?php echo $i - 1; ?>" style="display: <?php echo !empty($week_data['amount' . ($i - 1)]) ? 'inline' : 'none'; ?>">
                    <label style="display: inline; " for="amount[<?php echo $i - 1; ?>]">Transaction Value <?php echo $i; ?>:</label>
                    <input style="display: inline; " type="text" name="amount[<?php echo $i - 1; ?>]" value="<?php echo htmlspecialchars($week_data['amount' . ($i - 1)]); ?>">
                </div>
            </div>
            <br>
            <br>
        <?php endfor; ?>

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