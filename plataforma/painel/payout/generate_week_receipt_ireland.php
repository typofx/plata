<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php

include 'conexao.php';



if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week = $_GET['week'];
    $employee_id = $_GET['employee_id'];

    // Specify the database before the table name
    $sql = "SELECT ww.*, p.employee, p.rate, p.pay_type, p.uuid 
            FROM granna80_bdlinks.work_weeks ww
            JOIN granna80_bdlinks.payout p ON ww.employee_id = p.id
            WHERE ww.work_week = ? AND ww.employee_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('si', $week, $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Receipt details
        $employee_name = $row['employee'];
        $uuid = $row['uuid'];
        $start_date = date('d M Y', strtotime($row['start_week']));
        $end_date = date('d M Y', strtotime($row['end_week']));
        $isuedate = $row['created_at'];
        $status = $row['status'];
        $rate = $row['rate'];
        $pay_type = $row['pay_type'];
        $week_number = $row['work_week'];
        $working_hours = $row['working_hours'];
        $pay_method = $row['type0'];

        $PPS = $row['pps'];
        $PAYE = $row['PAYE'];
        $PRSI = $row['PRSI'];
        $USC = $row['USC'];
        $NETT = $row['NETT'];
        $EUR = $row['eur'];

        $amount0 = $row['pltusd0'];
        $amount1 = $row['pltusd1'];
        $amount2 = $row['pltusd2'];
        $amount3 = $row['pltusd3'];

        $plt0 = $row['plt0'];
        $plt1 = $row['plt1'];
        $plt2 = $row['plt2'];
        $plt3 = $row['plt3'];

        $eur0 = $row['plteur0'];
        $eur1 = $row['plteur1'];
        $eur2 = $row['plteur2'];
        $eur3 = $row['plteur3'];

        $eur = $row['weekly_value_plteur'];
        $plt = $row['weekly_value_pltusd'];

        $currency = $row['currency'];

        $eur_paid = ($eur0 + $eur1 + $eur2 + $eur3);
        $eur_paid = number_format($eur_paid, 2, '.', '');
        $plt_paid = ($plt0 + $plt1 + $plt2 + $plt3);
        $plt_paid = number_format($plt_paid, 4, '.', ',');
        $amount_paid = ($amount0 + $amount1 + $amount2 + $amount3);
        $amount_paid = number_format($amount_paid, 2, '.', '');
        // $working_hours;


        $weekly_value_plteur =  $amount_paid / $eur;
        $weekly_value_pltusd = $amount_paid / $plt;

        $weekly_value_plteur = number_format($weekly_value_plteur, 2, '.', '');
        $weekly_value_pltusd = number_format($weekly_value_pltusd, 2, '.', ',');

        // Calculate the total payment
        $total_payment = ($pay_type == 'Hourly') ? ($rate * $hours_worked) : $rate;

        // Display the receipt
        //   "<h1>Weekly Receipt</h1>";
        //  "<p><strong>Employee:</strong> $employee_name</p>";
        // "<p><strong>Week:</strong> $week</p>";
        // "<p><strong>Period:</strong> $start_date - $end_date</p>";
        //  "<p><strong>Status:</strong> $status</p>";
        // "<p><strong>Payment Type:</strong> $pay_type</p>";
        //  "<p><strong>Rate:</strong> $rate</p>";
        // "<p><strong>Total Payment:</strong> $total_payment</p>";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Advice - PDF Export</title>
    <link rel="stylesheet" href="https://plata.ie/plataforma/painel/payout/payslip/index.css">


</head>
<body>
    <!-- Conteúdo da página -->
    <div id="content-to-print">
 
<p>EMPLOYEE NUMBER: <?php echo htmlspecialchars($uuid); ?></p>





<p>PRSI: <?php echo number_format($PRSI, 2, ',', '.'); ?></p>

<p>TOTAL DEDS: <?php echo number_format($PAYE + $PRSI + $USC, 2, ',', '.'); ?></p>
<p>NETT PAY: </p>
<p>PAY METHOD: <?php echo in_array($pay_method, ['DeFi', 'CEX', 'Binance']) ? 'CRYPTOCURRENCY' : (in_array($pay_method, ['SEPA']) ? 'BANK' : (in_array($pay_method, ['Pix', 'Cash']) ? 'CASH' : htmlspecialchars($pay_method))); ?></p>

        <div class="container">
            <div class="header">
                <div class="header-title">
                    <label class="text-title" for="company">Company</label>
                    <label class="text">Typo FX</label>
                </div>
                <h1 class="title">Payslip</h1>
            </div>
            <div class="table">
                <div class="column1">
                    <div class="header-column1">
                        <label class="text-header-column1">CRO :</label>
                        <label class="text">770759</label>
                    </div>
                    <table>
                        <tr>
                            <th class="table-header">Description</th>
                            <th class="table-header">Hours</th>
                            <th class="table-header">Rate</th>
                            <th class="table-header">Amount</th>
                        </tr>
                        <tr>
                            <td class="table-text"><label>Wages</label></td>
                            <td class="table-text"><label>2</label></td>
                            <td class="table-text"><label>13.87</label></td>
                            <td class="table-value"><label><?php echo number_format($EUR, 2, '.', ','); ?></label></td>
                        </tr>
                    </table>
                </div>
                <div class="column2">
                    <div class="header-column2">
                        <label class="text-header-column2">Frequency :</label>
                        <label class="text">Weekly</label>
                    </div>
                    <table>
                        <tr>
                            <th class="table-header">Description</th>
                            <th class="table-header">Amount</th>
                        </tr>
                        <tr>
                            <td class="table-text"><label>_</label></td>
                            <td class="table-value"><label>_</label></td>
                        </tr>
                    </table>
                </div>
                <div class="column3">
                    <div class="header-column3">
                        <label class="text-header-column3">Pay Method :</label>
                        <label class="text">Cash</label>
                    </div>
                    <table>
                        <tr>
                            <th class="table-header">Description</th>
                            <th class="table-header">Amount</th>
                        </tr>
                        <tr>
                            <td class="table-text"><label>Basic / T</label></td>
                            <td class="table-value"><label><?php echo number_format($EUR, 2, '.', ','); ?></label></td>
                        </tr>
                        <tr>
                            <td class="table-text"><label>PAYEE</label></td>
                            <td class="table-value"><label><?php echo number_format($PAYE, 2, '.', ','); ?></label></td>
                        </tr>
                        <tr>
                            <td class="table-text"><label>USC</label></td>
                            <td class="table-value"><label><?php echo number_format($USC, 2, '.', ','); ?></label></td>
                        </tr>
                    </table>
                </div>
                <div class="footer">
                    <div class="footer-column1">
                        <label class="footer-header">Pay Period</label>
                        <label class="table-text"><?php echo htmlspecialchars($week_number); ?></label>
                    </div>
                    <div class="footer-column2">
                        <label class="footer-header">Date</label>
                        <label class="table-text"><?php echo date('d/m/Y', strtotime($isuedate)); ?></label>
                    </div>
                    <div class="footer-column3">
                        <label class="footer-header">Register</label>
                        <label class="table-text">131316</label>
                    </div>
                    <div class="footer-column4">
                        <label class="footer-header">PPS</label>
                        <label class="table-text"><?php echo $row['pps']?></label>
                    </div>
                    <div class="footer-column5">
                        <label class="footer-header cl5">Employee Name</label>
                        <label class="table-text"><?php echo htmlspecialchars($employee_name); ?></label>
                    </div>
                    <div class="footer-column7">
                        <label class="footer-header">Nett Pay</label>
                        <label class="table-text"><?php echo number_format($NETT, 2, '.', ','); ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
  
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const content = document.getElementById('content-to-print'); // Seleciona o conteúdo para o PDF

        const options = {
            margin: 10,              // Margem ao redor do conteúdo
            filename: 'Payslip.pdf', // Nome do arquivo (apenas exibido no título do PDF)
            image: { type: 'jpeg', quality: 0.98 }, // Tipo e qualidade da imagem
            html2canvas: { scale: 2 }, // Aumenta a qualidade do render
            jsPDF: { unit: 'mm', format: 'a5', orientation: 'portrait' } // Configurações do PDF
        };

        // Gera o PDF e exibe na mesma aba
        html2pdf()
            .set(options)
            .from(content)
            .toPdf()
            .get('pdf')
            .then(pdf => {
                // Converte o PDF para uma URL de dados e abre na aba atual
                const pdfUrl = pdf.output('bloburl'); // Gera um Blob URL do PDF
                window.location.href = pdfUrl;
            });
    });
</script>




    
</body>
</html>
<?php







       


       







    } else {
        "No records found for this week and employee ID.";
    }

    $stmt->close();
} else {
    "Week or employee ID not provided.";
}

$conn->close();
?>

</html>