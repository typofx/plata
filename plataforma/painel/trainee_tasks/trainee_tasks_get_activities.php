<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

try {
    $is_public = isset($_GET['is_public']) ? $_GET['is_public'] : '0';

    if ($is_public !== '1') {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php')) {
            throw new Exception("File is_logged.php not found at: " . $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php');
        }
        ob_start();
        include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
        ob_end_clean();
    }

    if (!file_exists('conexao.php')) {
        throw new Exception("File conexao.php not found.");
    }
    include 'conexao.php';

    $task_code = isset($_GET['task_code']) ? $_GET['task_code'] : '';

    if (empty($task_code)) {
        echo json_encode(['error' => 'Task code is required']);
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

    echo json_encode($activities);

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>