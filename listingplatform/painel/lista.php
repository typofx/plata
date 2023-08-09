<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Painel - Listing places</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="border-end bg-white" id="sidebar-wrapper">
            <!-- Sidebar content... -->
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <!-- Top navigation content... -->
            </nav>
            <div class="container-fluid">
               
            <?php
include '../conexao.php';

$sql = "SELECT * FROM granna80_bdlinks.links;";
$result = $conn->query($sql);

function getTXTcolor($value) {
    if ($value == 'K' || $value == 'Y') {
        return 'color: green;';
    } elseif ($value == 'Z') {
        return 'color: gray;';
    } elseif ($value == 'E' || $value == 'W') {
        return 'color: red;';
    } else {
        return '';
    }
}

if ($result->num_rows > 0) {
    echo '<style>
            .colored-cell {
                padding: 0px; /* Add padding for better visibility */
                text-align: center;
                
            }
          </style>';
    echo '<center><table style="border: 1px solid;">';
    echo '<tr>
            <th>ID</th>
            <th><img src="https://www.plata.ie/images/td-desktop.png"></th>
            <th><img src="https://www.plata.ie/images/td-mobile.png"></th>
            <th>Score</th>
            <th>Platform</th>
            <th>Type</th>
            <th>Access</th>
            <th>Country</th>
            <th>Rank</th>
            <th><img src="https://www.plata.ie/images/marketcap.png"></th>
            <th><img src="https://www.plata.ie/images/td-liquidity.png"></th>
            <th><img src="https://www.plata.ie/images/td-fully.png"></th>
            <th><img src="https://www.plata.ie/images/td-circulating.png"></th>
            <th><img src="https://www.plata.ie/images/td-max.png"></th>
            <th><img src="https://www.plata.ie/images/td-total.png"></th>
            <th><img src="https://www.plata.ie/images/td-price.png"></th>
            <th class="colored-cell" style="background-color: yellow;"><img src="https://www.plata.ie/images/td-graph.png"></th>
            <th class="colored-cell" style="background-color: yellow;"><img src="https://www.plata.ie/images/td-holders.png"></th>
            <th class="colored-cell" style="background-color: yellow;"><img src="https://www.plata.ie/images/td-tokenlogo.png"></th>
            <th class="colored-cell" style="background-color: yellow;"><img src="https://www.plata.ie/images/td-socialmedia.png"></th>
            <th class="colored-cell" style="background-color: yellow;"><img src="https://www.plata.ie/images/td-metamask.png"></th>
            <th>Obs1</th>
            <th>Obs2</th>
            <th>Edit</th>
          </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["ID"] . '</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Desktop"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Mobile"]) . '">█</td>';
        echo '<td><center> ' . $row["Score"] . ' </center></td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Platform"]) . '"><center> <a href="' . $row["Link"] . '" target="_blank">' . $row["Platform"] . '</a> </center></td>';
        echo '<td><center> ' . $row["Type"] . ' </center></td>';
        echo '<td><center> ' . $row["Access"] . ' </center></td>';
        echo '<td> <center><img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="'. $row["Country"] .'" height="20"></td><center>';
        echo '<td>' . $row["Rank"] . '</td>';
        echo '<td class="colored-cell" style=" width:20px;' . getTXTcolor($row["MarketCap"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Liquidity"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["FullyDilutedMKC"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["CirculatingSupply"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["MaxSupply"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["TotalSupply"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Price"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Graph"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["Holders"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["TokenLogo"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["SocialMedia"]) . '">█</td>';
        echo '<td class="colored-cell" style="' . getTXTcolor($row["MetamaskButton"]) . '">█</td>';
        echo '<td>' . $row["Obs1"] . '</td>';
        echo '<td>' . $row["Obs2"] . '</td>';
        echo '<td><a href="edit.php?id=' . $row["ID"] . '">Edit</a></td>';
        echo '</tr>';
    }

    echo '</table></center><br><br>';
} else {
    echo "Nenhum resultado encontrado.";
}

$conn->close();
?>
<style>
    table, th, td {
  border: 1px solid;
  border-collapse: collapse;
}
    
</style>


            
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
