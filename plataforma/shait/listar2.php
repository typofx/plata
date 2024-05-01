<script>
//    window.onload = function() {
//        var redirected = sessionStorage.getItem('redirected');
//        if (!redirected) {
//            sessionStorage.setItem('redirected', 'true');
//            window.location.href = 'https://www.plata.ie/listingplatform/?mode=full';
//        }
//    };
</script>



<?php
include 'conexao.php';

// Check if full view is enabled
$fullMode = isset($_GET['mode']) && $_GET['mode'] === 'full';


if ($fullMode) {
    $sql = "SELECT * FROM granna80_bdlinks.links Rank DESC FETCH FIRST 3 ROWS ONLY";
    //normal mode 
} else {
    $sql = "SELECT TOP 3 * FROM Customers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxAccess = $row["Access"];
        echo "The highest Access value is: " . $maxAccess;
    } else {
        echo "No results found";
    }
}

$result = $conn->query($sql);
?>






<style>
    .table-listing-places,
    .th-listing-places,
    .td-listing-places {
        border: 0px solid;
        border-collapse: collapse;
    }

    body {
        font-family: Montserrat;
        font-size: 15px;
        margin: 0;
        padding: 0;
    }

    .a-listing-places:link {
        font-weight: bold;
        color: #3A3B3C;
        text-decoration: none;
    }

    .a-listing-places:visited {
        color: #3A3B3C;
        text-decoration: none;
    }

    .a-listing-places:hover {
        color: gray;
        text-decoration: none;
    }

    .a-listing-places:active {
        color: gray;
        text-decoration: none;
    }

    .act-link {
        cursor: pointer;
        color: black;
    }

    .tb-bg-color-light-fortable {
        font-size: 14px;
        background-color: #EDEDED;
    }

    .tb-bg-color-dark-fortable {
        font-size: 14px;
        background-color: #FFFFFF;
    }

    .tr-list {
        background-color: #B5B5B5;
        height: 30px;
    }

    .button-container {
        text-align: center;
    }

    .button {
        background-color: purple;
        border: none;
        color: white;
        padding: 15px 32px;
        margin: 0 1.5px;
        /* 3px space divided by 2 */
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
</style>


<?php

$mediaGeral = ($maxAccess + 1) / 2;
echo "<br>" . $mediaGeral . "> B<br>";
echo "<br>" . ($mediaGeral + $mediaGeral * 0.5) . "> A<br>";
echo "<br>" . ($mediaGeral) . "> B<br>";
echo "<br>" . ($mediaGeral *0.75) . "> C<br>";
echo "<br>" . ($mediaGeral *0.5) . "> D<br>";


function getAccessLetter($accessValue, $mediaGeral) {
  
    if ($accessValue <= ($mediaGeral + $mediaGeral/4) ) {
        return 'D';
    } elseif ($accessValue <= ($mediaGeral + $mediaGeral / 2) ) {
        return 'C';
    } elseif ($accessValue >= $mediaGeral) {
        return 'B';
    } elseif ($accessValue >= $mediaGeral * 1.5) {
        return 'A';
    } else {
        return 'A+';
    }
}





function getTXTcolor($value)
{
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

function getTICKcolor($value)
{
    if ($value == 'K' || $value == 'Y') {
        return 'green';
    } else {
        return 'gray';
    }
}

function backgroundLine($number)
{
    if ($number % 2 == 0) {
        return 'tb-bg-color-light-fortable';
    } else {
        return 'tb-bg-color-dark-fortable';
    }
}

function tokenRanked($data)
{
    if ($data == '-') {
        return 'color: gray;';
    } else {
        return 'color: green;';
    }
}

if ($result->num_rows > 0) {
    echo '<center><table class="table-listing-places">';
    echo '<tr class="tr-list">
            <th> # </th>
            <!--<th> ID </th>-->
            <th><a class="act-link" onclick="msgInfo()">🖳</a></th>
            <th><a class="act-link" onclick="msgInfo()">☎</a></th>
            <th> Platform </th>
            <th> Category </th>
            <th>     </th>
            <th> Metrics </th>
            <th colspan="13"> Performance </th>
            <th>  Score  </th>
            <!--
            <th>Obs1</th>
            <th>Obs2</th>
            <th>Edit</th>
            -->
          </tr>';

    $i = 1;

    while ($row = $result->fetch_assoc()) {





        echo '<tr>';
        echo '<td class="' . backgroundLine($i) . '"><center>  ' . $i . '  </center></td>';
        //echo '<td><center>' . $row["ID"] . '</center></td>';
        echo '<td class="' . backgroundLine($i) . '"><center><img height="13px" src="https://www.plata.ie/images/listing-' . getTICKcolor($row["Desktop"]) . '-tick.svg"></center></td>';
        echo '<td class="' . backgroundLine($i) . '"><center><img height="13px" src="https://www.plata.ie/images/listing-' . getTICKcolor($row["Mobile"]) . '-tick.svg"></center></td>';

        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["Platform"]) . '"><center> <a class="a-listing-places" href="' . $row["Link"] . '" target="_blank">' . $row["Platform"] . '</a> </center></td>';
        echo '<td class="' . backgroundLine($i) . '"><center> ' . $row["Type"] . ' </center></td>';
        echo '<td class="' . backgroundLine($i) . '"> <center><img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="' . $row["Country"] . '" height="15" width="15"></td><center>';
        echo '<td class="' . backgroundLine($i) . '"><center>' . getAccessLetter($row["Access"], $maxAccess, $mediaGeral) . '</center></td>';

        echo '<td class="' . backgroundLine($i) . '"><center>' . $row["Access"] . '</center></td>';
        echo '<td class="' . backgroundLine($i) . '" <?php style="' . tokenRanked($row["Rank"]) . '"> █</td>';
        echo '<td class="' . backgroundLine($i) . '" <?php style="' . getTXTcolor($row["MarketCap"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["Liquidity"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["FullyDilutedMKC"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["CirculatingSupply"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["MaxSupply"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["TotalSupply"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["Price"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["Graph"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["Holders"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["TokenLogo"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["SocialMedia"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '" style="' . getTXTcolor($row["MetamaskButton"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) . '"><center> ' . $row["Score"] . '% </center></td>';
        // echo '<td>' . $row["Obs1"] . '</td>';
        //  echo '<td>' . $row["Obs2"] . '</td>';
        //echo '<td><a href="edit.php?id=' . $row["ID"] . '">Edit</a></td>';
        echo '</tr>';
        $i++;
    }

    echo '</table></center><br><br>  '; //<a href="https://plata.ie/listingplatform/painel/painel.php" target="_blank">edit</a>
} else {
    echo "No results found.";
}

$conn->close();



echo '<div class="button-container">';
echo '<center><form action="" method="get">';
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
<script>



</script>