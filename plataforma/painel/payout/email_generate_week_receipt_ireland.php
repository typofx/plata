<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
require_once 'vendor/autoload.php';



use Dompdf\Dompdf;
use Dompdf\Options;


$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);


$dompdf = new Dompdf($options);
// Include the database connection
ob_start();
include 'conexao.php';



if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week = $_GET['week'];
    $employee_id = $_GET['employee_id'];
    global $employee_email;

    // Specify the database before the table name
    $sql = "SELECT ww.*, p.employee, p.rate, p.pay_type 
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
        $employee_email = $row['employee_email'];
        $start_date = date('d M Y', strtotime($row['start_week']));
        $end_date = date('d M Y', strtotime($row['end_week']));
        $isuedate = $row['created_at'];
        $status = $row['status'];
        $rate = $row['rate'];
        $pay_type = $row['pay_type'];
        $working_hours = $row['working_hours'];
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
    <title>Pay advice</title>
    <link rel="stylesheet" href="https://www.plata.ie/sandbox/ux/payadvice/index.css">
</head>

 <body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <label class="text-title" for="company"> Company </label>
                <label class="text"> TypoFX</label>
            </div>
            <h1 class="title">Pay Advice</h1>
        </div>
        <div class="table">
            <div class="column1">
                <div class="header-column1">
                    <label class="text-header-column1">NI Number - </label>
                    <label class="text">1234</label>
                </div>
                <table>
                    <tr>
                        <th class="table-header">Description</th>
                        <th class="table-header">Hours</th>
                        <th class="table-header">Rate</th>
                        <th class="table-header">Amount</th>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Salary</label></td>
                        <td class="table-text"> <label >40</label></td>
                        <td class="table-text"><label >20</label></td>
                        <td class="table-text"><label>800</label></td>
                    </tr>
                </table>
            </div>
            <div class="column2">
                <div class="header-column2">
                    <label class="text-header-column2">Pay Period - </label>
                    <label class="text">Week</label>
                </div>
                <table>
                    <tr>
                        <th class="table-header">Description</th>
                        <th class="table-header">Amount</th>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Pay</label></td>
                        <td class="table-value"><label >800</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Payge Tax</label></td>
                        <td class="table-value"><label >66.34</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Nat Ins.</label></td>
                        <td class="table-value"><label >74.04</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >EE Pension</label></td>
                        <td class="table-value" ><label >0</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >ER Pension</label></td>
                        <td class="table-value"><label >0</label></td>
                    </tr>
                </table>
            </div>
            <div class="column3">
                <div class="header-column3">
                    <label class="text-header-column3">Pay Method - </label>
                    <label class="text">Bank</label>
                </div>
                <table>
                    <tr>
                        <th class="table-header">Description</th>
                        <th class="table-header">Amount</th>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Pay</label></td>
                        <td class="table-value"><label >41600</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Payge Tax</label></td>
                        <td class="table-value"><label >3449.80</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >Nat Ins.</label></td>
                        <td class="table-value"><label >3850.08</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >EE Pension</label></td>
                        <td class="table-value"><label >0</label></td>
                    </tr>
                    <tr>
                        <td class="table-text"><label >ER Pension</label></td>
                        <td class="table-value"><label >0</label></td>
                    </tr>
                </table>
            </div>
            <div class="footer">
                <div class="footer-column1">
                    <label class="footer-header">Pay period</label>
                    <label class="table-text"> 1 </label>
                </div>
                <div class="footer-column2">
                    <label class="footer-header">Date</label>
                    <label class="table-text"> 28/11/2024 </label>
                </div>
                <div class="footer-column3">
                    <label class="footer-header">Department</label>
                    <label class="table-text"> 1 </label>
                </div>
                <div class="footer-column4">
                    <label class="footer-header">Tax code</label>
                    <label class="table-text"> 1234 </label>
                </div>
                <div class="footer-column5">
                    <label class="footer-header cl5">Employee NO</label>
                    <label class="table-text"> 10 </label>
                </div>
                <div class="footer-column6">
                    <label class="footer-header cl6">Employee Name</label>
                    <label class="table-text"> <?php $employee_name?></label>
                </div>
                <div class="footer-column7">
                    <label class="footer-header">Net Pay</label>
                    <label class="table-text"> 6511119.62 </label>
                </div>
            </div>
        </div>
    </div>
</body>



        </html>

<?php
        $html = ob_get_clean();



        $dompdf->loadHtml($html);


        $dompdf->render();
        $pdfContent = $dompdf->output();
        // Enviar o PDF por e-mail
        $to = $employee_email;
        $subject = 'Typo FX Weekly Receipt';
        $message = 'Please find attached the PDF receipt for the period from ' . $start_date . ' to ' . $end_date . 
        'Employee Name: ' . (!empty($employee_name) ? $employee_name : 'N/A');


        $boundary = md5(uniqid(time()));

        $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "$message\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/pdf; name=\"week_receipt.pdf\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"week_receipt.pdf\"\r\n\r\n";

        $body .= chunk_split(base64_encode($pdfContent)) . "\r\n";
        $body .= "--$boundary--";

        if (mail($to, $subject, $body, $headers)) {
            // Atualizar o banco de dados para marcar o e-mail como enviado
            $update_sql = "UPDATE granna80_bdlinks.work_weeks SET email_sent = 1 WHERE work_week = ? AND employee_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            if ($update_stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $update_stmt->bind_param('si', $week, $employee_id);
            if ($update_stmt->execute()) {
                echo 'Email enviado e status atualizado com sucesso!';
                echo "<script>window.location.href='work_weeks.php?employee_id=" . $employee_id . "';</script>";
            } else {
                echo 'Email enviado, mas falha ao atualizar o status.';
            }
            $update_stmt->close();
        } else {
            echo 'Erro ao enviar o email.';
        }
    } else {
        echo "No records found for this week and employee ID.";
    }

    $stmt->close();
} else {
    echo "Week or employee ID not provided.";
}

$conn->close();
?>
<div class="page-break"></div>