<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=UTF-8');

include __DIR__ . '/bootstrap.php';

try {
    $is_public = isset($_GET['is_public']) ? $_GET['is_public'] : '0';
    $userLevel = '';

    if ($is_public !== '1') {
        ob_start();
        include AUTH_FILE;
        ob_end_clean();

        $userLevel = $_SESSION["user_level_panel"] ?? '';
    }

    $canEdit = ($userLevel === 'admin' || $userLevel === 'root');

    include DB_FILE;

    $task_code = isset($_GET['task_code']) ? $_GET['task_code'] : '';

    if (empty($task_code)) {
        echo '<div class="alert alert-danger">Task code is required</div>';
        exit;
    }

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT 
                trainee_task_code AS task_code, 
                trainee_activity_code AS code, 
                trainee_activity AS name, 
                trainee_activity_details AS details, 
                trainee_activity_status AS status, 
                DATE_FORMAT(trainee_activity_last_updated, '%d/%m/%y') AS last_updated 
            FROM granna80_bdlinks.trainee_activity 
            WHERE trainee_task_code = ?
            ORDER BY trainee_activity_code DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $task_code);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    $stmt->close();
    $conn->close();

    // --- HTML GENERATION (PHP FIRST) ---

    if (empty($activities)) {
        echo '<div class="activity-empty-state">';
        echo '<p class="text-muted">No pending activities found.</p>';
        if ($canEdit) {
            echo '<form action="trainee_tasks_activity_form" method="POST" class="action-form">
                    <input type="hidden" name="task_code" value="' . htmlspecialchars($task_code) . '">
                    <button type="submit">+ Add Activity</button>
                  </form>';
        }
        echo '</div>';
        exit;
    }

    echo '<table class="display activity-table">';
    echo '<thead><tr>';
    echo '<th>Code</th>';
    echo '<th>Activity</th>';
    echo '<th>Details</th>';
    echo '<th>Status</th>';
    echo '<th>Last Updated</th>';
    echo '<th></th>';
    echo '</tr></thead>';
    echo '<tbody>';

    foreach ($activities as $activity) {
        $rowClass = ($activity['status'] === 'done') ? 'class="activity-done"' : '';
        
        $displayName = htmlspecialchars($activity['name'] ?? '');
        if ($activity['status'] === 'video') {
            $displayName = '<a href="https://www.youtube.com/watch?v=' . htmlspecialchars($activity['name'] ?? '') . '" target="_blank" title="Open Youtube">' .
                           '<i class="fa-brands fa-youtube"></i></a>';
        }

        echo '<tr ' . $rowClass . '>';
        echo '<td>' . htmlspecialchars($activity['code'] ?? '') . '</td>';
        echo '<td>' . $displayName . '</td>';
        echo '<td>' . htmlspecialchars($activity['details'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($activity['status'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($activity['last_updated'] ?? '') . '</td>';

        echo '<td>';
        if ($canEdit) {
            $token = $_SESSION['csrf_token'] ?? '';
            
            echo '<form action="trainee_tasks_activity_form" method="POST" class="action-form">
                    <input type="hidden" name="task_code" value="' . htmlspecialchars($activity['task_code']) . '">
                    <input type="hidden" name="activity_code" value="' . htmlspecialchars($activity['code']) . '">
                    <button type="submit" title="Edit"><i class="fa-solid fa-pen-to-square icon-edit"></i></button>
                  </form>';
            echo ' <form action="trainee_tasks_activity_delete" method="POST" class="action-form" onsubmit="return confirm(\'Are you sure you want to delete this activity?\')">
                    <input type="hidden" name="task_code" value="' . htmlspecialchars($activity['task_code']) . '">
                    <input type="hidden" name="activity_code" value="' . htmlspecialchars($activity['code']) . '">
                    <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                    <button type="submit" title="Delete"><i class="fa-solid fa-trash icon-delete"></i></button>
                  </form>';
        }
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';

    if ($canEdit) {
        echo '<div class="activity-actions">';
        echo '<form action="trainee_tasks_activity_form" method="POST" class="action-form">
                <input type="hidden" name="task_code" value="' . htmlspecialchars($task_code) . '">
                <button type="submit">+ Add Activity</button>
              </form>';
        echo '</div>';
    }

} catch (Exception $e) {
    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>