<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
include 'conexao.php';

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create operation
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $tag = $_POST['tag'];
    
    $sql = "INSERT INTO granna80_bdlinks.finance_tools_groups (name, tag) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $tag);
    
    if ($stmt->execute()) {
        $message = "Record created successfully!";
    } else {
        $message = "Error creating record: " . $conn->error;
    }
    $stmt->close();
}

// Update operation
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $tag = $_POST['tag'];
    
    $sql = "UPDATE granna80_bdlinks.finance_tools_groups SET name=?, tag=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $tag, $id);
    
    if ($stmt->execute()) {
        $message = "Record updated successfully!";
    } else {
        $message = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $sql = "DELETE FROM granna80_bdlinks.finance_tools_groups WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Record deleted successfully!";
    } else {
        $message = "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

// Read operation - Fetch all records
$sql = "SELECT * FROM granna80_bdlinks.finance_tools_groups ORDER BY id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Tools Groups</title>
  
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  
    <style>
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        .dataTables_wrapper {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Finance Tools Groups</h1>
      <a href="index.php">[back]</a>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <!-- Form for Create/Update -->
    <form method="post" action="">
        <input type="hidden" name="id" id="id" value="">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="tag">Tag:</label>
            <input type="text" name="tag" id="tag">
        </div>
        <div>
            <button type="submit" name="create" id="create">Add</button>
            <button type="submit" name="update" id="update" style="display:none;">Update</button>
            <button type="button" id="cancel" style="display:none;" onclick="cancelEdit()">Cancel</button>
        </div>
    </form>
    
    <!-- Table with records -->
    <table id="financeToolsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Tag</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        
                        <td><center><?php echo $row['id']; ?></center></td>
                        <td><center><?php echo htmlspecialchars($row['name']); ?></center></td>
                        <td><center><?php echo htmlspecialchars($row['tag']); ?></center></td>
                        <td>
                            <button onclick="editRecord(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', '<?php echo addslashes($row['tag']); ?>')">Edit</button>
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        </td>
                        
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
 
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#financeToolsTable').DataTable({
            });
        });
        
        // Function to fill form for editing
        function editRecord(id, name, tag) {
            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('tag').value = tag;
            
            document.getElementById('create').style.display = 'none';
            document.getElementById('update').style.display = 'inline-block';
            document.getElementById('cancel').style.display = 'inline-block';
            
            // Scroll to form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Function to cancel editing
        function cancelEdit() {
            document.getElementById('id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('tag').value = '';
            
            document.getElementById('create').style.display = 'inline-block';
            document.getElementById('update').style.display = 'none';
            document.getElementById('cancel').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>