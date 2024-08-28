<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
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

                .qr-code-container {
                    position: absolute;
                    top: 0;
                    left: 0;
                    margin: 60px;
       
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


                        <br>Typo FX LTD Ireland<br> <br>
                        WorkHub Group<br>
                        77 Camden Street Lower Saint<br>
                        Kevin's Dublin D02 XE80 Ireland<br> <br>
                        CRO: XXXXXX<br>
                        VAT: 1282313RA
                    </div>
                    <?php
                    $hash = $row['hash'];
                    $qrCodeUrl = 'https://quickchart.io/qr?text=' . urlencode($hash);
                    ?>
                    <div class="qr-code-container">
                        <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code">
                        <p>To check the transaction details, scan the QR code.</p>
                    </div>
                </div>
                <div class="content">
                    Name: <?php echo !empty($employee_name) ? $employee_name : '0'; ?> <br>

                    <h1 class="content-title">Billing Invoice</h1>
                    <br> Services Rendered: Computing Service
                    <br> Invoice serial number: XX.XX.XX.00
                    <br> Invoice period: <?php echo $start_date . ' - ' . $end_date; ?>
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
                            <td class="service-content"><?php echo date('d F Y'); ?></td>
                            <td class="service-content"></td>
                        </tr>
                        <tr>
                            <td class="service-content"></td>
                            <td class="service-content"></td>
                            <td class="service-content"></td>
                        </tr>
                        <tr>
                            <td class="border">Total</td>
                            <td class="border"></td>
                            <td class="border"><?php echo !empty($total_payment) ? $total_payment : '0'; ?> USDT</td>
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
                            <td class="service-content"><?php echo !empty($total_payment) ? $total_payment : '0'; ?> USDT</td>
                        </tr>
                        <tr>
                            <td class="service-content">Transaction Fee</td>
                            <td class="service-content">(0.00) USDT</td>
                            <td class="service-content">(0.00) USDT</td>
                        </tr>
                        <tr>
                            <td class="service-content">Adjustments</td>
                            <td class="service-content">--</td>
                            <td class="service-content">--</td>
                        </tr>
                        <tr>
                            <td class="border">Total</td>
                            <td class="border"></td>
                            <td class="border"><?php echo !empty($total_payment) ? $total_payment : '0'; ?> USDT</td>
                        </tr>
                    </table>

                    <h1 class="total"><b>Total fee payable: <?php echo !empty($total_payment) ? $total_payment : '0'; ?> USDT</b></h1>
                    <p class="footer">This billing invoice is issued in the name, and on behalf of, the supplier Adam Soares in
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