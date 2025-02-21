<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

// Add image
if (isset($_POST['add'])) {
    $name = $_POST['name'] ?: basename($_FILES['file']['name']); // Use file name if name is left blank
    $description = $_POST['description'];
    $file_path = $_FILES['file']['name'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    // Upload file
    if ($file_path) {
        $target_dir = "/home2/granna80/public_html/website_d7042c63/images/uploads-carrousel/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    }

    // Insert into database
    $sql = "INSERT INTO granna80_bdlinks.typofx_carousell (name, description, file_path, file_type, file_size) VALUES ('$name', '$description', '$file_path', '$file_type', '$file_size')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Image added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Edit image
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $disabled = isset($_POST['disabled']) ? 1 : 0;

    // If a new file is uploaded
    if ($_FILES['file']['name']) {
        $file_path = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];

        // Upload file
        $target_dir = "/home2/granna80/public_html/website_d7042c63/images/uploads-carrousel/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        // Update database with new file
        $sql = "UPDATE granna80_bdlinks.typofx_carousell SET name='$name', description='$description', file_path='$file_path', file_type='$file_type', file_size='$file_size', disabled='$disabled' WHERE id=$id";
    } else {
        // Update database without changing the file
        $sql = "UPDATE granna80_bdlinks.typofx_carousell SET name='$name', description='$description', disabled='$disabled' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Image updated successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Delete image
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete from database
    $sql = "DELETE FROM granna80_bdlinks.typofx_carousell WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Image deleted successfully!');</script>";
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Fetch all images
$sql = "SELECT * FROM granna80_bdlinks.typofx_carousell";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">TypoFX Carousell Manage</h1>

        <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php';?>">[Back]</a>

        <!-- Button to open add image modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            Add New Image
        </button>

        <!-- Images table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>File Path</th>
                    <th>File Type</th>
                    <th>File Size</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cont = 1;

                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $cont; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><img src="/images/uploads-carrousel/<?php echo $row['file_path']; ?>" alt="" width="100"></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['file_path']; ?></td>
                        <td><?php echo $row['file_type']; ?></td>
                        <td><?php echo $row['file_size']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['disabled'] ? 'Disabled' : 'Enabled'; ?></td>
                        <td>
                            <!-- Button to open edit modal -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
                                Edit
                            </button>
                            <!-- Button to open delete modal -->
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description"><?php echo $row['description']; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="file" class="form-label">Change Image (optional)</label>
                                            <input type="file" class="form-control" name="file">
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" name="disabled" id="disabled<?php echo $row['id']; ?>" <?php echo $row['disabled'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="disabled<?php echo $row['id']; ?>">Disable Image</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this image?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    $cont++;
                endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name (optional)</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (optional)</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Image (optional)</label>
                            <input type="file" class="form-control" name="file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add" class="btn btn-primary">Add Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>