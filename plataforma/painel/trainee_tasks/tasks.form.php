<? include $_SERVER['DOCUMENT_ROOT']. '/plataforma/panel/is_logged.php'?>
<? include $_SERVER['DOCUMENT_ROOT']. '/.scr/conexao.php'?>
<?php
// Dynamic module loader: attempts to auto-detect module from directory, falls back to 'tasks' for stability.
$folder = file_exists(__DIR__ . '/' . basename(__DIR__) . '.language.helper.php') ? basename(__DIR__) : 'tasks';
include __DIR__ . '/' . $folder . '.language.helper.php';

// Block non-admin/root users
if (!in_array($_SESSION["user_level_panel"] ?? 'public', ['admin', 'root'])) {
    header("Location: index");
    exit();
}

$id = $_POST['id'] ?? $_GET['id'] ?? '';
$is_edit = !empty($id);

$success_message = '';
$error_message = '';
$errors = [];

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Load available languages from JSON dynamically
$langs_json_path = __DIR__ . '/' . $folder . '.programming.languages.json';
$available_langs = [];
if (file_exists($langs_json_path)) {
    $json_data = json_decode(file_get_contents($langs_json_path), true);
    $available_langs = $json_data['programming_languages'] ?? [];
}

$allowed_statuses = ['open', 'done', 'test', 'video', 'prod'];
$allowed_persons = ['', 'Alex Scarano', 'Paulo Alves', 'Larissa Correia', 'Ryann dos Santos', 'Murilo Furtado'];
$allowed_types = ['', 'office', 'script', 'form', 'frontend'];
$allowed_heads = ['', 'Adam Soares'];

$tasks_hours = 0;
$tasks_programming_languages = '';

