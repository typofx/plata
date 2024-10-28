<?php




session_start();






if (!isset($_SESSION['mode'])) {
    $_SESSION['mode'] = 'full';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['mode'] = isset($_POST['mode']) && $_POST['mode'] === 'full' ? 'full' : 'normal';
}


$fullMode = $_SESSION['mode'] === 'full';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Plata Token Listing</title>
    <link rel="stylesheet" href="mobile-style-new-listing.css">
    <style>
        .search-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/../languages/languages.php';?>
<?php  include $_SERVER['DOCUMENT_ROOT'] . '/mobile/mobile-header.php'; ?>

<body>
    <center>

        <div class="container">
            <br>
            <h1 class="title-plataforma">Plataforma</h1>
            <h2>Listing Places for Plata Token (PLT)</h2>

            <?php
            include 'conexao.php';

            $sql = "SELECT * FROM granna80_bdlinks.links";

            if (isset($_POST['search']) && !empty($_POST['search'])) {
                $searchTerm = $_POST['search'];
                $sql .= " WHERE (`Platform` LIKE '%$searchTerm%') OR (`Type` LIKE '%$searchTerm%')";
                
               
                if ($fullMode) {
                    $sql .= " AND (`Listed` = 1)";
                }
            } else {
                
                if ($fullMode) {
                    $sql .= " WHERE (`Listed` = 1)";
                }
            }
            
            
            $sql .= " ORDER BY `Score` DESC, Access DESC, Rank DESC";

            $result = $conn->query($sql);

            $sqlScore = "SELECT * FROM granna80_bdlinks.links ORDER BY `Access` DESC LIMIT 1 OFFSET 5";
            $resultScore = $conn->query($sqlScore);

            if ($resultScore->num_rows > 0) {
                while ($rowScore = $resultScore->fetch_assoc()) {
                    $avgScore = (float)($rowScore["Access"]) / 2;
                }
            }

            $sqlTotal = "SELECT COUNT(*) AS TotalListed FROM granna80_bdlinks.links WHERE Listed = 1";
            $resultTotal = $conn->query($sqlTotal);

            // Check if there are results and fetch the total
            if ($resultTotal->num_rows > 0) {
                $totalRow = $resultTotal->fetch_assoc();
                $totalListed = $totalRow['TotalListed'];
            }

            if ($result->num_rows > 0) {
                echo '
                <form action="" method="post">
                    <label for="search">Filter : </label>
                    <input type="text" id="search" name="search" autocomplete="off">&nbsp;<input type="submit" value="&#128269;">
                    <br><p>Plata Token (PLT) is listed on ' . $totalListed . ' projects.</p>
                </form>';

                echo '<div class="listing">';
                echo '<table class="listing-container">';
                echo '<tr class="label">';
                echo '<td class="number padding-column border-bottom2px">#</td>';

                // echo '<td class="platform padding-column border-bottom2px"> </td>';
                echo '<td class="platform padding-column border-bottom2px" style=" text-align: left;">Platform</td>';
                echo '<td class="category padding-column border-bottom2px">Category</td>';
                echo '<td class="score padding-column border-bottom2px">Score</td>';
                echo '</tr>';

                $i = 1;

                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="platform-data">';
                    echo '<td class="number padding-column border-bottom1px" id="number">';
                    echo '<span class="text-number">' . $i . '</span>';
                    echo '</td>';
                    // echo '<td class="logo-platform padding-column border-bottom1px"></td>';
                    echo '<td class="platform1 padding-column border-bottom1px" id="platform" style="vertical-align: middle; text-align: left;">';
                    echo '<img src="' . (empty($row["logo"]) ? "https://plata.ie/images/platatoken200px.png" : "https://www.plata.ie/images/icolog/" . $row["logo"]) . '" alt="img" height="22px" class="black-and-white" style="vertical-align: middle;margin-right: 7px">';
                    echo '<a class="a-listing-places" href="' . $row["Link"] . '" target="_blank">' . $row["Platform"] . '</a>';
                    echo '</td>';

                    echo '<td class="category padding-column border-bottom1px" id="category">' . $row["Type"] . '</td>';
                    echo '<td class="score padding-column border-bottom1px" id="score">' . number_format($row["Score"], 0, '.', '') . '%</td>';
                    echo '</tr>';
                    $i++;
                }

                echo '</table></div><br><br>';
            } else {
                echo "No results found.";
            }

            $conn->close();



            echo '<div class="button-container">';
            echo '<center><form action="" method="post">';
            echo '<input type="checkbox" id="platalisted" name="mode" value="full" onchange="this.form.submit()"';
            if ($fullMode) {
                echo ' checked';
            }
            echo '>';
            echo '<label for="platalisted">Plata Token listed Platforms.</label>';
            echo '</form></center><br><br>';
            echo '<br>';
            echo '<br>';
            echo '</div>';


            ?>

        </div>

        <script>
           
            var table = document.querySelector('.listing-container');

           
            var lastRow = table.querySelector('tr:last-child');

            
            var cellsInLastRow = lastRow.querySelectorAll('.border-bottom1px');
            for (var i = 0; i < cellsInLastRow.length; i++) {
                cellsInLastRow[i].style.borderBottom = 'none';
            }
        </script>

        <script>
            function msgInfo() {
                alert("Help\nWebsite Listed on Desktop Version;\nWebsite Listed on Mobile Version;");
            }
        </script>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-RXYGWW7KHB"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-RXYGWW7KHB');
        </script>
</body>
<br>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/../en/mobile/footer.php'; ?>

</html>