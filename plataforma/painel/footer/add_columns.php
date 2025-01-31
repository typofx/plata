<?php 
include "conexao.php"; // Database connection

// Insert new column
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["column_name"])) {
    $column_name = trim($_POST["column_name"]);

    // Check if the column already exists
    $check_query = "SELECT COUNT(*) as total FROM granna80_bdlinks.plata_footer_columns WHERE column_name = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $column_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($total > 0) {
        echo "<script>alert('This column already exists!');</script>";
    } else {
        // Get the next column order number
        $order_query = "SELECT IFNULL(MAX(column_order), 0) + 1 FROM granna80_bdlinks.plata_footer_columns";
        $result = mysqli_query($conn, $order_query);
        $row = mysqli_fetch_array($result);
        $new_order = $row[0];

        // Insert new column into the table
        $insert_query = "INSERT INTO granna80_bdlinks.plata_footer_columns (column_name, column_order) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "si", $column_name, $new_order);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Column added successfully!'); window.location.href='add_columns.php';</script>";
        } else {
            echo "<script>alert('Error adding column.');</script>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Update column name
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_id"]) && isset($_POST["edit_name"])) {
    $edit_id = $_POST["edit_id"];
    $edit_name = trim($_POST["edit_name"]);

    $update_query = "UPDATE granna80_bdlinks.plata_footer_columns SET column_name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $edit_name, $edit_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Column updated successfully!'); window.location.href='add_columns.php';</script>";
    } else {
        echo "<script>alert('Error updating column.');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Delete column and related items
if (isset($_GET["delete"])) {
    $column_id = $_GET["delete"];

    mysqli_begin_transaction($conn);

    // Delete items from plata_footer linked to this column
    $delete_items_query = "DELETE FROM granna80_bdlinks.plata_footer WHERE column_id = ?";
    $stmt = mysqli_prepare($conn, $delete_items_query);
    mysqli_stmt_bind_param($stmt, "i", $column_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete column from plata_footer_columns
    $delete_query = "DELETE FROM granna80_bdlinks.plata_footer_columns WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $column_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_commit($conn);
        echo "<script>alert('Column deleted successfully!'); window.location.href='add_columns.php';</script>";
    } else {
        mysqli_rollback($conn);
        echo "<script>alert('Error deleting column.');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch existing columns ordered by column_order
$query = "SELECT * FROM granna80_bdlinks.plata_footer_columns ORDER BY column_order";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Footer Columns</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>

    <h2>Manage Footer Columns</h2>
    <a href="index.php">[Back]</a>

    <!-- Form to add a new column -->
    <form method="POST">
        <label for="column_name">Column Name:</label>
        <input type="text" name="column_name" id="column_name" required>
        <button type="submit">Add Column</button>
    </form>

    <br>

    <!-- Table to list existing columns -->
    <table id="columnsTable" class="display">
        <thead>
            <tr>
                <th>Order</th>
                <th>Column Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row["column_order"]; ?></td>
                    <td id="col_name_<?php echo $row["id"]; ?>"><?php echo htmlspecialchars($row["column_name"]); ?></td>
                    <td>
                        <a href="?delete=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure you want to delete this column?');">Delete</a>
                        <a href="#" onclick="editColumn(<?php echo $row['id']; ?>)">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#columnsTable').DataTable({
                "pageLength": 10,
                "ordering": false,
                "language": {
                    "lengthMenu": "Show _MENU_ records per page",
                    "zeroRecords": "No records found",
                    "info": "Showing page _PAGE_ of _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search": "Search:",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        });

        function editColumn(id) {
            let currentName = document.getElementById("col_name_" + id).innerText;
            let newName = prompt("Enter new column name:", currentName);

            if (newName !== null && newName.trim() !== "") {
                let form = document.createElement("form");
                form.method = "POST";
                form.action = "";

                let inputId = document.createElement("input");
                inputId.type = "hidden";
                inputId.name = "edit_id";
                inputId.value = id;

                let inputName = document.createElement("input");
                inputName.type = "hidden";
                inputName.name = "edit_name";
                inputName.value = newName.trim();

                form.appendChild(inputId);
                form.appendChild(inputName);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</body>

</html>
