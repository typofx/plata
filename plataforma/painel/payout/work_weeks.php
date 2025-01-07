<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $year_date = isset($_GET['year']) ? $_GET['year'] : date('Y');

    $sql_employee = "SELECT employee, employee_email, uuid FROM granna80_bdlinks.payout WHERE id = ?";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->bind_param("i", $employee_id);
    $stmt_employee->execute();
    $result_employee = $stmt_employee->get_result();

    if ($result_employee->num_rows > 0) {
        $employee_data = $result_employee->fetch_assoc();
        $employee = $employee_data['employee'];
        global $uuid;
        global $email;
        $uuid = $employee_data['uuid'];
        //echo $uuid;
    } else {
        echo "Employee not found.";
        exit();
    }
    $search_uuid = $uuid;
    $query_uuid = "SELECT id FROM granna80_bdlinks.team WHERE uuid = ?";
    $prepared_stmt_uuid = $conn->prepare($query_uuid); // Prepare the query
    $prepared_stmt_uuid->bind_param("s", $search_uuid); // Bind the UUID parameter
    $prepared_stmt_uuid->execute(); // Execute the query
    $result_set_uuid = $prepared_stmt_uuid->get_result(); // Get the result

    if ($result_set_uuid->num_rows > 0) {
        // If a result is found, get the ID
        $record_uuid = $result_set_uuid->fetch_assoc();
        $ern = $record_uuid['id'];
        // echo "The corresponding ID for the UUID is: " . $ern;
    } else {
        echo "No record found for the provided UUID.";
    }

    $prepared_stmt_uuid->close();

    $uuid_search = $uuid; // Replace this value with the UUID you want to search for

    // Query to get the email by UUID
    $query_uuid = "SELECT private_email FROM granna80_bdlinks.team_docs WHERE uuid = ?";
    $prepared_stmt_uuid = $conn->prepare($query_uuid); // Prepare the query
    $prepared_stmt_uuid->bind_param("s", $uuid_search); // Bind the UUID parameter
    $prepared_stmt_uuid->execute(); // Execute the query
    $result_set_uuid = $prepared_stmt_uuid->get_result(); // Get the result

    if ($result_set_uuid->num_rows > 0) {
        // If a result is found, get the email
        $record_uuid = $result_set_uuid->fetch_assoc();
        $email = $record_uuid['private_email'];
        //echo "The email corresponding to the UUID is: " . $email;
    } else {
        echo "No record found for the provided UUID.";
    }

    $prepared_stmt_uuid->close();

    $sql_weeks = "SELECT * 
    FROM granna80_bdlinks.work_weeks 
    WHERE employee_id = ? 
    AND YEAR(end_week) = ? 
    ORDER BY work_week DESC";

    $stmt_weeks = $conn->prepare($sql_weeks);
    $stmt_weeks->bind_param("ii", $employee_id, $year_date);
    $stmt_weeks->execute();
    $result_weeks = $stmt_weeks->get_result();
} else {
    echo "No employee ID provided.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll - Employee: <?php echo htmlspecialchars($employee); ?></title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            background-color: #fff;
        }

        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            text-align: center;
        }

        table.dataTable th,
        table.dataTable td,
        table.dataTable tr {
            padding: 8px 12px;
            text-align: center;
        }

        table.dataTable th {
            background-color: #fff;
            text-align: center;
        }



        table.dataTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
        }

        .icon-spacing {
            margin-right: 10px;

        }

        .status-column {
            display: flex;
            align-items: center;
     
         
            gap: 8px;
 
        }
    </style>
</head>

