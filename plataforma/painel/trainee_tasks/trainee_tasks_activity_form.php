<?php
include __DIR__ . '/bootstrap.php';
include AUTH_FILE;

// Block non-admin/root users
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index");
    exit();
}

include DB_FILE;

$task_code = $_POST['task_code'] ?? $_GET['task_code'] ?? '';
$activity_code = $_POST['activity_code'] ?? $_GET['activity_code'] ?? '';
$is_edit = !empty($activity_code);

if (empty($task_code)) {
    echo "Task Code is required.";
    exit;
}

$success_message = '';
$error_message = '';
$errors = [];

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$allowed_statuses = ['open', 'done', 'test', 'video', 'prod'];

// Handle form submission (Only if token is present, meaning user pressed Save)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token'])) {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "Invalid CSRF token.";
    } else {
        $trainee_task_code = trim($_POST['trainee_task_code'] ?? '');
        $trainee_activity_code = trim($_POST['trainee_activity_code'] ?? '');
        $trainee_activity = trim($_POST['trainee_activity'] ?? '');
        $trainee_activity_details = trim($_POST['trainee_activity_details'] ?? '');
        $trainee_activity_status = trim($_POST['trainee_activity_status'] ?? '');

        // Verify readonly codes weren't tampered with
        if ($trainee_task_code !== $task_code) $errors[] = "Task Code mismatch.";
        if ($is_edit && $trainee_activity_code !== $activity_code) $errors[] = "Activity Code mismatch.";

        // Server-side validation
        if (empty($trainee_activity)) $errors[] = "Activity name is required.";
        if (mb_strlen($trainee_activity) > 40) $errors[] = "Activity name must be 40 characters or fewer.";
        if (empty($trainee_activity_details)) $errors[] = "Details are required.";
        if (mb_strlen($trainee_activity_details) > 85) $errors[] = "Details must be 85 characters or fewer.";
        if (!in_array($trainee_activity_status, $allowed_statuses, true)) $errors[] = "Invalid status.";

        // Regenerate CSRF token after validation
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        if (empty($errors)) {
            $trainee_activity_last_updated = date('Y-m-d');

            if ($is_edit) {
                $stmt = $conn->prepare("UPDATE granna80_bdlinks.trainee_activity SET trainee_activity = ?, trainee_activity_details = ?, trainee_activity_status = ?, trainee_activity_last_updated = ? WHERE trainee_task_code = ? AND trainee_activity_code = ?");
                $stmt->bind_param("ssssss", $trainee_activity, $trainee_activity_details, $trainee_activity_status, $trainee_activity_last_updated, $task_code, $activity_code);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Activity updated successfully.";
                    echo "<script>window.location.href = 'index';</script>";
                    exit();
                } else {
                    $error_message = "Error updating activity: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.trainee_activity (trainee_task_code, trainee_activity_code, trainee_activity, trainee_activity_details, trainee_activity_status, trainee_activity_last_updated) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $trainee_task_code, $trainee_activity_code, $trainee_activity, $trainee_activity_details, $trainee_activity_status, $trainee_activity_last_updated);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Activity added successfully to Task: " . htmlspecialchars($trainee_task_code);
                    echo "<script>window.location.href = 'index';</script>";
                    exit();
                } else {
                    $error_message = "Error adding activity: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $error_message = implode('<br>', $errors);
        }
    }
}

