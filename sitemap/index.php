<?php
// Include database connection file
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap</title>
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



        .external-notes {
            text-align: justify;
            word-wrap: break-word;
        
            white-space: normal;
          
            display: block;
        }
    </style>
</head>

<body>
    <h2>Sitemap</h2><br>

    <!-- Define table structure for displaying records -->
    <table id="linkTable" class="display" style="width:100%; font-size: 12px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Local</th>
                <th>Name</th>
                <th>Platform</th>
                <th>Info</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch records from the database
            $query = "SELECT * FROM `granna80_bdlinks`.`plata_link_checker` WHERE `platform` = 'desktop'";
            $result = mysqli_query($conn, $query);

            // Check if there are any results and display them
            function createAnchoredIcon($url, $iconClass, $additionalClasses = '')
            {
                return "<a href='{$url}' target='_blank'><i class='{$iconClass} {$additionalClasses}'></i></a>";
            }

            if (mysqli_num_rows($result) > 0) {
                $cont = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    $local = !empty($row['local']) ? $row['local'] : '';
                    if (stripos($local, 'root') !== false) {
                        continue; 
                    }

                 
                    $lastUpdated = !empty($row['last_updated_date']) ? date('d/m/Y H:i', strtotime($row['last_updated_date'])) . ' (UTC)' : '';

                 
                    $name = !empty($row['name']) ? "<a href='{$row['link']}' target='_blank'>{$row['name']}</a>" : '';

                   
                    $externalNotes = !empty($row['external_note']) ? $row['external_note'] : '';


               
                    $platformIcon = $row['platform'] === 'mobile'
                        ? "<i class='fas fa-mobile-alt icon-mobile'></i>"
                        : "<i class='fas fa-desktop icon-desktop'></i>";

                    echo "<tr>
                        <td>{$cont}</td>
                        <td>{$local}</td>
                        <td>{$name}</td>
                        <td>{$platformIcon}</td>
                        <td class='external-notes' data-full-text='{$row['external_note']}'>{$externalNotes}</td>
                        <td style='white-space: nowrap;'>{$lastUpdated}</td>
                      </tr>";


                    $cont++;
                }
            } else {
                echo "<tr><td colspan='6'>No records found.</td></tr>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="externalNotesModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <p id="modalText"></p>
        </div>
    </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".external-notes").forEach(function(el) {
                if (el.textContent.length > 100) { // Ajuste o valor conforme necessário
                    el.innerHTML = el.innerHTML.replace(/(.{50})/g, "$1<br>"); // Quebra a cada 50 caracteres
                }
            });
        });
    </script>

</body>

</html>