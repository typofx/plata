<?php  include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php


// Include the file for database connection
include 'conexao.php';

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select data from the English table
$sql_en = "SELECT * FROM granna80_bdlinks.plata_texts WHERE language = 'en' AND device = 'desktop'";
$result_en = $conn->query($sql_en);

// Query to select data from the Spanish table
$sql_es = "SELECT * FROM granna80_bdlinks.plata_texts WHERE language = 'es' AND device = 'desktop'";
$result_es = $conn->query($sql_es);

// Query to select data from the Portuguese table
$sql_pt = "SELECT * FROM granna80_bdlinks.plata_texts WHERE language = 'pt' AND device = 'desktop'";
$result_pt = $conn->query($sql_pt);


?>

<!DOCTYPE html>
<html>

<head>
    <title>plata_texts </title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2>plata_texts </h2>

    <a href="add.php">[ Add new ]</a>
    <br>
    <br>

    <table>
        <tr>
            <th>ID</th>
            <th>TEXT NAME</th>
            <th>ENGLISH TEXT</th>
        
            <th>ESPANISH TEXT</th>
   
            <th>PORTUGUESE TEXT</th>
           

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
      

            // If there are no more results in any of the tables, end the loop
            if (!$row_en && !$row_es && !$row_pt) {
                break;
            }

            echo "<tr>";
            echo "<td>" . $index . "
      </td>";

      echo "<td><b>" . $row_en["name"] . "</b></td>";


            // Check and display task goal in English
            echo "<td>";
            if ($row_en) {
                echo $row_en["text"];
            }
            echo "</td>";

     


            // Check and display task goal in Spanish
            echo "<td>";
            if ($row_es) {
                echo $row_es["text"];
            }
            echo "</td>";

      

            // Check and display task goal in Portuguese
            echo "<td>";
            if ($row_pt) {
                echo $row_pt["text"];
            }
            echo "</td>";

      






            // Add an edit button for each row
            echo "<td><a href='edit.php?id_en=" . ($row_en ? $row_en["id"] : "0") . "&id_es=" . ($row_es ? $row_es["id"] : "0") . "&id_pt=" . ($row_pt ? $row_pt["id"] : "0") . "'>Edit</a> | <a href='delete.php?id_en=" . ($row_en ? $row_en["id"] : "0") . "&id_es=" . ($row_es ? $row_es["id"] : "0") . "&id_pt=" . ($row_pt ? $row_pt["id"] : "0") . "'>Delete</a></td>";


            echo "</tr>";

            // Increment index counter
            $index++;
        }
        ?>
    </table>

</body>

</html>