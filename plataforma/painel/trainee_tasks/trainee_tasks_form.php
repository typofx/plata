<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php


// Bloqueio de acesso para não-root
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index.php");
    exit();
}

include 'conexao.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$is_edit = !empty($id);

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trainee_task_code = $_POST['trainee_task_code'];
    $deployed = $_POST['deployed'];
    $assignment = $_POST['assignment'];
    $status = $_POST['status'];
    $person = $_POST['person'];
    $type = $_POST['type'];
    $head = $_POST['head'];
    $trainee_tasks_link = $_POST['trainee_tasks_link'];
    $trainee_tasks_github = $_POST['trainee_tasks_github'];
    $trainee_tasks_youtube = $_POST['trainee_tasks_youtube'] ?? '';
    $last_updated = date("Y-m-d H:i:s");

    if ($is_edit) {
        $query = "UPDATE granna80_bdlinks.trainee_tasks SET
            trainee_task_code = ?,
            deployed = ?,
            assignment = ?,
            `status` = ?,
            person = ?,
            `type` = ?,
            head = ?,
            last_updated = ?,
            trainee_tasks_link = ?,
            trainee_tasks_github = ?,
            trainee_tasks_youtube = ?
            WHERE trainee_task_code = ?";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ssssssssssss", $trainee_task_code, $deployed, $assignment, $status, $person, $type, $head, $last_updated, $trainee_tasks_link, $trainee_tasks_github, $trainee_tasks_youtube, $id);
            if ($stmt->execute()) {
                $id = $trainee_task_code; // Update current ID in case it changed (though it's readonly in UI)
                $success_message = "Task updated successfully.";
                if (isset($_POST['action']) && $_POST['action'] === 'save_close') {
                    echo "<script>window.location.href = 'index.php';</script>";
                    exit();
                }
            } else {
                $error_message = "Error updating task: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $query = "INSERT INTO granna80_bdlinks.trainee_tasks (trainee_task_code, deployed, assignment, `status`, person, `type`, head, last_updated, trainee_tasks_link, trainee_tasks_github, trainee_tasks_youtube) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sssssssssss", $trainee_task_code, $deployed, $assignment, $status, $person, $type, $head, $last_updated, $trainee_tasks_link, $trainee_tasks_github, $trainee_tasks_youtube);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Record created successfully. Code: " . htmlspecialchars($trainee_task_code);
                echo "<script>window.location.href = 'index.php';</script>";
                exit();
            } else {
                $error_message = "Error creating task: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Initial Data Fetch/Setup
if ($is_edit) {
    $query_select = "SELECT * FROM granna80_bdlinks.trainee_tasks WHERE trainee_task_code = ?";
    if ($stmt = $conn->prepare($query_select)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $trainee_task_code = $row['trainee_task_code'];
            $deployed = $row['deployed'];
            $assignment = $row['assignment'];
            $status = $row['status'];
            $person = $row['person'];
            $type = $row['type'];
            $head = $row['head'];
            $last_updated_val = $row['last_updated'] ? date_format(date_create($row['last_updated']), "d/m/y H:i:s") : 'N/A';
            $trainee_tasks_link = $row['trainee_tasks_link'];
            $trainee_tasks_github = $row['trainee_tasks_github'];
            $trainee_tasks_youtube = $row['trainee_tasks_youtube'];
        } else {
            echo "Task not found.";
            exit;
        }
        $stmt->close();
    }
} else {
    // Add mode: Calculate next code
    $query_max = "SELECT MAX(CAST(trainee_task_code AS UNSIGNED)) as max_code FROM granna80_bdlinks.trainee_tasks";
    $result_max = $conn->query($query_max);
    $row_max = $result_max->fetch_assoc();
    $trainee_task_code = ($row_max['max_code'] ?? 1000) + 1;

    // Default values
    $deployed = date('Y-m-d');
    $assignment = '';
    $status = 'open';
    $person = '';
    $type = '';
    $head = '';
    $trainee_tasks_link = '';
    $trainee_tasks_github = '';
    $trainee_tasks_youtube = '';
    $last_updated_val = 'N/A';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $is_edit ? 'Edit' : 'Add'; ?> Trainee Task
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="trainee_tasks_styles.css">
</head>

<body>
    <div class="form-container">
        <h2>
            <?php echo $is_edit ? 'Edit Task: ' . htmlspecialchars($id) : 'Add New Trainee Task'; ?>
        </h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="trainee_task_code" class="form-label">Task Code:</label>
                <input type="text" class="form-control" name="trainee_task_code" id="trainee_task_code"
                    value="<?php echo htmlspecialchars($trainee_task_code); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="deployed" class="form-label">Deployed:</label>
                <input type="date" class="form-control" name="deployed" id="deployed"
                    value="<?php echo htmlspecialchars($deployed); ?>">
            </div>

            <div class="mb-3">
                <label for="assignment" class="form-label">Assignment:</label>
                <input type="text" class="form-control" name="assignment" id="assignment"
                    value="<?php echo htmlspecialchars($assignment); ?>">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" name="status" id="status">
                    <option value="open" <?php echo $status == 'open' ? 'selected' : ''; ?>>open</option>
                    <option value="done" <?php echo $status == 'done' ? 'selected' : ''; ?>>done</option>
                    <option value="test" <?php echo $status == 'test' ? 'selected' : ''; ?>>test</option>
                    <option value="video" <?php echo $status == 'video' ? 'selected' : ''; ?>>video</option>
                    <option value="prod" <?php echo $status == 'prod' ? 'selected' : ''; ?>>prod</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="person" class="form-label">Person:</label>
                <select class="form-select" name="person" id="person">
                    <option value="">-- select --</option>
                    <option value="Alex Scarano" <?php echo $person == 'Alex Scarano' ? 'selected' : ''; ?>>Alex Scarano
                    </option>
                    <option value="Paulo Alves" <?php echo $person == 'Paulo Alves' ? 'selected' : ''; ?>>Paulo Alves
                    </option>
                    <option value="Larissa Correia" <?php echo $person == 'Larissa Correia' ? 'selected' : ''; ?>>Larissa
                        Correia</option>
                    <option value="Ryann dos Santos" <?php echo $person == 'Ryann dos Santos' ? 'selected' : ''; ?>>Ryann
                        dos Santos</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type:</label>
                <select class="form-select" name="type" id="type">
                    <option value="">-- select --</option>
                    <option value="office" <?php echo $type == 'office' ? 'selected' : ''; ?>>office</option>
                    <option value="script" <?php echo $type == 'script' ? 'selected' : ''; ?>>script</option>
                    <option value="form" <?php echo $type == 'form' ? 'selected' : ''; ?>>form</option>
                    <option value="frontend" <?php echo $type == 'frontend' ? 'selected' : ''; ?>>frontend</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="head" class="form-label">Head:</label>
                <select class="form-select" name="head" id="head">
                    <option value="">-- select --</option>
                    <option value="Adam Soares" <?php echo $head == 'Adam Soares' ? 'selected' : ''; ?>>Adam Soares
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="trainee_tasks_link" class="form-label">Task Link:</label>
                <input type="url" class="form-control" name="trainee_tasks_link" id="trainee_tasks_link"
                    value="<?php echo htmlspecialchars($trainee_tasks_link); ?>" placeholder="https://example.com">
            </div>

            <div class="mb-3">
                <label for="trainee_tasks_github" class="form-label">Github Link:</label>
                <input type="url" class="form-control" name="trainee_tasks_github" id="trainee_tasks_github"
                    value="<?php echo htmlspecialchars($trainee_tasks_github); ?>" placeholder="https://github.com/...">
            </div>

            <div class="mb-3">
                <label for="trainee_tasks_youtube" class="form-label">YouTube Link (ID):</label>
                <input type="text" class="form-control" name="trainee_tasks_youtube" id="trainee_tasks_youtube"
                    value="<?php echo htmlspecialchars($trainee_tasks_youtube); ?>" placeholder="VjY8_W8S00A">
                <small class="text-muted">Paste only the video ID (e.g., VjY8_W8S00A)</small>
            </div>

            <?php if ($is_edit): ?>
                <div class="mb-3">
                    <label class="form-label">Last Updated:
                        <?php echo $last_updated_val; ?>
                    </label>
                </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" name="action" value="save" class="btn btn-primary">
                    <?php echo $is_edit ? 'Save' : 'Add New'; ?>
                </button>
                <?php if ($is_edit): ?>
                    <button type="submit" name="action" value="save_close" class="btn btn-success">Save and Close</button>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>