// Handle form submission (Only if token is present, meaning user pressed Save)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token'])) {
    // CSRF validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "Invalid CSRF token.";
    } else {
        $trainee_task_code = trim($_POST['trainee_task_code'] ?? '');
        $deployed = trim($_POST['deployed'] ?? '');
        $assignment = trim($_POST['assignment'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $person = trim($_POST['person'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $head = trim($_POST['head'] ?? '');
        $trainee_tasks_link = trim($_POST['trainee_tasks_link'] ?? '');
        $trainee_tasks_github = trim($_POST['trainee_tasks_github'] ?? '');
        $trainee_tasks_youtube = trim($_POST['trainee_tasks_youtube'] ?? '');
        $tasks_hours = intval($_POST['tasks_hours'] ?? 0);

        // Construct binary string from programming language checkboxes
        $submitted_langs = $_POST['lang_checks'] ?? [];
        $bin_str = "";
        foreach ($available_langs as $langName) {
            $bin_str .= in_array($langName, $submitted_langs) ? "1" : "0";
        }
        $tasks_programming_languages = $bin_str;

        // Verify readonly task code wasn't tampered with
        if ($is_edit && $trainee_task_code !== $id) $errors[] = "Task Code mismatch.";

        // Server-side validation
        if (empty($trainee_task_code)) $errors[] = "Task Code is required.";
        if (empty($deployed) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $deployed)) $errors[] = "Valid Deployed date is required.";
        if (empty($assignment)) $errors[] = "Assignment is required.";
        if (!in_array($status, $allowed_statuses, true)) $errors[] = "Invalid status.";
        if (!in_array($person, $allowed_persons, true)) $errors[] = "Invalid person.";
        if (!in_array($type, $allowed_types, true)) $errors[] = "Invalid type.";
        if (!in_array($head, $allowed_heads, true)) $errors[] = "Invalid head.";
        if (!empty($trainee_tasks_link) && !filter_var($trainee_tasks_link, FILTER_VALIDATE_URL)) $errors[] = "Invalid Task Link URL.";
        if (!empty($trainee_tasks_github) && !filter_var($trainee_tasks_github, FILTER_VALIDATE_URL)) $errors[] = "Invalid Github Link URL.";

        // Regenerate CSRF token after validation
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        if (empty($errors)) {
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
                    trainee_tasks_youtube = ?,
                    tasks_hours = ?,
                    tasks_programming_languages = ?
                    WHERE trainee_task_code = ?";

                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("sssssssssssiss", $id, $deployed, $assignment, $status, $person, $type, $head, $last_updated, $trainee_tasks_link, $trainee_tasks_github, $trainee_tasks_youtube, $tasks_hours, $tasks_programming_languages, $id);
                    if ($stmt->execute()) {
                        $id = $trainee_task_code;
                        $success_message = "Task updated successfully.";
                        if (isset($_POST['action']) && $_POST['action'] === 'save_close') {
                            echo "<script>window.location.href = 'index';</script>";
                            exit();
                        }
                    } else {
                        error_log("Tasks: Error updating task: " . $stmt->error);
                        $error_message = "An error occurred. Please try again.";
                    }
                    $stmt->close();
                }
            } else {
                // Recalculate task code server-side (never trust client for auto-generated PK)
                $max_sql = "SELECT MAX(CAST(trainee_task_code AS UNSIGNED)) as max_code FROM granna80_bdlinks.trainee_tasks";
                $max_result = $conn->query($max_sql);
                $max_row = $max_result->fetch_assoc();
                $trainee_task_code = ($max_row['max_code'] ?? 1000) + 1;

                $query = "INSERT INTO granna80_bdlinks.trainee_tasks (trainee_task_code, deployed, assignment, `status`, person, `type`, head, last_updated, trainee_tasks_link, trainee_tasks_github, trainee_tasks_youtube, tasks_hours, tasks_programming_languages) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("sssssssssssis", $trainee_task_code, $deployed, $assignment, $status, $person, $type, $head, $last_updated, $trainee_tasks_link, $trainee_tasks_github, $trainee_tasks_youtube, $tasks_hours, $tasks_programming_languages);
                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Record created successfully. Code: " . htmlspecialchars($trainee_task_code);
                        echo "<script>window.location.href = 'index';</script>";
                        exit();
                    } else {
                        error_log("Tasks: Error creating task: " . $stmt->error);
                        $error_message = "An error occurred. Please try again.";
                    }
                    $stmt->close();
                }
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
        $last_updated_val = 'N/A';
    } else {
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
                $tasks_hours = $row['tasks_hours'];
                $tasks_programming_languages = $row['tasks_programming_languages'];
            } else {
                echo "Task not found.";
                exit;
            }
            $stmt->close();
        }
    }
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($errors)) {
        // Preserve submitted values on validation failure
        $last_updated_val = 'N/A';
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
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $is_edit ? 'Edit' : 'Add'; ?> Trainee Task
    </title>
    <link rel="stylesheet" href="<?php echo $folder; ?>.styles.css">
    <script>
    function toggleSection(id, headerElement) {
        const content = document.getElementById(id);
        if (!content) return;
        const isShown = content.classList.contains('show');
        if (isShown) {
            content.classList.remove('show');
            headerElement.classList.add('collapsed');
        } else {
            content.classList.add('show');
            headerElement.classList.remove('collapsed');
        }
    }
    </script>
</head>

<body>
    <div class="form-container">
        <h2>
            <?php echo $is_edit ? 'Edit Task: ' . htmlspecialchars($id) : 'Add New Trainee Task'; ?>
        </h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

            <!-- SECTION 1: Information -->
            <div class="mb-3 collapsible-header" onclick="toggleSection('info-section', this)">
                <h5 class="p-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <strong>Information</strong>
                    <span class="small text-muted collapse-icon">▼</span>
                </h5>
            </div>
            <div id="info-section" class="collapse show mb-4">

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
                    <option value="Murilo Furtado" <?php echo $person == 'Murilo Furtado' ? 'selected' : ''; ?>>Murilo Furtado</option>
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

            </div> <!-- End Information Section -->

            <!-- SECTION 2: Languages & Hours -->
            <div class="mb-3 collapsible-header" onclick="toggleSection('lang-hours-section', this)">
                <h5 class="p-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <strong>Languages & Hours</strong>
                    <span class="small text-muted collapse-icon">▼</span>
                </h5>
            </div>
            <div id="lang-hours-section" class="collapse show mb-4">
                <!-- New Field: Hours -->
                <div class="mb-3">
                    <label for="tasks_hours" class="form-label">Hours (Hrs):</label>
                    <input type="number" class="form-control" name="tasks_hours" id="tasks_hours" 
                        value="<?php echo htmlspecialchars($tasks_hours ?? 0); ?>" min="0" max="32767">
                </div>

                <!-- New Field: Languages (Checkboxes) -->
                <div class="mb-3">
                    <label class="form-label d-block">Programming Languages:</label>
                    <div class="d-flex flex-wrap gap-3 p-2 border rounded bg-white">
                        <?php if (!empty($available_langs)): ?>
                            <?php foreach ($available_langs as $idx => $langName): ?>
                                <?php 
                                    // Safety check length
                                    $is_checked = '';
                                    if (strlen($tasks_programming_languages) > $idx) {
                                        $is_checked = ($tasks_programming_languages[$idx] === '1') ? 'checked' : '';
                                    }
                                ?>
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" type="checkbox" name="lang_checks[]" 
                                        id="lang_<?php echo $idx; ?>" value="<?php echo htmlspecialchars($langName); ?>" 
                                        <?php echo $is_checked; ?>>
                                    <label class="form-check-label text-capitalize" for="lang_<?php echo $idx; ?>">
                                        <?php echo htmlspecialchars($langName); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <small class="text-muted">No configuration found in tasks.programming.languages.json</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div> <!-- End Languages & Hours Section -->

            <!-- SECTION 3: Links -->
            <div class="mb-3 collapsible-header" onclick="toggleSection('links-section', this)">
                <h5 class="p-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <strong>Links</strong>
                    <span class="small text-muted collapse-icon">▼</span>
                </h5>
            </div>
            <div id="links-section" class="collapse show mb-4">
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
            </div> <!-- End Links Section -->

            <?php if ($is_edit): ?>
                <div class="mb-3">
                    <label class="form-label">Last Updated:
                        <?php echo htmlspecialchars($last_updated_val); ?>
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
                <a href="index" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</body>

</html>
