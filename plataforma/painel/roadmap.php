<style>

/*table, th, td {
    border: 1px solid black;
    color: black;
    }*/

.img-line {
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 47px;
}

.roadmap-main-text {
    border-collapse: collapse;
    width: 100%;
    height: 80px; /* width="70%" height="80px"*/
    margin-left: auto;
    margin-right: auto;
}
    
.roadmap-main-text .roadmap-header{
    background-color: #4C00B0;
    color: white;
}
    
.roadmap-main-text .roadmap-icon{
    background-color: #8A00C2;
    width: 80px;
}
    
.roadmap-table {
    border-collapse: collapse;
    width: calc(100% - 60px);
    margin-left: 30px;
    margin-top: 30px;
    margin-bottom: 0px;
    text-align: left;
}
    
.roadmap-table h3 {
    text-align: left;
}

.roadmap-table .tdleft {
    text-align: center;
    width: 50px;
}

.roadmap-table .tdline1 {
    background-color: #9966CC;
    color: white;
}

.roadmap-table .tdline2 {
    background-color: #8A00C2;
    color: white;
}

.div-roadmap {
    background-color: #1C0024;
    padding-bottom: 30px;
}

.highlighted-text {
    color: yellow;
    /* You can add more CSS styles as needed */
} 
</style>
<style>
    /* Style to hide text */
    .hidden-text {
        overflow: hidden;
        position: relative;
    }

    /* Style for the button */
    .show-button {
        cursor: pointer;
        color: blue;
    }

    .show-button {
        text-align: center; 
        color: white; 
        cursor: pointer; 
        padding: 10px 20px; 
        border-radius: 5px; 
    }

    .no-wrap {
        white-space: nowrap;
    }
</style>

<section id="AnchorRoadmapMobile">
<div class="div-roadmap">
    
<table class="roadmap-main-text">
    <tr>
        <td class="roadmap-header"><h2><?php echo $txtRoadmap ?></h2></td>
        <td class="roadmap-icon"><img class="img-line" src="https://www.plata.ie/images/plata-lines-logo.svg" alt=""></td>
    </tr>
    <td>
</table>

<?php

include 'connection.php';

$language = substr($_SERVER['REQUEST_URI'], 1, 2);

//granna80_bdlinks.roadmap_en

$tableSuffix = ($language == "pt") ? "_pt_mobile" : (($language == "es") ? "_es_mobile" : "_en_mobile");

// Construct the SQL query
$sqlEN = "SELECT *
        FROM granna80_bdlinks.roadmap" . $tableSuffix . "
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
        } else {
            $task_goalEN = ' &ndash; ' . $task_goalEN;
        }

        // Check if task_highlighted is equal to 1 and add a CSS class
        $task_highlighted = $rowEN["task_highlighted"];
        if ($task_highlighted == 1) {
            $task_goalEN = '<span class="highlighted-text hidden-text no-wrap" id="myText" data-full-text="' . $task_goalEN . '"><l>' . $task_goalEN . '</l></span>';
        } else {
            $task_goalEN = '<span class="hidden-text no-wrap" id="myText" data-full-text="' . $task_goalEN . '"><l>' . $task_goalEN . ' </l></span>';
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
    echo '<td class="tdleft tdline1"><div class="centre cursor"><h2>Q1</h2></div></td>';
    echo '<td class="tdline1 fontStyle"><br>';
    foreach ($q1_tasks as $q1_task) {
        echo $q1_task . '<br>';
    }
    echo '<br>';
    echo '</td>';
    echo '</tr>';

    // Populate the Q2 section
    echo '<tr class="noooo">';
    echo '<td class="tdleft tdline2 cursor"><div class="centre"><h2>Q2</h2></div></td>';
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
    echo "Database query error.";
}
?>
 <!-- Button to show/hide the text 
 <div class="show-button" onclick="showHideText()">Show more</div>-->

  <!--<script>
  document.addEventListener('DOMContentLoaded', function() {
    showHideText(true); // Call the function when the page loads
  });

  function showHideText(load) {
    var spans = document.querySelectorAll('.hidden-text');
    var showButton = document.querySelector('.show-button');

    spans.forEach(function(span) {
      var paragraph = span.querySelector('l');
      var displayedWords = 5.5; // Desired number of words
      var words = paragraph.textContent.split(/\s+/).filter(word => word !== '-' && word.toLowerCase() !== 'de' && word.toLowerCase() !== 'la');

      if (load) {
        var summaryText = words.slice(0, displayedWords).join(' ');
        paragraph.innerHTML = summaryText;
        showButton.innerHTML = 'Show more';
        if (!span.classList.contains('no-wrap')) {
          span.classList.add('no-wrap'); 
        }
      } else {
        if (words.length > displayedWords) {
          var summaryText = words.slice(0, displayedWords).join(' ');
          paragraph.innerHTML = summaryText;
          showButton.innerHTML = 'Show more';
          span.classList.add('no-wrap'); 
        } else {
          paragraph.innerHTML = span.getAttribute('data-full-text');
          showButton.innerHTML = 'Show less';
          span.classList.remove('no-wrap'); 
        }
      }
    });
  }

  document.querySelectorAll('.hidden-text').forEach(function(span) {
    span.setAttribute('data-full-text', span.textContent);
  });
</script>
-->

</div>
</section>
