<?php
include 'conexao.php';


function getLinkFromDatabase($conn) {
    $sql = "SELECT * FROM granna80_bdlinks.back_link_menu LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); 
    }
    return null; 
}


function saveLinkToJsonFile($data, $filename = 'data.json') {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    file_put_contents($filename, $jsonData);
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
      
        $linkData = getLinkFromDatabase($conn);
      
        saveLinkToJsonFile($linkData);
   
        header("Location: index.php");
        exit();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}


if ($linkExists) {
    saveLinkToJsonFile($linkData);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Button Link Manager</title>
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
        th, td {
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
    </style>
    <script>
    
        function fetchLinkData() {
            fetch('data.json') 
                .then(response => response.json())
                .then(data => {
                    const linkTable = document.getElementById('linkTable');
                    if (data.error) {
                        linkTable.innerHTML = '<p>No links registered yet.</p>';
                    } else {
                        linkTable.innerHTML = `
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
                                        <td>${data.id}</td>
                                        <td><a href="${data.link_url}" target="_blank">${data.link_url}</a></td>
                                        <td>${data.description}</td>
                                        <td>${data.created_at}</td>
                                    </tr>
                                </tbody>
                            </table>
                        `;
                    }
                })
                .catch(error => console.error('Error fetching link data:', error));
        }


        window.onload = fetchLinkData;

       
        setInterval(fetchLinkData, 30000);
    </script>
</head>
<body>
    <h1>Back Button Link Manager</h1>

    <a href="index.php">[Back]</a>
    <a href="data.json">[JSON]</a>


    <form method="POST" action="">
        <label for="link_url">Link URL:</label>
        <input type="text" id="link_url" name="link_url" value="<?php echo $linkExists ? $linkData['link_url'] : ''; ?>" required>
        <br><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo $linkExists ? $linkData['description'] : ''; ?></textarea>
        <br><br>
        <button type="submit">
            <?php echo $linkExists ? 'Update Link' : 'Register Link'; ?>
        </button>
    </form>


    <h2>Registered Links</h2>
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