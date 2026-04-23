<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
$session_info = ob_get_clean();
include 'conexao.php';

if (!isset($_GET['id'])) {
    die('Item ID not provided.');
}

$id = intval($_GET['id']);

// Fetch the item to be edited
$query = "SELECT * FROM granna80_bdlinks.user_permissions WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die('Item not found.');
}

// Fetch submenus associated with the parent item
$submenuQuery = "SELECT * FROM granna80_bdlinks.submenus WHERE parent_id = $id";
$submenuResult = mysqli_query($conn, $submenuQuery);
$submenus = [];
while ($submenuRow = mysqli_fetch_assoc($submenuResult)) {
    $submenus[] = $submenuRow;
}

// Handle individual submenu deletion
if (isset($_GET['delete_submenu'])) {
    $submenu_id_to_delete = intval($_GET['delete_submenu']);
    $deleteQuery = "DELETE FROM granna80_bdlinks.submenus WHERE id = $submenu_id_to_delete AND parent_id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: edit.php?id=$id");
        exit();
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Delete associated submenus first
        $deleteSubmenusQuery = "DELETE FROM granna80_bdlinks.submenus WHERE parent_id = $id";
        mysqli_query($conn, $deleteSubmenusQuery);

        // Delete the parent item
        $deleteParentQuery = "DELETE FROM granna80_bdlinks.user_permissions WHERE id = $id";
        if (mysqli_query($conn, $deleteParentQuery)) {
            echo "<script>alert('Record deleted successfully'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
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
        }        echo "<script>window.location.href='edit.php?id=$id';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item and Submenus</title>
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header-container">
        <div class="session-info"><?php echo $session_info; ?></div>
    </div>

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
                        <td><input type="text" name="name" value="<?php echo $row['name']; ?>" required style="margin:0;"></td>
                        <td><input type="text" name="link" value="<?php echo $row['link']; ?>" style="margin:0;"></td>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submenus as $submenu) { ?>
                                <tr>
                                    <td><input type="text" name="submenu_name[<?php echo $submenu['id']; ?>]" value="<?php echo $submenu['name']; ?>" required style="margin:0;"></td>
                                    <td><input type="text" name="submenu_link[<?php echo $submenu['id']; ?>]" value="<?php echo $submenu['link']; ?>" style="margin:0;"></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $id; ?>&delete_submenu=<?php echo $submenu['id']; ?>" 
                                           class="btn-delete-small" 
                                           onclick="return confirm('⚠️ Are you sure you want to delete this submenu?')">
                                           <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                    <input type="hidden" name="submenu_id[]" value="<?php echo $submenu['id']; ?>">
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

            <div class="button-container">
                <button type="submit" name="action" value="save" class="btn">Save Changes</button>
                <button type="submit" name="action" value="delete" class="btn btn-delete" onclick="return confirm('⚠️ ATTENTION: Are you sure you want to delete this item and all its submenus? This action cannot be undone.')">Delete</button>
            </div>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php">[ Back to Permissions ]</a>
        </div>
</body>
</html>
<?php mysqli_close($conn); ?>