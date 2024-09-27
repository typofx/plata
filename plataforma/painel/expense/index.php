<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include "conexao.php"; // Include database connection

// Query to fetch data from the spends table
$sql = "SELECT * FROM granna80_bdlinks.spends ORDER BY created_at DESC";
$result = $conn->query($sql);

// Loop through the results to update USDT in the database
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate USDT as cost_eur * eurusdt
        $calculated_usdt = $row['cost_eur'] * $row['eurusdt'];

        // Update USDT in the database
        $update_sql = "UPDATE granna80_bdlinks.spends SET usdt = '$calculated_usdt' WHERE id = " . $row['id'];
        $conn->query($update_sql);
    }
}

// Fetch the data again after updating USDT
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses List</title>


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


    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


</head>

<body>
    <h2>Expenses List</h2>
    <br>

    <a href="add.php">[Add new Expense]</a>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Main menu]</a>
    <br>
    <br>
    <table id="spendsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Year</th>
                <th>Month</th>
                <th>Day</th>
                <th>Good/Service</th>
                <th>Company</th>
                <th>Status</th>
                <th>Cost (EUR)</th>
                <th>USDT</th>
                <th>PLTUSD</th>
                <th>EURUSD</th>
                <th>Generated At</th>
                <th>File (PDF)</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $cont = $result->num_rows;
                // Fetch and display each row of data
                $update_sql = "UPDATE granna80_bdlinks.spends SET spends_number = NULL";
                $conn->query($update_sql);

                while ($row = $result->fetch_assoc()) {

                    $update_number_sql = "UPDATE granna80_bdlinks.spends SET spends_number = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_number_sql);
                    $stmt->bind_param("ii", $cont, $row['id']);
                    $stmt->execute();
                    $bgColor = ($row['status'] == 'pending') ? 'background-color: #fce5a1;' : '';
                    echo "<tr style='$bgColor'>";
                    echo "<td>" .  $cont . "</td>";
                    echo "<td>" . $row['year'] . "</td>";
                    echo "<td>" . $row['month'] . "</td>";
                    echo "<td>" . $row['day'] . "</td>";
                    $date = DateTime::createFromFormat('Y-F-d', $row['year'] . '-' . $row['month'] . '-' . $row['day']);
                    $date = $date->format('d-m-Y');

                    echo "<td>" . $row['good_service'] . "</td>";
                    echo "<td>" . $row['company'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['cost_eur'] . "</td>";
                    echo "<td>" . number_format($row['cost_eur'] * $row['eurusdt'], 2) . "</td>";
                    echo "<td>" . $row['pltusd'] . "</td>";
                    echo "<td>" . $row['eurusdt'] . "</td>";
                    echo "<td>" . date('d-m-Y', strtotime($date)) . "</td>";

                    // Display PDF download/exhibition link if PDF exists
                    if (!empty($row['pdf_path'])) {
                        // View and download links for the PDF
                        echo "<td>
                            <!-- View PDF Link -->
                            <a  style='text-decoration: none;' href='" . $row['pdf_path'] . "' target='_blank'>
                                <img src='https://www.plata.ie/plataforma/img/sheet-icon-pdf.png' alt='View' style='width: 15px; margin-right: 10px;'>
                            </a>
                
                            <!-- Direct Download PDF Link -->
                            <a  style='text-decoration: none;' href='" . $row['pdf_path'] . "' download>
                                <img src='https://www.plata.ie/plataforma/img/sheet-icon-pdf-download.png' alt='Download' style='width: 17px; margin-right: 10px;'>
                            </a>
                          </td>";
                    } else {
                        echo "<td></td>";
                    }


                    echo "<td>
                    <a style='text-decoration: none;' href='edit.php?id=" . $row['id'] . "'>
                        <img src='https://www.plata.ie/plataforma/img/sheet-icon-edit.png' alt='Edit' style='width: 15px; margin-right: 5px;'>
                    </a>
                    <a style='text-decoration: none;' href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this record?');\">
                        <img src='https://www.plata.ie/plataforma/img/sheet-icon-delete.png' alt='Delete' style='width: 15px; margin-right: 5px;'>
                        
                    </a>
                </td>";
                    echo "</tr>";
                    echo "</tr>";




                    $cont--;
                }
            } else {
                echo "<tr><td colspan='13'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>

    <script>
        $(document).ready(function() {
            $.fn.dataTable.moment('DD-MM-YYYY');

            $('#spendsTable').DataTable({
                "order": [],
                "columnDefs": [{
                    "targets": 11, // A coluna 11 contém as datas
                    "render": function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return moment(data, 'DD-MM-YYYY').format('YYYYMMDD');
                        }
                        return data;
                    }
                }]
            });
        });
    </script>





</body>

</html>

<?php
$conn->close();
?>