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
    <a href="add.php">[ add new record ]</a>
    <!-- Define table structure for displaying records -->
    <table id="linkTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Link</th>
                <th>Status</th>
                <th>Platform</th>
                <th>Observations</th>
                <th>Last Edited By</th>
                <th>Last Updated Date</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch records from the database
            $query = "SELECT id, name, link, status, platform, obs, last_edited_by, last_updated_date, created_at FROM granna80_bdlinks.plata_link_checker";
            $result = mysqli_query($conn, $query);

            // Check if there are any results and display them
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $platformIcon = $row['platform'] === 'mobile' ? "<i class='fas fa-mobile-alt icon-mobile'></i>" : "<i class='fas fa-desktop icon-desktop'></i>";
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td><a href='{$row['link']}' target='_blank'><i class='fas fa-link icon-link icon-spacing'></i>{$row['link']}</a></td>
                            <td>{$row['status']}</td>
                            <td>{$platformIcon}</td>
                            <td>{$row['obs']}</td>
                            <td>{$row['last_edited_by']}</td>
                            <td>{$row['last_updated_date']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <a href='edit.php?id={$row['id']}' class='icon-spacing'><i class='fas fa-edit icon-edit'></i></a>
                                <a href='delete.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this record?');\"><i class='fas fa-trash-alt icon-delete'></i></a>
                            </td>
                          </tr>";
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
