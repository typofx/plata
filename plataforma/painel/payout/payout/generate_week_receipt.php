<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
require_once 'vendor/autoload.php';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="week_receipt.pdf"');

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
        $start_date = date('d M Y', strtotime($row['start_week']));
        $end_date = date('d M Y', strtotime($row['end_week']));
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

        $currency = $row['currency'];

        $eur_paid = ($eur0 + $eur1 + $eur2 + $eur3);
        $eur_paid = number_format($eur_paid, 2, '.', '');
        $plt_paid = ($plt0 + $plt1 + $plt2 + $plt3);
        $plt_paid = number_format($plt_paid, 4, '.', ',');
        $amount_paid = ($amount0 + $amount1 + $amount2 + $amount3);
        $amount_paid = number_format($amount_paid, 2, '.', '');
        // $working_hours;

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
            <title>Invoice</title>
            <style>
                body {
                    font-family: Verdana, sans-serif;
                    font-size: 10px;
                }

                img {
                    max-width: 100px;
                    max-height: 100px;
                }

                .container {
                    width: 80%;
                    margin: 10px auto;
                    max-width: 794px;
                    max-height: 1096px;
                }

                .header-address-container {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                }

                .header {
                    display: flex;
                    align-items: center;
                    gap: 50px;
                    font-size: 15px;
                }

                .address {
                    margin-top: 10px;
                    text-align: right;
                    font-size: 9px;

                }

                .address2 {
                    margin-top: 10px;
                    text-align: right;


                }

                .address img {
                    float: right;

                    margin-left: 10px;

                }


                .content {
                    clear: both;
                    margin-top: 20px;
                }

                .content-title {
                    font-size: 28px;
                }

                .table-title {
                    font-size: 14px;
                    text-align: center;
                }

                .service {
                    width: 100%;
                    border-collapse: collapse;
                }

                .service-title {
                    text-align: left;
                }

                .border {
                    border-top: 1px solid black;
                    border-bottom: 1px solid black;
                    padding: 8px;
                }

                .service-content {
                    padding: 8px;
                }

                .total {
                    font-size: 16px;
                    font-weight: lighter;
                }

                .footer {
                    margin-top: 50px;
                }

                .page-break {
                    page-break-before: always;
                }

                .qr-code-grid {
                    position: absolute;
                    top: 0;
                    left: 0;
                    margin: 70px;
                    display: grid;
                    grid-template-columns: 1fr 1fr;

                    gap: 10px;

                }

                .qr-code-grid img {
                    width: 75px;

                    height: 75px;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div class="header-address-container">



                    <div class="header address2">
                        <div> Typo FX</div>
                        <br>
                        <img src="https://plata.ie/plataforma/painel/payout/typofx-2024.png" alt="Logo">


                    </div>
                    <div class="address">


                        <br><b>Typo FX LTD</b><br> <br>
                        Workhub Group<br>
                        77 Camden Street Lower<br>
                        Saint Kevin's Dublin<br>
                        D02 XE80 Ireland<br><br>
                        CRO: 770759<br>
                        VAT: 1282313RA
                    </div>
                    <div class="qr-code-grid">
                        <?php

                        $hash = $row['hash0'];
                        $hash1 = $row['hash1'];
                        $hash2 = $row['hash2'];
                        $hash3 = $row['hash3'];

                        // Exibindo os valores dos hashes para depuração
                        //echo "<p>Hash: $hash </p><br>";
                        //echo "<p>Hash1: $hash1</p><br>";
                        //echo "<p>Hash2: $hash2</p><br>";
                        //echo "<p>Hash3: $hash3</p><br>";

                        if (!empty($hash)) {
                            $qrCodeUrl = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash);
                            echo '<img src="' . $qrCodeUrl . '" alt="QR Code" style="height: 60px; width: 60px;">';
                        }
                        if (!empty($hash1)) {
                            $qrCodeUrl1 = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash1);
                            echo '<img src="' . $qrCodeUrl1 . '" alt="QR Code" style="height: 60px; width: 60px;">';
                        }
                        if (!empty($hash2)) {
                            $qrCodeUrl2 = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash2);
                            echo '<img src="' . $qrCodeUrl2 . '" alt="QR Code" style="height: 60px; width: 60px;">';
                        }
                        if (!empty($hash3)) {
                            $qrCodeUrl3 = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash3);
                            echo '<img src="' . $qrCodeUrl3 . '" alt="QR Code" style="height: 60px; width: 60px;">';
                        }

                        ?>

                    </div>

                    <div class="content">
                        <b>Name: <?php echo !empty($employee_name) ? $employee_name : '0'; ?></b> <br>

                        <h1 class="content-title">Billing Invoice</h1>
                        <br> Services Rendered: Computing Service
                        <br> Invoice serial number: XX.XX.XX.00
                        <br> Document issue date: <?php echo date('d F Y'); ?>
                        <br>
                        <h6 class="table-title">Services</h6>
                        <table class="service">
                            <tr>
                                <th class="service-title border">Day</th>
                                <th class="service-title border">Date</th>
                                <th class="service-title border">Total</th>
                            </tr>
                            <tr>
                                <td class="service-content">Monday till Friday</td>
                                <td class="service-content"><?php echo $start_date . ' - ' . $end_date; ?></td>
                                <td class="service-content"></td>
                            </tr>
                            <tr>
                                <td class="service-content"></td>
                                <td class="service-content"></td>
                                <td class="service-content"></td>
                            </tr>
                            <tr>
                                <td class="border">Plata Token Assets<br>Stablecoin</td>
                                <td class="border"></td>
                                <td class="service-content border"><?php echo !empty($plt_paid) ? $plt_paid : '0'; ?> PLT<br><?php echo !empty($amount_paid) ? $amount_paid : '0'; ?> USDT</td>
                            </tr>
                        </table>
                        <h6 class="table-title">Invoice Total</h6>
                        <table class="service">
                            <tr>
                                <th class="service-title border">Totals</th>
                                <th class="service-title border">Total</th>
                                <th class="service-title border">Gross</th>
                            </tr>
                            <tr>
                                <td class="service-content">Worked Hours</td>
                                <td class="service-content"><?php echo !empty($working_hours) ? $working_hours : '0'; ?> hr</td>
                                <td class="service-content"><?php echo !empty($plt_paid) ? $plt_paid : '0'; ?> PLT</td>
                            </tr>
                            <tr>
                                <td class="service-content">Transaction Fee</td>
                                <td class="service-content">(0.00) USDT</td>
                                <td class="service-content">(0.00) USDT</td>
                            </tr>
                            <tr>
                                <td class="service-content">Adjustments (USDT)</td>
                                <td class="service-content">--</td>
                                <td class="service-content">--</td>
                            </tr>
                            <tr>
                                <td class="border">Total</td>
                                <td class="border"></td>
                                <td class="border"><?php echo !empty($amount_paid) ? $amount_paid : '0'; ?> USDT</td>
                            </tr>
                        </table>

                        <h1 class="total"><b>Total fee payable: <?php echo !empty($eur_paid) ? $eur_paid : '0'; ?> EUR</b></h1>
                        <p class="footer">This billing invoice is issued in the name, and on behalf of, the supplier <?php echo !empty($employee_name) ? $employee_name : '0'; ?> in
                            accordance with the terms agreement between the parties</p>
                    </div>
                </div>
        </body>



        </html>

<?php
        $html = ob_get_clean();



        $dompdf->loadHtml($html);


        $dompdf->render();


        $dompdf->stream(
            'Invoice_' . (!empty($employee_name) ? $employee_name : '0') . '.pdf',
            array('Attachment' => 0)
        );
    } else {
        "No records found for this week and employee ID.";
    }

    $stmt->close();
} else {
    "Week or employee ID not provided.";
}

$conn->close();
?>
<div class="page-break"></div>