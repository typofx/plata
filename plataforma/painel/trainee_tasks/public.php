<?php

include __DIR__ . '/bootstrap.php';
include DB_FILE;
include __DIR__ . '/trainee_tasks_language_helper.php';

$pageTitle = 'Sirka';


date_default_timezone_set('UTC');

$query = "SELECT *, 
    DATE_FORMAT(deployed, '%d/%m/%y') as deployed_formatted,
    DATE_FORMAT(last_updated, '%d/%m/%y') as last_updated_formatted 
    FROM granna80_bdlinks.trainee_tasks 
    WHERE last_updated >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    ORDER BY trainee_task_code DESC";
$result = $conn->query($query);

$htmlRows = '';
$cont = 1;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trainee_task_code = htmlspecialchars($row['trainee_task_code'] ?? '');
        $deployed = $row['deployed_formatted'] ?: '00/00/00';

        $assignment = htmlspecialchars($row['assignment'] ?? '');
        $status = htmlspecialchars($row['status'] ?? '');
        $person = htmlspecialchars($row['person'] ?? '');
        $type = htmlspecialchars($row['type'] ?? '');
        $head = htmlspecialchars($row['head'] ?? '');

        $last_updated = $row['last_updated_formatted'] ?: '00/00/00';
        $tasks_hours = htmlspecialchars($row['tasks_hours'] ?? '');
        $tasks_programming_languages = htmlspecialchars($row['tasks_programming_languages'] ?? '');

        $link = htmlspecialchars($row['trainee_tasks_link'] ?? '');
        $displayLink = $link ? "<a href='$link' target='_blank' title='Open Link'><i class='fa-solid fa-external-link-alt'></i></a>" : "<i class='fa-solid fa-external-link-alt lang-inactive'></i>";

        $github = htmlspecialchars($row['trainee_tasks_github'] ?? '');
        $displayGithub = $github ? "<a href='$github' target='_blank' title='Open Github'><i class='fa-brands fa-github'></i></a>" : "<i class='fa-brands fa-github lang-inactive'></i>";

        $youtube = htmlspecialchars($row['trainee_tasks_youtube'] ?? '');
        $displayYoutube = $youtube ? "<a href='https://www.youtube.com/watch?v=$youtube' target='_blank' title='Open Youtube'><i class='fa-brands fa-youtube'></i></a>" : "<i class='fa-brands fa-youtube lang-inactive'></i>";

        $actions = "<td><i class='fa-solid fa-plus expand-btn' data-task-code='{$trainee_task_code}' title='Expand/Collapse Activities'></i></td>";

        $htmlRows .= "<tr> 
            <td>{$cont}</td>
            <td>{$trainee_task_code}</td>
            <td>{$deployed}</td>
            <td>{$assignment}</td>
            <td>{$status}</td>
            <td>{$person}</td>
            <td>{$type}</td>
            <td>
                <div class='links-wrapper'>
                    {$displayLink}
                    {$displayGithub}
                    {$displayYoutube}
                </div>
            </td>
            <td>" . renderLanguagesHtml($tasks_programming_languages) . "</td>
            <td>{$last_updated}</td>
            <td>{$tasks_hours}</td>
            {$actions}
        </tr>";
        $cont++;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirka</title>

    <link rel="stylesheet" href="https://www.typofx.ie/.scr/dataTables.min.css">
    <link rel="stylesheet" href="https://www.typofx.ie/.scr/all.min.css">
    <link rel="stylesheet" href="trainee_tasks_styles.css?v=<?php echo time(); ?>">
    <script src="https://www.typofx.ie/.scr/jquery.min.js"></script>
    <script src="https://www.typofx.ie/.scr/jquery.dataTables.min.js"></script>
    <script src="trainee_tasks.js?v=<?php echo time(); ?>"></script>
</head>

<body>

    <h1><?php echo $pageTitle; ?></h1>
    <table id="traineeTasksTable" class="display" style="width:100%" data-public="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Task</th>
                <th>Deployed</th>
                <th>Assignment</th>
                <th>Status</th>
                <th>Person</th>
                <th>Type</th>
                <th>Data Links</th>
                <th>Language</th>
                <th>Updated</th>
                <th>HRS</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $htmlRows; ?>
        </tbody>
    </table>
    <center>v 0.1.0 <!--23/02/2026)--></center>
</body>

</html>