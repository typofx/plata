<?php
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

// Include the file for database connection
include 'conexao.php';

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select data from the English table
$sql_en = "SELECT * FROM granna80_bdlinks.roadmap_en";
$result_en = $conn->query($sql_en);

// Query to select data from the Spanish table
$sql_es = "SELECT * FROM granna80_bdlinks.roadmap_es";
$result_es = $conn->query($sql_es);

// Query to select data from the Portuguese table
$sql_pt = "SELECT * FROM granna80_bdlinks.roadmap_pt";
$result_pt = $conn->query($sql_pt);

// Query to select data from the English table for mobile devices
$sql_en_mobile = "SELECT * FROM granna80_bdlinks.roadmap_en_mobile";
$result_en_mobile = $conn->query($sql_en_mobile);

// Query to select data from the Spanish table for mobile devices
$sql_es_mobile = "SELECT * FROM granna80_bdlinks.roadmap_es_mobile";
$result_es_mobile = $conn->query($sql_es_mobile);

// Query to select data from the Portuguese table for mobile devices
$sql_pt_mobile = "SELECT * FROM granna80_bdlinks.roadmap_pt_mobile";
$result_pt_mobile = $conn->query($sql_pt_mobile);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Roadmap </title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .highlighted {
            background-color: yellow;
            display: inline;
            padding: 0;
        }
    </style>
</head>
<body>

<h2>Roadmap </h2>

<a href="add.php">Add new</a>

<table>
  <tr>
    <th>ID</th>
    <th>Date</th>
    <th>Done</th>
    <th>Goal ENGLISH</th>
    <th>Goal ENGLISH MOBILE</th>
    <th>Goal ESPANISH</th>
    <th>Goal ESPANISH MOBILE</th>
    <th>Goal PORTUGUESE</th>
    <th>Goal PORTUGUESE MOBILE</th>
    <th>Semester</th>
    <th>Actions</th> <!-- New column for edit buttons -->
  </tr>
  <?php
  // Initialize index counters
  $index = 1;

  // Loop until there are results in at least one of the tables
  while (true) {
      $row_en = $result_en->fetch_assoc();
      $row_es = $result_es->fetch_assoc();
      $row_pt = $result_pt->fetch_assoc();
      $row_en_mobile = $result_en_mobile->fetch_assoc();
      $row_es_mobile = $result_es_mobile->fetch_assoc();
      $row_pt_mobile = $result_pt_mobile->fetch_assoc();

      // If there are no more results in any of the tables, end the loop
      if (!$row_en && !$row_es && !$row_pt && !$row_en_mobile && !$row_es_mobile && !$row_pt_mobile) {
          break;
      }

      echo "<tr>";
      echo "<td>".$index."
      </td>";

      // Check and display task date in English
      echo "<td>";
      if ($row_en) {
          echo date('d/m/Y', strtotime($row_en["task_date"]));
      }
      echo "</td>";

         // Check and display if task is done
         echo "<td>";
         if ($row_en && $row_en["task_done"] == 1) {
             echo "&#x2713;";
         } else if ($row_en) {
             echo "No";
         }
         echo "</td>";

      // Check and display task goal in English
      echo "<td>";
      if ($row_en && $row_en["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_en["task_goal"]."</span>";
      } else if ($row_en) {
          echo $row_en["task_goal"];
      }
      echo "</td>";

      // Check and display task goal in English for mobile devices
      echo "<td>";
      if ($row_en_mobile && $row_en_mobile["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_en_mobile["task_goal"]."</span>";
      } else if ($row_en_mobile) {
          echo $row_en_mobile["task_goal"];
      }
      echo "</td>";

      // Check and display task goal in Spanish
      echo "<td>";
      if ($row_es && $row_es["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_es["task_goal"]."</span>";
      } else if ($row_es) {
          echo $row_es["task_goal"];
      }
      echo "</td>";

      // Check and display task goal in Spanish for mobile devices
      echo "<td>";
      if ($row_es_mobile && $row_es_mobile["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_es_mobile["task_goal"]."</span>";
      } else if ($row_es_mobile) {
          echo $row_es_mobile["task_goal"];
      }
      echo "</td>";

      // Check and display task goal in Portuguese
      echo "<td>";
      if ($row_pt && $row_pt["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_pt["task_goal"]."</span>";
      } else if ($row_pt) {
          echo $row_pt["task_goal"];
      }
      echo "</td>";

      // Check and display task goal in Portuguese for mobile devices
      echo "<td>";
      if ($row_pt_mobile && $row_pt_mobile["task_highlighted"] == 1) {
          echo "<span class='highlighted'>".$row_pt_mobile["task_goal"]."</span>";
      } else if ($row_pt_mobile) {
          echo $row_pt_mobile["task_goal"];
      }
      echo "</td>";
      
   
      
      // Display the semester, fetching from any of the tables
      echo "<td>";
      if ($row_en) {
          echo $row_en["semester"];
      } else if ($row_es) {
          echo $row_es["semester"];
      } else if ($row_pt) {
          echo $row_pt["semester"];
      }
      echo "</td>";

      // Add an edit button for each row
      echo "<td><a href='editar.php?id_en=" . ($row_en ? $row_en["task_id"] : "") . "&id_es=" . ($row_es ? $row_es["task_id"] : "") . "&id_pt=" . ($row_pt ? $row_pt["task_id"] : "") . "&id_en_mobile=" . ($row_en_mobile ? $row_en_mobile["task_id"] : "") . "&id_es_mobile=" . ($row_es_mobile ? $row_es_mobile["task_id"] : "") . "&id_pt_mobile=" . ($row_pt_mobile ? $row_pt_mobile["task_id"] : "") . "'>Edit</a> | <a href='delete.php?id_en=" . ($row_en ? $row_en["task_id"] : "") . "&id_es=" . ($row_es ? $row_es["task_id"] : "") . "&id_pt=" . ($row_pt ? $row_pt["task_id"] : "") . "&id_en_mobile=" . ($row_en_mobile ? $row_en_mobile["task_id"] : "") . "&id_es_mobile=" . ($row_es_mobile ? $row_es_mobile["task_id"] : "") . "&id_pt_mobile=" . ($row_pt_mobile ? $row_pt_mobile["task_id"] : "") . "'>Delete</a></td>";

      echo "</tr>";

      // Increment index counter
      $index++;
  }
  ?>
</table>

</body>
</html>
