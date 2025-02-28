<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

// Fetch all rows from the user_permissions table
$query = "SELECT * FROM granna80_bdlinks.user_permissions";
$result = mysqli_query($conn, $query);

// Fetch all submenus grouped by parent_id
$submenuQuery = "SELECT * FROM granna80_bdlinks.submenus";
$submenuResult = mysqli_query($conn, $submenuQuery);

$submenus = [];
while ($row = mysqli_fetch_assoc($submenuResult)) {
    $submenus[$row['parent_id']][] = $row;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['id'] as $id) {
        $guest = isset($_POST['guest'][$id]) ? 1 : 0;
        $trainee = isset($_POST['trainee'][$id]) ? 1 : 0;
        $admin = isset($_POST['admin'][$id]) ? 1 : 0;
        $root = isset($_POST['root'][$id]) ? 1 : 0;
        $office = isset($_POST['office'][$id]) ? 1 : 0;
        $block = isset($_POST['block'][$id]) ? 1 : 0;

        $updateQuery = "UPDATE granna80_bdlinks.user_permissions 
                        SET guest = $guest, trainee = $trainee, admin = $admin, root = $root, office = $office, block = $block 
                        WHERE id = $id";
        mysqli_query($conn, $updateQuery);
    }
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Permissions</title>
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

        button {
            background-color: #67458b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        button:hover {
            background-color: #9362C6;
        }

        a {
            color: #9362C6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .submenu {
            margin-left: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>
    <h1>User Permissions Table</h1>
    <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php';?>">[Back]</a>
    <a href="add.php">[ Add new Link ]</a>
    <a href="component-links.php">[ Main Menu link ]</a>
    <form action="index.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Guest</th>
                    <th>Trainee</th>
                    <th>Admin</th>
                    <th>Root</th>
                    <th>Office</th>
                    <th>Block</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cont = 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>#<?php echo $cont; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><a href="<?php echo $row['link']; ?>" target="_blank"><?php echo $row['link']; ?></a></td>
                        <td><input type="checkbox" name="guest[<?php echo $row['id']; ?>]" <?php if ($row['guest']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="trainee[<?php echo $row['id']; ?>]" <?php if ($row['trainee']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="admin[<?php echo $row['id']; ?>]" <?php if ($row['admin']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="root[<?php echo $row['id']; ?>]" <?php if ($row['root']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="office[<?php echo $row['id']; ?>]" <?php if ($row['office']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="block[<?php echo $row['id']; ?>]" <?php if ($row['block']) echo 'checked'; ?>></td>
                        <td><a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                    </tr>
                    <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">

                    <?php if (isset($submenus[$row['id']])) { ?>
                        <tr>
                            <td colspan="9">
                                <strong>Submenus:</strong>
                                <ul class="submenu">
                                    <?php foreach ($submenus[$row['id']] as $submenu) { ?>
                                        <li>
                                            <a href="<?php echo $submenu['link']; ?>" target="_blank"><?php echo $submenu['name']; ?></a>
                                           
                                        </li>
                                    <?php } ?>
                                </ul>
                                <a href="add_submenu.php?parent_id=<?php echo $row['id']; ?>">[ Add Submenu ]</a>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9">
                                <a href="add_submenu.php?parent_id=<?php echo $row['id']; ?>">[ Add Submenu ]</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php
                    $cont++;
                } ?>
            </tbody>
        </table>
        <button type="submit">Save Changes</button>
    </form>
</body>

</html>

<?php mysqli_close($conn); ?>