<?php
// Include database connection file
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Granna users</title>
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
    </style>
</head>

<body>
    <h2>User List</h2>
    <!-- Define table structure for displaying users -->
    <table id="usersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Birth Date</th>
                <th>Phone</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch users from database
            $query = "SELECT id, name, last_name, email, birth, CONCAT(ddi, ' ', phone) AS phone, username, created_at FROM granna80_bdlinks.granna_users";
            $result = mysqli_query($conn, $query);

            // Check if there are any results and display them
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['last_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['birth']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['username']}</td>
                         <td>
                                <a href='edit_user.php?id={$row['id']}' class='btn btn-primary'>Edit</a>
                                <a href='delete_user.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found.</td></tr>";
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
            $('#usersTable').DataTable();
        });
    </script>
</body>

</html>