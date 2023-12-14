<?php
include 'conexao.php';

// ROAD MAP ENGLISH

$sqlEN = "SELECT * FROM granna80_bdlinks.roadmap_en WHERE YEAR(task_date) = 2022";
$resultEN = mysqli_query($conn, $sqlEN);

if ($resultEN) {
    // Start the existing HTML table
    echo '<table class="roadmap-table">';

    // Start the first row with "S2"
    echo '<tr>';
    echo '<td class="tdleft tdline1 cursor">';
    echo '<div class="centre">';
    echo '<h2>S2</h2><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
    echo '</div>';
    echo '</td>';

    // Start the second cell for records
    echo '<td class="tdline1 fontStyle cursor">';

    while ($rowEN = mysqli_fetch_assoc($resultEN)) {
        $task_goalEN = $rowEN["task_goal"];

        // Check if task_done is equal to 1
        if ($rowEN["task_done"] == 1) {
            $task_goalEN = '&#x2713; ' . $task_goalEN;
        }

        // Check if task_highlighted is equal to 1
        $task_highlighted = $rowEN["task_highlighted"];
        if ($task_highlighted == 1) {
            $task_goalEN = '<span>' . $task_goalEN . '</span>';
        }

        // Display each record within the second cell
        echo $task_goalEN . '<br>';
    }

    // Close the second cell and the first row
    echo '</td>';
    echo '</tr>';

    // Close the existing HTML table
    echo '</table>';
} else {
    echo "Erro na consulta ao banco de dados.";
}
