<?php
include 'conexao.php';

// ROAD MAP ENGLISH

$sqlEN = "SELECT *
         FROM granna80_bdlinks.roadmap_en
         WHERE YEAR(task_date) = 2023
         ORDER BY task_done DESC, task_id DESC";

$resultEN = mysqli_query($conn, $sqlEN);

if ($resultEN) {
    // Start the HTML table
    echo '<table class="roadmap-table">';
    
    // Arrays for tasks in Q1 and Q2
    $q1_tasks = array();
    $q2_tasks = array();

    while ($rowEN = mysqli_fetch_assoc($resultEN)) {
        $task_goalEN = $rowEN["task_goal"];
        
        // Check if task_done is equal to 1
        if ($rowEN["task_done"] == 1) {
            $task_goalEN = '✓ ' . $task_goalEN;
        }

        // Check if task_highlighted is equal to 1 and add a CSS class
        $task_highlighted = $rowEN["task_highlighted"];
        if ($task_highlighted == 1) {
            $task_goalEN = '<span class="highlighted-text">' . $task_goalEN . '</span>';
        }

        // Check if semester is equal to 1
        $semester = $rowEN["semester"];
        if ($semester == 1) {
            // Add to Q2
            $q2_tasks[] = $task_goalEN;
        } else {
            // Add to Q1
            $q1_tasks[] = $task_goalEN;
        }
    }

    // Populate the Q1 section
    echo '<tr>';
    echo '<td class="tdleft tdline1"><div class="centre cursor"><h2>S1</h2><br><br><br><br><br><br></div></td>';
    echo '<td class="tdline1 fontStyle"><br>';
    foreach ($q1_tasks as $q1_task) {
        echo $q1_task . '<br>';
    }
    echo '<br>';
    echo '</td>';
    echo '</tr>';

    // Populate the Q2 section
    echo '<tr class="noooo">';
    echo '<td class="tdleft tdline2 cursor"><div class="centre"><h2>S2</h2><br><br><br><br><br><br></div></td>';
    echo '<td class="tdline2 fontStyle"><br>';
    foreach ($q2_tasks as $q2_task) {
        echo $q2_task . '<br>';
    }
    echo '<br>';
    echo '</td>';
    echo '</tr>';

    // Close the HTML table
    echo '</table>';
} else {
    echo "database error";
}
?>
