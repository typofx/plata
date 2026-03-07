<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainee Tasks</title>
    <link rel="stylesheet" href="dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <a href="/plataforma/panel/main.php">[Back]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <a href="add.php">[Add New Record]</a>

    <h1>Trainee Tasks</h1>
    <table id="traineeTasksTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Code</th>
                <th>Task Code</th>
                <th>Deployed</th>
                <th>Assignment</th>
                <th>Status</th>
                <th>Person</th>
                <th>Type</th>
                <th>Head</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows go here -->
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#traineeTasksTable').dataTable({
                "lengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "pageLength": 50
            });
        });
    </script>
</body>

</html>