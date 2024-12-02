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
include 'conexao.php'; // Include the database connection file

if (isset($_GET['month']) && isset($_GET['employee_id'])) {
    $month_name = $_GET['month'];
    $employee_id = $_GET['employee_id'];

    // Convert the month name to the month number
    $month_number = date('m', strtotime($month_name));
    $year = date('Y'); // Current year

    // Adjust the year if the requested month is earlier than the current month
    if ($month_number > date('m')) {
        $year--;
    }

    // Function to calculate the number of weeks in a specific month from the database
    function getWeeksInMonthFromDatabase($conn, $month, $year, $employee_id)
    {
        // Calculate the first and last day of the month
        $start_of_month = "$year-$month-01";
        $end_of_month = date('Y-m-t', strtotime($start_of_month));

        $sql = "SELECT * FROM granna80_bdlinks.work_weeks WHERE employee_id = ? AND (status = 'Paid' OR status = 'Processing')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $weeks_count = 0;
        while ($row = $result->fetch_assoc()) {
            $start_week = new DateTime($row['start_week']);
            $end_week = new DateTime($row['end_week']);
            $working_hours = $row['working_hours'];

            // Check if the week is within the month's range
            if ($start_week <= new DateTime($end_of_month) && $end_week >= new DateTime($start_of_month)) {
                $weeks_count++;
            }
        }

        return $weeks_count;
    }

    // Function to calculate the total payment for the month
    function calculateMonthlyInvoice($conn, $month_number, $year, $employee_id, $month_name)
    {
        $weeks_in_month = getWeeksInMonthFromDatabase($conn, $month_number, $year, $employee_id);

        // Get the employee's payment rate
        $sql = "SELECT * FROM granna80_bdlinks.payout WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rate = $row['rate'];
            global $name;
            $name = $row['employee'];



            $sql_hours = "SELECT hash0, hash1, hash2, hash3, working_hours, employee_id, amount0, amount1, amount2, amount3, pltusd0,pltusd1,pltusd2,pltusd3, plt0, plt1, plt2, plt3, plteur0, plteur1, plteur2, plteur3 FROM granna80_bdlinks.work_weeks WHERE employee_id = ? AND month = ?;";
            $stmt_hours = $conn->prepare($sql_hours);
            $stmt_hours->bind_param('is', $employee_id, $month_name);
            $stmt_hours->execute();
            $result_hours = $stmt_hours->get_result();

            $total_eur_paid = 0;
            $total_plt_paid = 0;
            $total_amount_paid = 0;
            $total_working_hours = 0;

            if ($result_hours->num_rows > 0) {
                $qrCodesHtml = '';
                while ($row_hours = $result_hours->fetch_assoc()) {
                    $working_hours = $row_hours['working_hours'];

                    // Check and assign value to amount_paid
                    $amount_paid = 0;

                    // Loop through each amount field
                    for ($i = 0; $i <= 3; $i++) {
                        $field = 'pltusd' . $i;
                        $amount = isset($row_hours[$field]) ? $row_hours[$field] : 0;
                        $amount = ($amount === NULL || $amount === '' || $amount === '0') ? 0 : (float)$amount;
                        $amount_paid += $amount;
                    }

                    $plt_paid = 0;

                    for ($i = 0; $i <= 3; $i++) {
                        $field = 'plt' . $i;
                        $plt = isset($row_hours[$field]) ? $row_hours[$field] : 0;
                        $plt = ($plt === NULL || $plt === '' || $plt === '0') ? 0 : (float)$plt;
                        $plt_paid += $plt;
                    }

                    $eur_paid = 0;

                    for ($i = 0; $i <= 3; $i++) {
                        $field = 'plteur' . $i;
                        $eur = isset($row_hours[$field]) ? $row_hours[$field] : 0;
                        $eur = ($eur === NULL || $eur === '' || $eur === '0') ? 0 : (float)$eur;
                        $eur_paid += $eur;
                    }

                    $hash = $row_hours['hash0'];
                    $hash1 = $row_hours['hash1'];
                    $hash2 = $row_hours['hash2'];
                    $hash3 = $row_hours['hash3'];

                    //echo "Hash: " . $hash . "<br>";
                    //echo "Hash1: " . $hash1 . "<br>";
                    //echo "Hash2: " . $hash2 . "<br>";
                    //echo "Hash3: " . $hash3 . "<br>";

                    $qrCodes = [];


                    if (!empty($hash)) {
                        $qrCodes[] = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash);
                    }
                    if (!empty($hash1)) {
                        $qrCodes[] = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash1);
                    }
                    if (!empty($hash2)) {
                        $qrCodes[] = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash2);
                    }
                    if (!empty($hash3)) {
                        $qrCodes[] = 'https://quickchart.io/qr?text=https://polygonscan.com/tx/' . urlencode($hash3);
                    }

                    // Maximum number of columns
                    $columns = 12;
                    $rows = 1;


                    //$qrCodesHtml .= '<!--<div >-->';
                    //$qrCodesHtml .= '<table>';

                    for ($row = 0; $row < $rows; $row++) {
                        //$qrCodesHtml .= '<!--<tr>-->';
                        for ($col = 0; $col < $columns; $col++) {
                            $index = $row * $columns + $col;
                            if ($index < count($qrCodes)) {
                                $qrCodeUrl = $qrCodes[$index];
                                $qrCodesHtml .= '<!--<td>--><img src="' . $qrCodeUrl . '" alt="QR Code" style="height: 40px; width: 40px;"><!--</td>-->';
                            } else {
                                $qrCodesHtml .= '<!--<td></td>-->';
                            }
                        }
                        //$qrCodesHtml .= '<!--</tr>-->';
                    }

                    //$qrCodesHtml .= '<!--</table>-->';
                    //$qrCodesHtml .= '<!--</div>-->';


                    // Debugging output
                    //echo "Working Hours: " . $working_hours . "<br>";
                    //echo "Amount Paid: " . $amount_paid . "<br>";

                    // Accumulates the values
                    $total_amount_paid += $amount_paid;
                    $total_working_hours += $working_hours;
                    $total_plt_paid += $plt_paid;
                    $total_eur_paid += $eur_paid;
                }
                //echo 'valor total:' . $total_amount_paid;
                //echo 'hora total:' . $total_working_hours;
                // Calculate the total amount based on the weeks and total amount paid
                //$total_amount = $weeks_in_month * $total_amount_paid;
                $total_eur = $total_eur_paid;
                $total_plt = $total_plt_paid;
                $total_amount = $total_amount_paid;
                $total_hours  = $total_working_hours;
                // Converter o número do mês para o nome do mês
                $month_name = strftime('%B', strtotime("$year-$month_number-01"));

                $start_date = date("d M Y", strtotime("$year-$month_number-01"));
                $end_date = date("d M Y", strtotime("$year-$month_number-" . date('t', strtotime($start_date))));


                // Data
                ucfirst($month_name) . $year;
                $weeks_in_month;
                number_format($rate, 2);
                number_format($total_amount, 2);
                number_format($total_plt, 4);
                $name;



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
                            margin: 0;
                            padding: 0;
                            //border: 1px solid black;
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

                        .address3 {
                            top: 100px;
                            text-align: left;


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
                    </style>




                </head>

                <body>
                    <div class="container">
                        <div class="header-address-container">

                            <div class="header address2">
                                <img src="https://plata.ie/plataforma/painel/payout/typofx-2024.png" alt="Logo">


                            </div>

                            <div class="address">


                                <div></div><br>Typo FX LTD<br> <br>
                                Workhub Group<br>
                                77 Camden Street Lower
                                Saint Kevin's<br>
                                Dublin D02 XE80 Ireland<br> <br>
                                CRO: 770759<br>
                                VAT: IE 04325859TH
                            </div>
                        </div>
                        <div class="content">
                            Name: <?php echo !empty($name) ? $name : '0'; ?> <br>

                            <h1 class="content-title">Billing Invoice</h1>
                            <br> Services Rendered: Computing Service
                            <br> Invoice serial number: XX.XX.XX.00
                            <br> Monthly invoice period: <?php echo "$start_date - $end_date"; ?>
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
                                    <td class="border"><?php echo !empty(number_format($total_amount, 2)) ? number_format($total_amount, 2) : '0'; ?> USDT</td>
                                </tr>
                                <tr>
                                    <td class="service-content border">Total</td>
                                    <td class="service-content border"></td>
                                    <td class="service-content border"><?php echo !empty(number_format($total_plt, 4, '.', ',')) ? number_format($total_plt, 4, '.', ',') : '0'; ?> PLT</td>
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
                                    <td class="service-content"><?php echo !empty($total_hours) ? $total_hours  : '0'; ?> hr</td>
                                    <td class="service-content"><?php echo !empty(number_format($total_plt, 4, '.', ',')) ? number_format($total_plt, 4, '.', ',') : '0'; ?> PLT</td>
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
                                    <td class="border"><?php echo !empty(number_format($total_amount, 2)) ? number_format($total_amount, 2) : '0'; ?> USDT</td>
                                </tr>
                            </table>
                            <div class="page-break"></div>

                            <table class="service">
                                <tr>

                                    <td class="border"></td>

                                </tr>
                            </table>


                            <h1 class="total"><b>Total fee payable: <?php echo !empty(number_format($total_eur, 2)) ? number_format($total_eur, 2) : '0'; ?> EUR</b></h1>
                            <p class="footer">This billing invoice is issued in the name, and on behalf of, the supplier <?php echo !empty($name) ? $name : '0'; ?> in
                                accordance with the terms agreement between the parties</p>
                        </div>
                        <?php echo $qrCodesHtml; ?>
                    </div>
                </body>



                </html>

<?php
            } else {
                echo "No working hours found for the specified month and year.";
            }
        } else {
            echo "No payment information found for the selected employee.";
        }

        $stmt->close();
    }

    calculateMonthlyInvoice($conn, $month_number, $year, $employee_id, $month_name);

    $conn->close();
} else {
    echo "Month and employee ID not provided.";
}
$html = ob_get_clean();



$dompdf->loadHtml($html);

$dompdf->render();


$dompdf->stream(
    'Monthly_invoice_' . (!empty($name) ? $name : '0') . '.pdf',
    array('Attachment' => 0)
);

?>