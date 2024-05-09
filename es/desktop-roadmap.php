<?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php'; ?>

<link rel="stylesheet" href="https://www.plata.ie/en/desktop-roadmap-style.css" media="screen">


<style>
    @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap");
    /* need to be at index */

    body {
        font-family: "Montserrat", sans-serif;
        margin: 0;
        padding: 0;
        text-align: left;
    }

    /*
table, th, td {
    border: 1px solid black;
    color: black;
    }*/

    .img-line {
        display: block;
        margin-left: auto;
        margin-right: auto;
        height: 65px;
    }

    .roadmap-main-text {
        border-collapse: collapse;
        width: 100%;
    }

    .roadmap-main-text .roadmap-header {
        background-color: #4C00B0;
        margin: auto;
        color: white;
        /* padding-top:15px;*/
        /*border: 1px solid red;*/
        vertical-align: middle;
    }

    .roadmap-main-text .roadmap-icon {
        background-color: #8A00C2;
        width: 90px;
        height: 90px;
        /*border: 1px solid red;*/
        vertical-align: middle;
    }

    .roadmap-table {
        border-collapse: collapse;
        width: calc(100% - 120px);
        margin-left: 60px;
        margin-top: 60px;
        margin-bottom: 30px;
        text-align: left;
    }

    .roadmap-table .tdleft {
        margin: auto;
        text-align: center;
        width: 100px;
        text-align: center;
        margin-left: auto;
        margin-right: auto;

    }

    .fontStyle {
        font-family: 'Montserrat', sans-serif;
        font-size: 20px;
        color: white;
    }

    .roadmap-table .tdline1 {
        background-color: #9966CC;
    }

    .roadmap-table h2 {
        font-size: 25px;
        margin: auto;
        text-align: center;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .roadmap-table .tdline2 {
        background-color: #8A00C2;
    }

    .div-roadmap {
        background-color: #1C0024;
        padding-bottom: 30px;
    }

    .centre {
        display: flex;
        justify-content: center;
        align-items: center;
        vertical-align: middle;
    }

    .a-link {
        color: yellow;
    }

    .cursor {
        cursor: default;
    }

    .highlighted-text {
        color: yellow;
        /* You can add more CSS styles as needed */
    }
</style>




<section id="AnchorRoadmap">




    <div class="div-roadmap">

        <table class="roadmap-main-text">
            <tr>
                <td class="roadmap-header cursor">
                    <h2><?php echo $txtRoadmap ?></h2>
                </td>
                <td class="roadmap-icon"><img class="img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
            </tr>
        </table>

        <?php $CurrentPageURL = substr($_SERVER['REQUEST_URI'], 1, 2);


        $tableSuffix = ($CurrentPageURL == "pt") ? "_pt" : (($CurrentPageURL == "es") ? "_es" : "_en");
        include 'conexao.php';
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
                    $task_goalEN = ' &ndash; ' . $task_goalEN;
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
            echo '<td class="tdleft tdline1"><div class="centre cursor"><h2>S1</h2></div></td>';
            echo '<td class="tdline1 fontStyle"><br>';
            foreach ($q1_tasks as $q1_task) {
                echo $q1_task . '<br>';
            }
            echo '<br>';
            echo '</td>';
            echo '</tr>';

            // Populate the Q2 section
            echo '<tr class="noooo">';
            echo '<td class="tdleft tdline2 cursor"><div class="centre"><h2>S2</h2></div></td>';
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
            echo "Erro na consulta ao banco de dados.";
        }

        ?>



    </div>
    <div class="div-roadmap">
        <table class="roadmap-main-text">
            <tr>
                <td class="roadmap-icon"><img class="img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
                <td class="roadmap-header cursor">
                    <h2><?php echo $txtFirststage ?></h2>
                </td>
            </tr>

        </table>

        <?php if ($CurrentPageURL == "pt") {
            include 'roadmap2022_pt.php';
        } elseif ($CurrentPageURL == "es") {
            include 'roadmap2022_es.php';
        } else {
            include 'roadmap2022_en.php';
        } ?>


    </div>

</section>