<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
$session_info = ob_get_clean();
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
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="top-header">
        <span class="session-info"><?php echo $session_info; ?></span>
        <a href="https://typofx.ie/plataforma/panel/">[Control Panel]</a>
        <a href="javascript:window.location.reload(true)">[Refresh]</a>
        <a href="add.php">[Add New Record]</a>
    </div>

    <h1>User Permissions Table</h1>
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
                    <tr class="main-row">
                        <td>#<?php echo $cont; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><a href="<?php echo $row['link']; ?>" target="_blank"><?php echo $row['link']; ?></a></td>
                        <td><input type="checkbox" name="guest[<?php echo $row['id']; ?>]" <?php if ($row['guest']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="trainee[<?php echo $row['id']; ?>]" <?php if ($row['trainee']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="admin[<?php echo $row['id']; ?>]" <?php if ($row['admin']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="root[<?php echo $row['id']; ?>]" <?php if ($row['root']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="office[<?php echo $row['id']; ?>]" <?php if ($row['office']) echo 'checked'; ?>></td>
                        <td><input type="checkbox" name="block[<?php echo $row['id']; ?>]" <?php if ($row['block']) echo 'checked'; ?>></td>
                        <td><a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a></td>
                    </tr>
                    <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">

                    <?php if (isset($submenus[$row['id']])) { ?>
                        <tr class="submenu-row">
                            <td colspan="10" class="submenu-cell">
                                <div class="submenu-label">Submenus:</div>
                                <ul class="submenu-list-items">
                                    <?php foreach ($submenus[$row['id']] as $submenu) { ?>
                                        <li>
                                            <a href="<?php echo $submenu['link']; ?>" target="_blank"><?php echo $submenu['name']; ?></a>
                                           
                                        </li>
                                    <?php } ?>
                                </ul>
                                <a href="add_submenu.php?parent_id=<?php echo $row['id']; ?>" class="add-submenu-link">[ Add Submenu ]</a>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr class="submenu-row">
                            <td colspan="10" class="submenu-cell">
                                <a href="add_submenu.php?parent_id=<?php echo $row['id']; ?>" class="add-submenu-link">[ Add Submenu ]</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php
                    $cont++;
                } ?>
            </tbody>
        </table>
        <div class="button-container">
            <button type="submit">Save Changes</button>
        </div>
    </form>
</body>

</html>

<?php mysqli_close($conn); ?>