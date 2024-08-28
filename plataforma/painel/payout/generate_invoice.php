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
    function calculateMonthlyInvoice($conn, $month_number, $year, $employee_id)
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
            

     
            $sql_hours = "SELECT working_hours FROM granna80_bdlinks.work_weeks WHERE employee_id = ?";
            $stmt_hours = $conn->prepare($sql_hours);
            $stmt_hours->bind_param('i', $employee_id);
            $stmt_hours->execute();
            $result_hours = $stmt_hours->get_result();

            if ($result_hours->num_rows > 0) {
                $row_hours = $result_hours->fetch_assoc();
                $working_hours = $row_hours['working_hours'];

                // Calculate the total amount based on the number of weeks and the rate
                $total_amount = $weeks_in_month * $rate;

                // Convert the month number to the month name
                $month_name = strftime('%B', strtotime("$year-$month_number-01"));

                $start_date = date("d M Y", strtotime("$year-$month_number-01"));
                $end_date = date("d M Y", strtotime("$year-$month_number-" . date('t', strtotime($start_date))));



                // Data
                ucfirst($month_name) . $year;
                $weeks_in_month;
                number_format($rate, 2);
                number_format($total_amount, 2);
                $name;
                $total_hours = ($working_hours * $weeks_in_month);


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
                                    <td class="service-content"><?php echo !empty(number_format($total_amount, 2)) ? number_format($total_amount, 2) : '0'; ?> USDT</td>
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

                            <h1 class="total"><b>Total fee payable: <?php echo !empty(number_format($total_amount, 2)) ? number_format($total_amount, 2) : '0'; ?> USDT</b></h1>
                            <p class="footer">This billing invoice is issued in the name, and on behalf of, the supplier Adam Soares in
                                accordance with the terms agreement between the parties</p>
                        </div>
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

    calculateMonthlyInvoice($conn, $month_number, $year, $employee_id);

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