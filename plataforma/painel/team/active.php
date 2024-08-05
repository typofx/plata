<?php
include 'conexao.php';

// Update active status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = intval($_POST['id']);
    $active = isset($_POST['active']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE granna80_bdlinks.team SET active = ? WHERE id = ?");
    $stmt->bind_param('ii', $active, $id);

    if ($stmt->execute()) {
        echo 'Status updated successfully.';
    } else {
        echo 'Error updating status: ' . $conn->error;
    }

    $stmt->close();
}

// Fetch team members
$sql = "SELECT id, teamName, active FROM granna80_bdlinks.team";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currently work here</title>
</head>
<body>
    <h1>Currently work here</h1>

    <a href="https://plata.ie/plataforma/painel/menu.php">[Control Panel]</a>
    <a href="add.php">[Add new member]</a>
    <a href="form.php">[Set JSON]</a>
    <a href="index.php">[Back]</a>
<br>
<br>
<br>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['teamName']) . '</td>';
                    echo '<td>';
                    echo '<form action="" method="post">';
                    echo '<input type="checkbox" name="active" value="1" ' . ($row['active'] ? 'checked' : '') . ' onclick="this.form.submit()">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<input type="hidden" name="action" value="update_status">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">No members found.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
