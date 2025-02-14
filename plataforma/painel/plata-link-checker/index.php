<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
// Include database connection file
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Checker List</title>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include DataTables CSS and jQuery -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
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

        .icon-link {
            color: #007bff;
        }

        .icon-edit {
            color: blue;
        }

        .icon-delete {
            color: red;
        }

        .icon-mobile {
            color: #17a2b8;
        }

        .icon-desktop {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <h2>Link Checker List</h2><br>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Back]</a>
    <a href="add.php">[ add new record ]</a>
    <a href="https://www.plata.ie/sitemap/">[ Sitemap ]</a>
    <!-- Define table structure for displaying records -->
    <table id="linkTable" class="display" style="width:100%;  font-size: 12px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Local</th>
                <th>Name</th>
                <th>Link</th>
                <th>Status</th>
                <th>Platform</th>
                <th>Observations</th>
                <th>External notes</th>
                <th>Last Edited By</th>
                <th>Last Updated</th>
                
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch records from the database
            $query = "SELECT * FROM granna80_bdlinks.plata_link_checker";
            $result = mysqli_query($conn, $query);

            // Check if there are any results and display them
            function createAnchoredIcon($url, $iconClass, $additionalClasses = '')
            {
                return "<a href='{$url}' target='_blank'><i class='{$iconClass} {$additionalClasses}'></i></a>";
            }

            if (mysqli_num_rows($result) > 0) {
                $cont = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $platformIcon = $row['platform'] === 'mobile'
                        ? "<i class='fas fa-mobile-alt icon-mobile'></i>"
                        : "<i class='fas fa-desktop icon-desktop'></i>";

                    // Link e ícone separados
                    $iconLink = createAnchoredIcon($row['link'], 'fas fa-link', 'icon-link icon-spacing');

                    // Lógica de exibição do link completo baseado no status
                    if ($row['status'] === 'ok') {
                        $linkCell = ''; // Apenas o ícone ancorado
                    } else { // Caso 'fail' ou outro status
                        $linkCell = "<a href='{$row['link']}' target='_blank'>{$row['link']}</a>";
                    }

                    if (!empty($row) && is_array($row)) {
                        echo "<tr>
                                <td>{$cont}</td>
                                <td>" . (!empty($row['local']) ? $row['local'] : '') . "</td>
                                <td>" . (!empty($row['name']) ? $row['name'] : '') . "</td>
                                <td>{$linkCell}</td>
                                <td>" . (!empty($row['status']) ? $row['status'] : '') . "</td>
                                <td>{$iconLink} {$platformIcon}</td>
                                <td>" . (!empty($row['obs']) ? $row['obs'] : '') . "</td>
                               <td>" . (!empty($row['external_note']) ? (count(explode(' ', $row['external_note'])) > 4 ? implode(' ', array_slice(explode(' ', $row['external_note']), 0, 4)) . '...' : $row['external_note']) : '') . "</td>
                                <td style='white-space: nowrap;'>" . (!empty($row['last_edited_by']) ? $row['last_edited_by'] : '') . "</td>
                                <td style='white-space: nowrap;'>" . (!empty($row['last_updated_date']) ? $row['last_updated_date'] . ' UTC' : '') . "</td>
                              
                                <td>
                                    <a href='edit.php?id={$row['id']}' class='icon-spacing'><i class='fas fa-edit icon-edit'></i></a>
                                    <a href='delete.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this record?');\"><i class='fas fa-trash-alt icon-delete'></i></a>
                                </td>
                              </tr>";
                    } else {
                        echo "<tr><td colspan='11' style='text-align:center;'>No data</td></tr>";
                    }
                    
                          $cont++;
                }
            } else {
                echo "<tr><td colspan='10'>No records found.</td></tr>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script>
        // Initialize DataTables on page load
        $(document).ready(function() {
            $('#linkTable').DataTable({
                "pageLength": 100, // Set the default number of rows per page to 100
                "lengthMenu": [100, 200, 500, 1000] // Add options for larger page sizes
            });
        });
    </script>

</body>

</html>
<a href="http://"></a>