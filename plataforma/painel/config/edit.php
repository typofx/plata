<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

if (!isset($_GET['id'])) {
    die('ID do item não fornecido.');
}

$id = intval($_GET['id']);

// Fetch the item to be edited
$query = "SELECT * FROM granna80_bdlinks.user_permissions WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die('Item não encontrado.');
}

// Fetch submenus associated with the parent item
$submenuQuery = "SELECT * FROM granna80_bdlinks.submenus WHERE parent_id = $id";
$submenuResult = mysqli_query($conn, $submenuQuery);
$submenus = [];
while ($submenuRow = mysqli_fetch_assoc($submenuResult)) {
    $submenus[] = $submenuRow;
}

// Process form submission for the parent item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update the parent item
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $guest = isset($_POST['guest']) ? 1 : 0;
    $trainee = isset($_POST['trainee']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;
    $root = isset($_POST['root']) ? 1 : 0;
    $office = isset($_POST['office']) ? 1 : 0;
    $block = isset($_POST['block']) ? 1 : 0;

    $updateQuery = "UPDATE granna80_bdlinks.user_permissions 
                    SET name = '$name', link = '$link', guest = $guest, trainee = $trainee, admin = $admin, root = $root, office = $office, block = $block 
                    WHERE id = $id";
    mysqli_query($conn, $updateQuery);

    // Update submenus if they exist
    if (!empty($_POST['submenu_id'])) {
        foreach ($_POST['submenu_id'] as $submenuId) {
            $submenuName = mysqli_real_escape_string($conn, $_POST['submenu_name'][$submenuId]);
            $submenuLink = mysqli_real_escape_string($conn, $_POST['submenu_link'][$submenuId]);

            $updateSubmenuQuery = "UPDATE granna80_bdlinks.submenus 
                                   SET name = '$submenuName', link = '$submenuLink' 
                                   WHERE id = $submenuId";
            mysqli_query($conn, $updateSubmenuQuery);
        }
    }

    echo "<script>window.location.href='edit.php?id=$id';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item and Submenus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #67458b;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        input[type="text"],
        input[type="checkbox"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        button {
            background-color: #67458b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto 0;
        }

        button:hover {
            background-color: #9362C6;
        }

        .submenu-section {
            margin-top: 20px;
        }

        .submenu-section h2 {
            margin-top: 0;
            color: #67458b;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        a {
            color: #9362C6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <br>
    <br>
    <a href="index.php">[Back]</a>

    <h1>Edit Item and Submenus</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <!-- Parent Item Table -->
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Guest</th>
                    <th>Trainee</th>
                    <th>Admin</th>
                    <th>Root</th>
                    <th>Office</th>
                    <th>Block</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="name" value="<?php echo $row['name']; ?>" required></td>
                    <td><input type="text" name="link" value="<?php echo $row['link']; ?>"></td>
                    <td><input type="checkbox" name="guest" <?php if ($row['guest']) echo 'checked'; ?>></td>
                    <td><input type="checkbox" name="trainee" <?php if ($row['trainee']) echo 'checked'; ?>></td>
                    <td><input type="checkbox" name="admin" <?php if ($row['admin']) echo 'checked'; ?>></td>
                    <td><input type="checkbox" name="root" <?php if ($row['root']) echo 'checked'; ?>></td>
                    <td><input type="checkbox" name="office" <?php if ($row['office']) echo 'checked'; ?>></td>
                    <td><input type="checkbox" name="block" <?php if ($row['block']) echo 'checked'; ?>></td>
                </tr>
            </tbody>
        </table>

        <!-- Submenus Section -->
        <?php if (!empty($submenus)) { ?>
            <div class="submenu-section">
                <h2>Submenus</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Submenu Name</th>
                            <th>Submenu Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submenus as $submenu) { ?>
                            <tr>
                                <td><input type="text" name="submenu_name[<?php echo $submenu['id']; ?>]" value="<?php echo $submenu['name']; ?>" required></td>
                                <td><input type="text" name="submenu_link[<?php echo $submenu['id']; ?>]" value="<?php echo $submenu['link']; ?>"></td>
                                <input type="hidden" name="submenu_id[]" value="<?php echo $submenu['id']; ?>">
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <button type="submit">Save Changes</button>
    </form>
</body>

</html>

<?php mysqli_close($conn); ?>