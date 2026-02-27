<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php

include 'conexao.php';

// Define o nível de usuário baseado na sessão
$userLevel = $_SESSION["user_level_panel"] ?? '';

// Configurações da página interna
$pageTitle = 'Trainee Tasks';
$showTopBar = true;
$canEdit = ($userLevel === 'admin' || $userLevel === 'root');

date_default_timezone_set('UTC');

$query = "SELECT *, 
    DATE_FORMAT(deployed, '%d/%m/%y') as deployed_formatted,
    DATE_FORMAT(last_updated, '%d/%m/%y') as last_updated_formatted 
    FROM granna80_bdlinks.trainee_tasks ORDER BY trainee_task_code DESC";
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

        $link = htmlspecialchars($row['trainee_tasks_link'] ?? '');
        $displayLink = $link ? "<a href='$link' target='_blank' title='Open Link'><i class='fa-solid fa-external-link-alt'></i></a>" : "";

        $github = htmlspecialchars($row['trainee_tasks_github'] ?? '');
        $displayGithub = $github ? "<a href='$github' target='_blank' title='Open Github'><i class='fa-brands fa-github'></i></a>" : "";

        $youtube = htmlspecialchars($row['trainee_tasks_youtube'] ?? '');
        $displayYoutube = $youtube ? "<a href='https://www.youtube.com/watch?v=$youtube' target='_blank' title='Open Youtube'><i class='fa-brands fa-youtube'></i></a>" : "";

        $actions = "<td><i class='fa-solid fa-plus expand-btn' data-task-code='{$trainee_task_code}'></i>";
        if ($canEdit) {
            $actions .= " <a href='trainee_tasks_form.php?id={$trainee_task_code}'><i class='fa-solid fa-pen-to-square'></i></a> 
                          <a href='trainee_tasks_delete.php?id={$trainee_task_code}' onclick='return confirm(\"Tem certeza?\")'><i style='color: red;' class='fa-solid fa-trash'></i></a>";
        }
        $actions .= "</td>";

        $htmlRows .= "<tr> 
            <td>{$cont}</td>
            <td>{$trainee_task_code}</td>
            <td>{$deployed}</td>
            <td>{$assignment}</td>
            <td>{$status}</td>
            <td>{$person}</td>
            <td>{$type}</td>
            <td>{$displayLink}</td>
            <td>{$displayGithub}</td>
            <td>{$displayYoutube}</td>
            <td>{$last_updated}</td>
            {$actions}
        </tr>";
        $cont++;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainee Tasks</title>
    <link rel="stylesheet" href="dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="trainee_tasks_styles.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php if ($showTopBar): ?>
        <a href="https://www.typofx.ie/plataforma/panel/">[Control Panel]</a>
        <a href="javascript:window.location.reload(true)">[Refresh]</a>
        <?php if ($canEdit): ?>
            <a href="trainee_tasks_form.php">[Add New Record]</a>
        <?php else: ?>
            <span style="color: gray; cursor: not-allowed; text-decoration: none;" title="Acesso Restrito">[Add New
                Record]</span>
        <?php endif; ?>
    <?php endif; ?>

    <h1><?php echo $pageTitle; ?></h1>
    <table id="traineeTasksTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Task</th>
                <th>Deployed</th>
                <th>Assignment</th>
                <th>Status</th>
                <th>Person</th>
                <th>Type</th>
                <th><!-- Link Assign --></th>
                <th><!-- Link Github --></th>
                <th><!-- Link Youtube --></th>
                <th>Updated</th>
                <th><!-- Actions --></th>
            </tr>
        </thead>
        <tbody>
            <?php echo $htmlRows; ?>
        </tbody>
    </table>

    <script src="jquery.min.js"></script>
    <script src="jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            // Permissão para uso no JS
            var canEdit = <?php echo $canEdit ? 'true' : 'false'; ?>;

            var table = $('#traineeTasksTable').DataTable({
                "order": [[ 1, "desc" ]],
                "lengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "pageLength": 50,
                "columns": [
                    null, // #
                    null, // Task Code
                    null, // Deployed
                    null, // Assignment
                    null, // Status
                    null, // Person
                    null, // Type
                    null, // Link Assign
                    null, // Link Github
                    null, // Link Youtube
                    null, // Updated
                    { "orderable": false } // Actions column is always visible
                ]
            });

            // Event delegation: Handle clicks on 'expand' buttons for rows
            // Fetches activity details via AJAX if not already loaded
            $('#traineeTasksTable tbody').on('click', '.expand-btn', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var icon = $(this);
                var taskCode = $(this).data('task-code');

                if (row.child.isShown()) {
                    // Close row
                    row.child.hide();
                    tr.removeClass('shown');
                    icon.removeClass('fa-minus').addClass('fa-plus');
                }
                else {
                    // Open row
                    if (row.child() && row.child().length) {
                        // Show already fetched data
                        row.child.show();
                        tr.addClass('shown');
                        icon.removeClass('fa-plus').addClass('fa-minus');
                    } else {
                        // Fetch data from API
                        $.ajax({
                            url: 'trainee_tasks_get_activities.php',
                            method: 'GET',
                            data: { task_code: taskCode },
                            dataType: 'json',
                            success: function (data) {
                                if (data.error) {
                                    alert('Erro: ' + data.error);
                                } else {
                                    row.child(format(data, taskCode)).show();
                                    tr.addClass('shown');
                                    icon.removeClass('fa-plus').addClass('fa-minus');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(xhr.responseText);
                                alert('Erro na requisição. Verifique o console para mais detalhes. ' + error);
                            }
                        });
                    }
                }
            });

            // Formats the expanded row logic
            // Uses generic API keys (code, name, status) masked from DB columns
            function format(d, taskCode) {
                var addUrl = 'trainee_tasks_activity_form.php?task_code=' + taskCode;

                if (!d || d.length === 0) {
                    var emptyHtml = '<div class="activity-empty-state">' +
                        '<p class="text-muted">Nenhuma atividade pendente encontrada.</p>';
                    if (canEdit) {
                        emptyHtml += '<a href="' + addUrl + '" class="btn btn-primary activity-add-btn-large">+ Add Activity</a>';
                    }
                    emptyHtml += '</div>';
                    return emptyHtml;
                }

                var html = '<table class="display activity-table">';
                html += '<thead><tr>' +
                    '<th>Code</th>' +
                    '<th>Activity</th>' +
                    '<th>Details</th>' +
                    '<th>Status</th>' +
                    '<th>Last Updated</th>' +
                    '<th></th>' +
                    '</tr></thead>';
                html += '<tbody>';

                d.forEach(function (activity) {
                    var editUrl = 'trainee_tasks_activity_form.php?task_code=' + activity.task_code + '&activity_code=' + activity.code;
                    var deleteUrl = 'trainee_tasks_activity_delete.php?task_code=' + activity.task_code + '&activity_code=' + activity.code;
                    var rowClass = (activity.status === 'done') ? 'class="activity-done"' : '';

                    var displayName = activity.name || '';
                    if (activity.status === 'video') {
                        displayName = '<a href="https://www.youtube.com/watch?v=' + (activity.name || '') + '" target="_blank" title="Open Youtube">' +
                            '<i class="fa-brands fa-youtube"></i></a>';
                    }

                    html += '<tr ' + rowClass + '>' +
                        '<td>' + (activity.code || '') + '</td>' +
                        '<td>' + displayName + '</td>' +
                        '<td>' + (activity.details || '') + '</td>' +
                        '<td>' + (activity.status || '') + '</td>' +
                        '<td>' + (activity.last_updated || '') + '</td>';

                    if (canEdit) {
                        html += '<td>' +
                            '<a href="' + editUrl + '" title="Edit"><i class="fa-solid fa-pen-to-square icon-edit"></i></a>' +
                            '<a href="' + deleteUrl + '" onclick="return confirm(\'Are you sure you want to delete this activity?\')" title="Delete"><i class="fa-solid fa-trash icon-delete"></i></a>' +
                            '</td>';
                    } else {
                        html += '<td></td>';
                    }
                    html += '</tr>';
                });

                html += '</tbody></table>';
                if (canEdit) {
                    html += '<div class="activity-actions">' +
                        '<a href="' + addUrl + '" class="btn btn-sm btn-primary">+ Add Activity</a>' +
                        '</div>';
                }
                return html;
            }
        });
    </script>
    <center>v 0.1.0 <!--23/02/2026)--></center>
</body>

</html>