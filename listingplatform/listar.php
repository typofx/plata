<?php
include 'conexao.php';
 
$sql = "SELECT * FROM granna80_bdlinks.links WHERE `Score` != 'NOT'";
$result = $conn->query($sql);
?>

<style>

    .table-listing-places, .th-listing-places, .td-listing-places {
        border: 0px solid;
        border-collapse: collapse;
    }

    body {
        font-family: Montserrat;
        font-size: 15px;
        margin: 0;
        padding: 0;
    }

.a-listing-places:link { font-weight: bold; color:gray; text-decoration: none; }
.a-listing-places:visited { color:black; text-decoration: none; }
.a-listing-places:hover { color:black; text-decoration: none; }
.a-listing-places:active { color:black; text-decoration: none; }

.tb-bg-color-light-fortable {
    font-size: 14px;
    background-color:#EDEDED;
}

.tb-bg-color-dark-fortable {
    font-size: 14px;
    background-color:#FFFFFF;
}

.tr-list {background-color:#B5B5B5;}

.topcorner {
  border-radius: 25px;
  background: #73AD21;
  padding: 20px; 
  width: 200px;
  height: 150px;
}

.div-vert {
	width: 10px;
	height: 10px;
	transform: rotateZ(-90deg);
	font-size: 9px;
	//border: 1px solid;
	text-align: left;
}



</style>


<?php

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

function getTICKcolor($value) {
    if ($value == 'K' || $value == 'Y') {
        return 'green';
    } else {
        return 'gray';
    }
}

function backgroundLine($number){
    if($number % 2 == 0){
        return 'tb-bg-color-light-fortable';
    }
    else{
        return 'tb-bg-color-dark-fortable';
    }
}

if ($result->num_rows > 0) {
    echo '<center><table class="table-listing-places">';
    echo '<tr class="tr-list">
            <th> # </th>
            <!--<th> ID </th>-->
            <th> D </th>
            <th> M </th>
            <th> Platform </th>
            <th> Category </th>
            <th>     </th>
            <th> Metrics </th>
            <th> Rank </th>
            <th><div class="div-vert">Cap</div></th>
            <th><div class="div-vert">Liquidity</div></th>
            <th><div class="div-vert">Fully Diluted MC</div></th>
            <th><div class="div-vert">Circulating Supply</div></th>
            <th><div class="div-vert">Max Supply</div></th>
            <th><div class="div-vert">Total Supply</div></th>
            <th><div class="div-vert">Price</div></th>
            <th><div class="div-vert">Graph</div></th>
            <th><div class="div-vert">Holders</div></th>
            <th><div class="div-vert">PLT Logo</div></th> 
            <th><div class="div-vert">Social Media</div></th>
            <th><div class="div-vert">Metamask Button</div></th>
            <th> Score </th>
            <!--
            <th>Obs1</th>
            <th>Obs2</th>
            <th>Edit</th>
            -->
          </tr>';
          
    $i = 1;

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="' . backgroundLine($i) .'"><center>  ' . $i . '  </center></td>'; 
        //echo '<td><center>' . $row["ID"] . '</center></td>';
        echo '<td class="' . backgroundLine($i) .'"><center><img src="https://www.plata.ie/images/listing-'.getTICKcolor($row["Desktop"]).'-tick.svg"></center></td>';
        echo '<td class="' . backgroundLine($i) .'"><center><img src="https://www.plata.ie/images/listing-'.getTICKcolor($row["Mobile"]).'-tick.svg"></center></td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["Platform"]) . '"><center> <a class="a-listing-places" href="' . $row["Link"] . '" target="_blank">' . $row["Platform"] . '</a> </center></td>';
        echo '<td class="' . backgroundLine($i) .'"><center> ' . $row["Type"] . ' </center></td>';
        echo '<td class="' . backgroundLine($i) .'"> <center><img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="'. $row["Country"] .'" height="15" width="15"></td><center>';
        echo '<td class="' . backgroundLine($i) .'"><center> ' . $row["Access"] . ' </center></td>';
        echo '<td class="' . backgroundLine($i) .'"><center>' . $row["Rank"] . '</center></td>';
        echo '<td class="' . backgroundLine($i) .'" <?php style="' . getTXTcolor($row["MarketCap"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["Liquidity"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["FullyDilutedMKC"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["CirculatingSupply"]) . '">█</td>'; 
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["MaxSupply"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["TotalSupply"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["Price"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["Graph"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["Holders"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["TokenLogo"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["SocialMedia"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'" style="' . getTXTcolor($row["MetamaskButton"]) . '">█</td>';
        echo '<td class="' . backgroundLine($i) .'"><center> ' . $row["Score"] . ' </center></td>'; 
       // echo '<td>' . $row["Obs1"] . '</td>';
       //  echo '<td>' . $row["Obs2"] . '</td>';
        //echo '<td><a href="edit.php?id=' . $row["ID"] . '">Edit</a></td>';
        echo '</tr>';
        $i++;
    }

    echo '</table></center><br><br>  <a href="https://plata.ie/listingplatform/painel/painel.php">edit</a>';
} else {
    echo "Nenhum resultado encontrado.";
}

$conn->close();
?>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-RXYGWW7KHB"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-RXYGWW7KHB');
</script>
