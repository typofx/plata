<?php
 include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
// Include the database connection file
include "conexao.php";

// Function to generate JSON with table data
function generateJson($conn) {
    $query = "SELECT * FROM granna80_bdlinks.typofx_service_editable_items";
    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Save data to a JSON file
    file_put_contents('editable_itens.json', json_encode($data));
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Add new item
        if ($action === 'add') {
            $field1 = $_POST['field1'];
            $field2 = $_POST['field2'];
            $field3 = $_POST['field3'];

            $query = "INSERT INTO granna80_bdlinks.typofx_service_editable_items (field1, field2, field3) VALUES ('$field1', '$field2', '$field3')";
            if ($conn->query($query)) {
                echo "Item added successfully!";
            } else {
                echo "Error: " . $conn->error;
            }
        }

        // Edit existing item
        if ($action === 'edit') {
            $id = $_POST['id'];
            $field1 = $_POST['field1'];
            $field2 = $_POST['field2'];
            $field3 = $_POST['field3'];

            $query = "UPDATE granna80_bdlinks.typofx_service_editable_items SET field1='$field1', field2='$field2', field3='$field3' WHERE id=$id";
            if ($conn->query($query)) {
                echo "Item updated successfully!";
            } else {
                echo "Error: " . $conn->error;
            }
        }

        // Delete item
        if ($action === 'delete') {
            $id = $_POST['id'];

            $query = "DELETE FROM granna80_bdlinks.typofx_service_editable_items WHERE id=$id";
            if ($conn->query($query)) {
                echo "Item deleted successfully!";
            } else {
                echo "Error: " . $conn->error;
            }
        }

        // Regenerate JSON after any action
        generateJson($conn);
    }
}
$checkQuery = "SELECT COUNT(*) as total FROM granna80_bdlinks.typofx_service_editable_items";
$checkResult = $conn->query($checkQuery);
$row = $checkResult->fetch_assoc();
$isDisabled = ($row['total'] > 0) ? "disabled" : "";
// Generate JSON on page load
generateJson($conn);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editable Items</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
<a href="index.php">[Back]</a>
    <!-- Add New Item Button -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addItemModal" <?php echo $isDisabled; ?>>
            <i class="fas fa-plus"></i> Add New Item
        </button>


        <!-- Table -->
        <table id="itemsTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Field 1</th>
                    <th>Field 2</th>
                    <th>Field 3</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the table
                $query = "SELECT * FROM granna80_bdlinks.typofx_service_editable_items";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['field1']}</td>
                            <td>{$row['field2']}</td>
                            <td>{$row['field3']}</td>
                            <td>
                                <button class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#editItemModal' data-id='{$row['id']}' data-field1='{$row['field1']}' data-field2='{$row['field2']}' data-field3='{$row['field3']}'><i class='fas fa-edit'></i></button>
                                <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteItemModal' data-id='{$row['id']}'><i class='fas fa-trash'></i></button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addItemForm" method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="field1" class="form-label">Field 1</label>
                            <input type="text" class="form-control" id="field1" name="field1" required>
                        </div>
                        <div class="mb-3">
                            <label for="field2" class="form-label">Field 2</label>
                            <input type="text" class="form-control" id="field2" name="field2" required>
                        </div>
                        <div class="mb-3">
                            <label for="field3" class="form-label">Field 3</label>
                            <input type="text" class="form-control" id="field3" name="field3" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="addItemForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editItemForm" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_field1" class="form-label">Field 1</label>
                            <input type="text" class="form-control" id="edit_field1" name="field1" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_field2" class="form-label">Field 2</label>
                            <input type="text" class="form-control" id="edit_field2" name="field2" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_field3" class="form-label">Field 3</label>
                            <input type="text" class="form-control" id="edit_field3" name="field3" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="editItemForm" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Item Modal -->
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemModalLabel">Delete Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item?</p>
                    <form id="deleteItemForm" method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" id="delete_id" name="id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteItemForm" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- jQuery -->

    <!-- Custom Script -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#itemsTable').DataTable();

            // Populate edit modal with data
            $('#editItemModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const field1 = button.data('field1');
                const field2 = button.data('field2');
                const field3 = button.data('field3');
                $('#edit_id').val(id);
                $('#edit_field1').val(field1);
                $('#edit_field2').val(field2);
                $('#edit_field3').val(field3);
            });

            // Set delete ID in delete modal
            $('#deleteItemModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                $('#delete_id').val(id);
            });
        });
    </script>
</body>
</html>