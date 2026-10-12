<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
$session_info = ob_get_clean();
include 'conexao.php';

function getLinkFromDatabase($conn) {
    $sql = "SELECT * FROM granna80_bdlinks.back_link_menu LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc(); 
    }
    return null; 
}

$linkData = getLinkFromDatabase($conn);
$linkExists = !is_null($linkData);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $linkUrl = $conn->real_escape_string($_POST['link_url']);
    $description = $conn->real_escape_string($_POST['description']);

    if ($linkExists) {
        $sql = "UPDATE granna80_bdlinks.back_link_menu SET link_url = '$linkUrl', description = '$description' WHERE id = {$linkData['id']}";
    } else {
        $sql = "INSERT INTO granna80_bdlinks.back_link_menu (link_url, description) VALUES ('$linkUrl', '$description')";
    }

    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Button Link Manager</title>
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header-container">
        <div class="session-info"><?php echo $session_info; ?></div>
    </div>

    <h1>Back Button Link Manager</h1>

    <div style="text-align: center; margin-bottom: 20px;">
        <a href="index.php">[ Back to List ]</a>
    </div>

    <form method="POST" action="">
        <label for="link_url">Link URL:</label>
        <input type="text" id="link_url" name="link_url" value="<?php echo $linkExists ? $linkData['link_url'] : ''; ?>" required>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo $linkExists ? $linkData['description'] : ''; ?></textarea>
        
        <div class="button-container" style="justify-content: flex-start;">
            <button type="submit" class="btn">
                <?php echo $linkExists ? 'Update Link' : 'Register Link'; ?>
            </button>
        </div>
    </form>

    <h2 style="margin-top: 30px;">Registered Links</h2>
    <div id="linkTable">
        <?php if ($linkExists): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>URL</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $linkData['id']; ?></td>
                        <td><a href="<?php echo $linkData['link_url']; ?>" target="_blank"><?php echo $linkData['link_url']; ?></a></td>
                        <td><?php echo $linkData['description']; ?></td>
                        <td><?php echo $linkData['created_at']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>No links registered yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>