<body>
    <h1>Payroll - Employee: <?php echo htmlspecialchars($employee); ?></h1>
    <h4>Employee email: <?php echo htmlspecialchars($email); ?></h4>
    <br>
    <br>

    <?php

    $current_year = isset($_GET['year']) ? $_GET['year'] : '2024';


    $years = ['2024', '2025'];


    $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : '1';
    ?>

    <form method="GET" action="">
        <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">
        <select name="year" onchange="this.form.submit()">
            <?php foreach ($years as $year) : ?>
                <option value="<?php echo $year; ?>" <?php echo $year == $current_year ? 'selected' : ''; ?>>
                    <?php echo $year; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php

    $current_year = isset($_GET['year']) ? $_GET['year'] : '2025';


    $sql = "SELECT * 
    FROM granna80_bdlinks.work_weeks 
    WHERE employee_id = $employee_id 
    AND YEAR(end_week) = '$current_year' 
    ORDER BY work_week DESC";



    $result = $conn->query($sql);

    $totalWage = 0;
    $totalWagePlt = 0;
    $totalWageEur = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $totalWage += ($row['pltusd0'] + $row['pltusd1'] + $row['pltusd2'] + $row['pltusd3']);
            $totalWagePlt += (float) $row['plt0'] + (float) $row['plt1'] + (float) $row['plt2'] + (float) $row['plt3'];
            $totalWageEur += ((float) ($row['pltusd0'] + $row['pltusd1'] + $row['pltusd2'] + $row['pltusd3']) / $row['weekly_value_plteur']);
        }
    }

    echo "<p>$current_year : " . number_format($totalWage, 2, '.', '') . " (USDT) : "
        . number_format($totalWageEur, 2, '.', '') . " (EUR) : "
        . number_format($totalWagePlt, 4, '.', ',') . " (PLT)</p>";

    $result->data_seek(0);
    ?>






    <a href="add_week.php?employee_id=<?php echo $employee_id ?>" onclick="return confirm('Are you sure you want to add a new week?')">[Add New Week]</a>
    <a href="reset_employee_emails.php?employee_id=<?php echo $employee_id ?>">[Reset all emails]</a>
    <a href="<?php include $_SERVER['DOCUMENT_ROOT']; ?>/plataforma/painel/team/docs/edit.php?id=<?php echo $ern ?>">[DOCS]</a>
    <a href="<?php include $_SERVER['DOCUMENT_ROOT']; ?>/plataforma/painel/payout/edit.php?id=<?php echo $employee_id ?>" onclick="return confirm('Are you sure you want to add a new week?')">[Edit Payout]</a>
    <a href="index.php">[Back]</a>
    <br><br>
    <table id="weeksTable" class="display">
        <thead>
            <tr>
                <th>#</th>
                <th>Week</th>
                <th>Year</th>
                <th>Monday - Friday</th>
                <th>Month</th>

                <th>Status</th>
                <th>Wage (USDT)</th>
                <th>PLTUSDT</th>
                <th>EURUSDT</th>
                <th>Generated on</th>
                <th>WEEK</th>
                <th>MONTH</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_weeks->num_rows > 0) {
                $cont = $result_weeks->num_rows;
                function getDaysInMonthRange($start_date, $end_date, $month, $year)
                {
                    $start = max(strtotime("first day of $month $year"), strtotime($start_date));
                    $end = min(strtotime("last day of $month $year"), strtotime($end_date));

                    if ($start > $end) {
                        return 0;
                    }

                    return (int) ((($end - $start) / 86400) + 1);
                }

                function isLastWeekOfMonth($start_date, $end_date)
                {
                    // Último dia do mês de início
                    $last_day_of_start_month = date('t', strtotime($start_date));

                    // Verifica se a semana se estende para o próximo mês
                    $is_end_of_next_month = date('d', strtotime($end_date)) <= 7 && date('m', strtotime($start_date)) != date('m', strtotime($end_date));

                    // Conta quantos dias dessa semana pertencem ao mês de início
                    $days_in_start_month = 0;
                    $days_in_next_month = 0;

                    // Itera por cada dia entre $start_date e $end_date
                    $current_date = strtotime($start_date);
                    $end_date_timestamp = strtotime($end_date);

                    while ($current_date <= $end_date_timestamp) {
                        // Verifica se a data atual pertence ao mês de início
                        if (date('m', $current_date) == date('m', strtotime($start_date))) {
                            $days_in_start_month++;
                        } else {
                            $days_in_next_month++;
                        }
                        // Avança para o próximo dia
                        $current_date = strtotime('+1 day', $current_date);
                    }

                    // Se a maioria dos dias da semana estiver no próximo mês, não conta como última semana do mês de início
                    if ($days_in_next_month > $days_in_start_month) {
                        return false;
                    }

                    // Verifica se a semana está nos últimos dias do mês de início ou se se estende para o próximo mês
                    return (date('d', strtotime($start_date)) >= 23 && date('d', strtotime($start_date)) <= $last_day_of_start_month) || $is_end_of_next_month;
                }



                function allWeeksPaid($month, $employee_id, $conn)
                {
                    $check_status_query = "SELECT COUNT(*) AS pending_count 
                           FROM granna80_bdlinks.work_weeks 
                           WHERE month = ? AND employee_id = ? AND status IN ('Processing', 'Pending')";
                    $status_stmt = $conn->prepare($check_status_query);
                    $status_stmt->bind_param("si", $month, $employee_id);
                    $status_stmt->execute();
                    $status_result = $status_stmt->get_result();
                    $status_row = $status_result->fetch_assoc();
                    $status_stmt->close();

                    return $status_row['pending_count'] == 0;
                }

                //echo $month;
                function calculateTotalMonthlyWage($month, $employee_id, $conn)
                {
                    // Consulta para somar os valores pltusd0, pltusd1, pltusd2 e pltusd3 e contar as semanas
                    $query = "
    SELECT COUNT(*) AS week_count, 
           SUM(COALESCE(pltusd0, 0) + COALESCE(pltusd1, 0) + COALESCE(pltusd2, 0) + COALESCE(pltusd3, 0)) AS total_wage
    FROM granna80_bdlinks.work_weeks
    WHERE month = ? AND employee_id = ?
";


                    // Prepara e executa a consulta
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $month, $employee_id); // Associa os parâmetros: mês e ID do funcionário
                    $stmt->execute();

                    // Obtém o resultado da consulta
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    // Fecha a declaração
                    $stmt->close();

                    // Verifica se existem semanas no mês e retorna o valor total do salário ou 0
                    if ($row['week_count'] > 0) {
                        return $row['total_wage'] ?? 0;
                    } else {
                        return 0;
                    }
                }





                while ($row = $result_weeks->fetch_assoc()) {

                    $start_date = $row['start_week'];
                    $end_date = $row['end_week'];

                    $start_month = date('F', strtotime($start_date));
                    $start_year = date('Y', strtotime($start_date));
                    $end_month = date('F', strtotime($end_date));
                    $end_year = date('Y', strtotime($end_date));

                    $days_in_start_month = getDaysInMonthRange($start_date, $end_date, $start_month, $start_year);
                    $days_in_end_month = getDaysInMonthRange($start_date, $end_date, $end_month, $end_year);

                    if ($days_in_start_month > $days_in_end_month) {
                        $month_to_display = $start_month;
                        $is_end_of_month = isLastWeekOfMonth($start_date, $end_date);
                    } else {
                        $month_to_display = $end_month;
                        $is_end_of_month = isLastWeekOfMonth($start_date, $end_date);
                    }

                    $update_counter_query = "UPDATE granna80_bdlinks.work_weeks SET week_counter = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_counter_query);
                    $stmt->bind_param("ii", $cont, $row['id']);
                    $stmt->execute();
                    $stmt->close();

                    $update_query = "UPDATE granna80_bdlinks.work_weeks SET month = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si", $month_to_display, $row['id']);
                    $stmt->execute();
                    $stmt->close();

                    $update_query = "UPDATE granna80_bdlinks.work_weeks SET employee_email = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si",  $email, $row['id']);
                    $stmt->execute();
                    $stmt->close();





                    if ($row['status'] == 'Paid') {
                        $week_receipt_icon = "<a href='generate_week_receipt.php?week={$row['work_week']}&employee_id=$employee_id' target='_blank'><i class='fa-solid fa-receipt'></i></a>";
                    } else {
                        $week_receipt_icon = "<i class='fa-solid fa-receipt' style='color: grey;'></i>";
                    }

                    if ($row['status'] == 'Paid') {
                        if ($row['email_sent'] == 1) {

                            $week_receipt_icon_email = "<img src='https://www.plata.ie/images/sheet-icon-email_sent.png' alt='Email Sent' style='margin-right: 10px;'>";
                        } else {

                            $week_receipt_icon_email = "<a style='text-decoration: none;' href='email_generate_week_receipt.php?week={$row['work_week']}&employee_id=$employee_id' onclick='return confirmEmailSend();'>
                                                            <img src='email_tobe.png' alt='Email To Be Sent' style='margin-right: 10px;'>
                                                        </a>";
                        }
                    } else {

                        $week_receipt_icon_email = "<img src='email_tobe_b.png' alt='Email To Be Sent' style='margin-right: 10px; color: grey;'>";
                    }

                    $generated = $row['created_at'];


                    if ($is_end_of_month) {
                        // Verifica se todas as semanas do mês estão pagas
                        if (allWeeksPaid($month_to_display, $employee_id, $conn)) {
                            // Calcula o total do mês para todas as semanas
                            $total_monthly_wage = calculateTotalMonthlyWage($month_to_display, $employee_id, $conn);


                            $invoice_icon = "
                            <span>" . number_format($total_monthly_wage, 2, '.', ',') . "</span>
                            <a href='generate_invoice.php?month=$month_to_display&employee_id=$employee_id' target='_blank'>
                                <i class='fa-solid fa-receipt'></i>
                            </a>
                        ";
                        } else {
                            // Exibe o ícone de invoice desabilitado se nem todas as semanas foram pagas
                            $invoice_icon = "<i class='fa-solid fa-receipt' style='color: grey;'></i>"; // Ícone desabilitado
                        }
                    } else {
                        // Não exibe o ícone de invoice se não for o final do mês
                        $invoice_icon = '';
                    }






                    $year = date('Y', strtotime($row['end_week']));

                    $update_query = "UPDATE granna80_bdlinks.work_weeks SET year_date = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si",  $year, $row['id']);
                    $stmt->execute();
                    $stmt->close();

                    echo "<tr>
                    <td><b>{$cont}</b></td>
        <td><b>" . intval($row['work_week']) . "</b></td>
        <td>" . $year . "</td>
        <td>" . date('d M', strtotime($row['start_week'])) . " - " . date('d M', strtotime($row['end_week'])) . "</td>
        <td>{$month_to_display}</td>";



                    $wage = $row['pltusd0'] + $row['pltusd1'] + $row['pltusd2'] + $row['pltusd3'];

                    $wageplt = $row['plt0'] + $row['plt1'] + $row['plt2'] + $row['plt3'];

                    $wageeur = $row['plteur0'] + $row['plteur1'] + $row['plteur2'] + $row['plteur3'];


                    $plt_total =
                        (isset($row['current_plt_price0']) ? (float)$row['current_plt_price0'] : 0) +
                        (isset($row['current_plt_price1']) ? (float)$row['current_plt_price1'] : 0) +
                        (isset($row['current_plt_price2']) ? (float)$row['current_plt_price2'] : 0) +
                        (isset($row['current_plt_price3']) ? (float)$row['current_plt_price3'] : 0);

                    $plt_count =
                        (isset($row['current_plt_price0']) ? 1 : 0) +
                        (isset($row['current_plt_price1']) ? 1 : 0) +
                        (isset($row['current_plt_price2']) ? 1 : 0) +
                        (isset($row['current_plt_price3']) ? 1 : 0);

                    $PLTVALUE = number_format($plt_count > 0 ? $plt_total / $plt_count : 0, 10);

                    // Cálculo para current_eur_price
                    $eur_total =
                        (isset($row['current_eur_price0']) ? (float)$row['current_eur_price0'] : 0) +
                        (isset($row['current_eur_price1']) ? (float)$row['current_eur_price1'] : 0) +
                        (isset($row['current_eur_price2']) ? (float)$row['current_eur_price2'] : 0) +
                        (isset($row['current_eur_price3']) ? (float)$row['current_eur_price3'] : 0);

                    $eur_count =
                        (isset($row['current_eur_price0']) ? 1 : 0) +
                        (isset($row['current_eur_price1']) ? 1 : 0) +
                        (isset($row['current_eur_price2']) ? 1 : 0) +
                        (isset($row['current_eur_price3']) ? 1 : 0);

                    $EURVALUE = number_format($eur_count > 0 ? $eur_total / $eur_count : 0, 4);

                    $status_icons = [
                        'Paid' => "<i class='fa-solid fa-circle-check' style='color: #00ff33;'></i>",
                        'Pending' => "<i class='fa-solid fa-circle-xmark' style='color: #ff0000;'></i>",
                        'Processing' => "<i class='fa-solid fa-hourglass-half' style='color: #ffaa00;'></i>",
                        'Holiday' => "<i class='fa-solid fa-umbrella-beach' style='color: #007bff;'></i>",
                        'Holiday (Paid)' => "<i class='fa-solid fa-sun' style='color: #00ff33;'></i>",
                        'Holiday (Off)' => "<i class='fa-solid fa-sun' style='color: #ff9900;'></i>",
                        'Running' => "<i class='fa-solid fa-gears' style='color: #808080;'></i>",
                        'Off' => "<i class='fa-solid fa-power-off' style='color: #cccccc;'></i>"
                    ];

                    // Get the icon for the current status
                    $status_icon = isset($status_icons[$row['status']]) ? $status_icons[$row['status']] : '';



                    echo "
                <td class='status-column'>⠀⠀⠀⠀⠀{$status_icon}<span class='status-text'>{$row['status']}</span></td>
                      <td>" . number_format($wage, 2, '.', ',') . "</td>
                    <td>" . $PLTVALUE . "</td>
                    <td>" . $EURVALUE . "</td>
                    <td>" . substr($generated, 0, 10) . "</td>
                    <td>{$week_receipt_icon}</td>
                    <td>{$invoice_icon}</td>
                    <td>
                        <a href='edit_week.php?week={$row['work_week']}&employee_id=$employee_id' style='text-decoration: none;'>
                            <img src='https://www.plata.ie/plataforma/img/sheet-icon-edit.png' alt='Edit' style='margin-right: 10px;'>
                        </a>
           
                            $week_receipt_icon_email

                  
                        <a href='delete_week.php?week={$row['work_week']}&employee_id=$employee_id' style='text-decoration: none;'>
                            <img src='https://www.plata.ie/plataforma/img/sheet-icon-delete.png' alt='Delete'>
                        </a>
                    </td>
                </tr>";
                    $cont--;
                }
            } else {
                //echo "<tr><td colspan='8'>No work weeks found</td></tr>";
            }
            ?>
        </tbody>
    </table>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        function confirmEmailSend() {
            return confirm("Are you sure you want to send the email?");
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#weeksTable').DataTable({
                "lengthMenu": [
                    [50, 100, 150, 250],
                    [50, 100, 150, 250]
                ],
                "columnDefs": [{
                        "width": "20px",
                        "targets": 0
                    },
                    {
                        "width": "100px",
                        "targets": 7
                    },

                ],
                "order": [
                    [1, 'desc']
                ],
            "language": {
                "emptyTable": "There is no data for the selected year!" 
            }
            });
        });
    </script>


</body>

</html>

<?php
$stmt_employee->close();
$stmt_weeks->close();
$conn->close();
?>