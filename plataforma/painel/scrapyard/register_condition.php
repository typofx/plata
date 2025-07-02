<?php

include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    function sanitizeInput($data)
    {
        return htmlspecialchars(trim($data));
    }

    $action = $_POST['action'];
    $condition_name = sanitizeInput($_POST['condition_name'] ?? '');


    if ($action === 'add' && !empty($condition_name)) {
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_condition (condition_name) VALUES (?)");
        $stmt->bind_param("s", $condition_name);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'update' && !empty($condition_name)) {
        $id = intval($_POST['condition_id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE granna80_bdlinks.scrapyard_condition SET condition_name = ? WHERE id = ?");
            $stmt->bind_param("si", $condition_name, $id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['condition_id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM granna80_bdlinks.scrapyard_condition WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }


    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


$conditions_result = $conn->query("SELECT id, condition_name FROM granna80_bdlinks.scrapyard_condition ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Conditions</title>

    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
</head>

<body>

    <h1>Manage Scrapyard Conditions</h1>
    <hr>

    <fieldset id="formContainer">
        <legend id="formLegend">Add New Condition</legend>
        <form method="POST" action="register_condition.php" id="conditionForm">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="condition_id" id="condition_id">

            <label for="condition_name_input">Condition Name:</label>
            <input type="text" id="condition_name_input" name="condition_name" required>

            <button type="submit" id="submitButton">Add</button>
            <button type="button" id="cancelButton" style="display: none;">Cancel</button>
        </form>
    </fieldset>

    <hr>

    <h3>Existing Conditions</h3>
    <table id="conditionsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Condition Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($condition = $conditions_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $condition['id'] ?></td>
                    <td><?= htmlspecialchars($condition['condition_name']) ?></td>
                    <td>
                        <button class="edit-btn"
                            data-id="<?= $condition['id'] ?>"
                            data-name="<?= htmlspecialchars($condition['condition_name']) ?>">
                            Edit
                        </button>

                        <form method="POST" action="register_condition.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this condition?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="condition_id" value="<?= $condition['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p><a href="index.php">[ Back ]</a></p>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#conditionsTable').DataTable();


            const formLegend = $('#formLegend');
            const formAction = $('#formAction');
            const conditionIdInput = $('#condition_id');
            const conditionNameInput = $('#condition_name_input');
            const submitButton = $('#submitButton');
            const cancelButton = $('#cancelButton');
            const formContainer = $('#formContainer');


            function resetFormToAddMode() {
                formLegend.text('Add New Condition');
                formAction.val('add');
                conditionIdInput.val('');
                conditionNameInput.val('');
                submitButton.text('Add');
                cancelButton.hide();
                $('#conditionForm')[0].reset();
            }


            $('.edit-btn').on('click', function() {

                const id = $(this).data('id');
                const name = $(this).data('name');


                formLegend.text('Edit Condition (ID: ' + id + ')');
                formAction.val('update');
                conditionIdInput.val(id);
                conditionNameInput.val(name);
                submitButton.text('Save Changes');
                cancelButton.show();


                formContainer[0].scrollIntoView({
                    behavior: 'smooth'
                });
                conditionNameInput.focus();
            });


            cancelButton.on('click', function() {
                resetFormToAddMode();
            });
        });
    </script>

</body>

</html>