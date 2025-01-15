<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    // Validate if the name was provided
    if (empty($name)) {
        echo "Please enter the equipment name.";
    } else {
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_equipment (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            echo "Equipment registered successfully!";
        } else {
            echo "Error registering the equipment: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch equipment data for the table
$result = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_equipment");
$equipmentData = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $equipmentData[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Equipment</title>
    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>Register Equipment</h1>
    <form method="POST">
        <label for="name">Equipment Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <button type="submit">Register</button>
        <a href="index.php">[ Back ]</a>
    </form>

    <h2>Manage Equipments</h2>
    <table id="equipmentTable" class="display">
        <thead>
            <tr>
               
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipmentData as $equipment) : ?>
                <tr>
                    
                    <td><?= htmlspecialchars($equipment['name']) ?></td>
                    <td>
                        <a href="edit_equipments.php?id=<?= $equipment['id'] ?>">Edit</a> |
                        <a href="delete_equipments.php?id=<?= $equipment['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('#equipmentTable').DataTable();
        });
    </script>
</body>

</html>
