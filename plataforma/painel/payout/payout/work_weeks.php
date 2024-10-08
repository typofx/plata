<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';


if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];


    $sql_employee = "SELECT employee, employee_email FROM granna80_bdlinks.payout WHERE id = ?";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->bind_param("i", $employee_id);
    $stmt_employee->execute();
    $result_employee = $stmt_employee->get_result();

    if ($result_employee->num_rows > 0) {
        $employee_data = $result_employee->fetch_assoc();
        $employee = $employee_data['employee'];
        global $email;
        $email = $employee_data['employee_email'];
    } else {
        echo "Employee not found.";
        exit();
    }


    $sql_weeks = "SELECT * FROM granna80_bdlinks.work_weeks WHERE employee_id = ?";
    $stmt_weeks = $conn->prepare($sql_weeks);
    $stmt_weeks->bind_param("i", $employee_id);
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
    </style>
</head>

<body>
    <h1>Payroll - Employee: <?php echo htmlspecialchars($employee); ?></h1>
    <br>
    <br>

    <a href="add_week.php?employee_id=<?php echo $employee_id ?>" onclick="return confirm('Are you sure you want to add a new week?')">[Add New Week]</a>
    <a href="reset_employee_emails.php?employee_id=<?php echo $employee_id ?>" >[Reset all emails]</a>

    <a href="index.php">[Back]</a>
    <br><br>
    <table id="weeksTable" class="display">
        <thead>
            <tr>
                <th>Week</th>
                <th>Year</th>
                <th>Monday - Friday</th>
                <th>Month</th>
                <th>Txn Hash</th>
                <th>Status</th>
                <th>WEEK</th>
                <th>MONTH</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_weeks->num_rows > 0) {
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

                    $last_day_of_start_month = date('t', strtotime($start_date));


                    $is_end_of_next_month = date('d', strtotime($end_date)) <= 7 && date('m', strtotime($start_date)) != date('m', strtotime($end_date));


                    return (date('d', strtotime($start_date)) >= 25 && date('d', strtotime($start_date)) <= $last_day_of_start_month) || $is_end_of_next_month;
                }

                // Função para verificar se todas as semanas do mês estão pagas
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

                    // Atualiza o mês no banco de dados
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


                    // Geração do ícone de recibo semanal
                    if ($row['status'] == 'Paid') {
                        $week_receipt_icon = "<a href='generate_week_receipt.php?week={$row['work_week']}&employee_id=$employee_id' target='_blank'><i class='fa-solid fa-receipt'></i></a>";
                    } else {
                        $week_receipt_icon = "<i class='fa-solid fa-receipt' style='color: grey;'></i>";
                    }

                    if ($row['status'] == 'Paid') {
                        if ($row['email_sent'] == 1) {
                            // Mostrar ícone de e-mail enviado se já foi enviado
                            $week_receipt_icon_email = "<img src='https://www.plata.ie/images/sheet-icon-email_sent.png' alt='Email Sent' style='margin-right: 10px;'>";
                        } else {
                            // Mostrar ícone de e-mail para enviar
                            $week_receipt_icon_email = "<a style='text-decoration: none;' href='email_generate_week_receipt.php?week={$row['work_week']}&employee_id=$employee_id'>
                                                            <img src='email_tobe.png' alt='Email To Be Sent' style='margin-right: 10px;'>
                                                        </a>";
                        }
                    } else {
                        // Mostrar ícone de e-mail para enviar com estilo alterado para outros status
                        $week_receipt_icon_email = "<img src='email_tobe_b.png' alt='Email To Be Sent' style='margin-right: 10px; color: grey;'>";
                    }


                    // Geração do ícone de invoice (somente no final do mês)
                    if ($is_end_of_month) {
                        // Verifica se todas as semanas do mês estão pagas
                        if (allWeeksPaid($month_to_display, $employee_id, $conn)) {
                            $invoice_icon = "<a href='generate_invoice.php?month=$month_to_display&employee_id=$employee_id' target='_blank'><i class='fa-solid fa-receipt'></i> </a>";
                        } else {
                            $invoice_icon = "<i class='fa-solid fa-receipt' style='color: grey;'></i>"; // Ícone desabilitado
                        }
                    } else {
                        $invoice_icon = '&nbsp;'; // Não exibe o ícone fora do final do mês
                    }


                   





                    echo "<tr>
        <td><b>{$row['work_week']}</b></td>
        <td>" . date('Y', strtotime($row['start_week'])) . "</td>
        <td>" . date('d M', strtotime($row['start_week'])) . " - " . date('d M', strtotime($row['end_week'])) . "</td>
        <td>{$month_to_display}</td>
        <td>";

                    if (strpos($row['hash0'], '0x') === 0) {
                        echo '<i class="fa-solid fa-circle-check" style="color: #00ff33;"></i>';
                    } else {
                        echo '<i class="fa-solid fa-circle-xmark" style="color: #ff0000;"></i>';
                    }


                    echo "</td>
                    <td>{$row['status']}</td>
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
                }
            } else {
                echo "<tr><td colspan='8'>No work weeks found</td></tr>";
            }
            ?>
        </tbody>
    </table>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
                        "width": "20px",
                        "targets": 7
                    }
                ],
                "order": [
                    [0, 'desc']
                ]
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