// Initial Data Fetch/Setup
if ($is_edit) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($errors)) {
        // Preserve submitted values on validation failure
    } else {
        $stmt = $conn->prepare("SELECT * FROM granna80_bdlinks.trainee_activity WHERE trainee_task_code = ? AND trainee_activity_code = ?");
        $stmt->bind_param("ss", $task_code, $activity_code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $trainee_activity = $row['trainee_activity'];
            $trainee_activity_details = $row['trainee_activity_details'];
            $trainee_activity_status = $row['trainee_activity_status'];
            $trainee_activity_code = $row['trainee_activity_code'];
        } else {
            echo "Activity not found.";
            exit;
        }
        $stmt->close();
    }
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($errors)) {
        // Preserve submitted values on validation failure
    } else {
        // Add mode: Calculate next activity code
        $stmt_max = $conn->prepare("SELECT MAX(CAST(trainee_activity_code AS UNSIGNED)) as max_code FROM granna80_bdlinks.trainee_activity WHERE trainee_task_code = ?");
        $stmt_max->bind_param("s", $task_code);
        $stmt_max->execute();
        $result_max = $stmt_max->get_result();
        $row_max = $result_max->fetch_assoc();
        $trainee_activity_code = str_pad(($row_max['max_code'] ?? 0) + 1, 3, '0', STR_PAD_LEFT);
        $stmt_max->close();

        $trainee_activity = '';
        $trainee_activity_details = '';
        $trainee_activity_status = 'open';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $is_edit ? 'Edit' : 'Add'; ?> Trainee Activity
    </title>
    <link href="https://www.typofx.ie/.scr/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.typofx.ie/.scr/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="trainee_tasks_styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('trainee_activity_status');
            const nameLabel = document.getElementById('activity_name_label');
            const nameInput = document.getElementById('trainee_activity');

            function updateLabel() {
                if (statusSelect.value === 'video') {
                    nameLabel.textContent = 'YouTube Link (ID):';
                    nameInput.placeholder = 'ex: VjY8_W8S00A';
                } else {
                    nameLabel.textContent = 'Activity Name:';
                    nameInput.placeholder = '';
                }
            }

            statusSelect.addEventListener('change', updateLabel);
            updateLabel();
        });
    </script>
</head>

<body>
    <div class="form-container">
        <h2>
            <?php echo $is_edit ? 'Edit Activity: ' . htmlspecialchars($activity_code) : 'Add Activity'; ?> (Task:
            <?php echo htmlspecialchars($task_code); ?>)
        </h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-3">
                <label for="trainee_task_code" class="form-label">Task Code:</label>
                <input type="text" class="form-control" name="trainee_task_code" id="trainee_task_code"
                    value="<?php echo htmlspecialchars($task_code); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="trainee_activity_code" class="form-label">Activity Code:</label>
                <input type="text" class="form-control" name="trainee_activity_code" id="trainee_activity_code"
                    value="<?php echo htmlspecialchars($trainee_activity_code); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="trainee_activity" id="activity_name_label" class="form-label">Activity Name:</label>
                <input type="text" class="form-control" name="trainee_activity" id="trainee_activity" maxlength="40"
                    value="<?php echo htmlspecialchars($trainee_activity); ?>" required>
            </div>

            <div class="mb-3">
                <label for="trainee_activity_details" class="form-label">Details:</label>
                <textarea class="form-control" name="trainee_activity_details" id="trainee_activity_details" rows="3"
                    maxlength="85" required><?php echo htmlspecialchars($trainee_activity_details); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="trainee_activity_status" class="form-label">Status:</label>
                <select class="form-select" name="trainee_activity_status" id="trainee_activity_status">
                    <option value="open" <?php echo $trainee_activity_status == 'open' ? 'selected' : ''; ?>>open
                    </option>
                    <option value="done" <?php echo $trainee_activity_status == 'done' ? 'selected' : ''; ?>>done
                    </option>
                    <option value="test" <?php echo $trainee_activity_status == 'test' ? 'selected' : ''; ?>>test
                    </option>
                    <option value="video" <?php echo $trainee_activity_status == 'video' ? 'selected' : ''; ?>>video
                    </option>
                    <option value="prod" <?php echo $trainee_activity_status == 'prod' ? 'selected' : ''; ?>>prod
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?php echo $is_edit ? 'Save Changes' : 'Add Activity'; ?>
                </button>
                <a href="index" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</body>

</html>