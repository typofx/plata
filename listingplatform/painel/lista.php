<?php
session_start(); 


if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {

    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Painel - Listing places</title>

    <style>
        body {
            font-family: Verdana;
            font-size: 12px;
        }
        
        table,
        th,
        td {
            border: 1px solid;
            border-collapse: collapse;
        }
        
         .invisible {
                display: none;
         }
         
         .pointer {
             cursor: pointer;
             
         }
        
    </style>
    <script>
    
    function hideCountry(){
        const listElement = document.querySelectorAll(".cl-country");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
    }
    
    function hideAccess(){
        const listElement = document.querySelectorAll(".cl-access");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }    
    }
    
    function hideType(){
        const listElement = document.querySelectorAll(".cl-type");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }     
    }
    
    function hideEmail(){
        const listElement = document.querySelectorAll(".cl-email");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }     
    }
    
    function hideTelegram(){
        const listElement = document.querySelectorAll(".cl-telegram");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }     
    }
    
    function hideRank(){
        const listElement = document.querySelectorAll(".cl-rank");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }     
    }    
    </script>
</head>

<body>
    
    <a href="https://plata.ie/listingplatform/" class="btn btn-primary">Main(Index)</a>
    <a href="insert.php" class="btn btn-primary">Add New Record</a>
    <a class="invisible pointer cl-type" onclick="hideType()">Type</a>
    <a class="invisible pointer cl-access" onclick="hideAccess()">Access</a>
    <a class="invisible pointer cl-country" onclick="hideCountry()">Country</a>
    <a class="pointer cl-email" onclick="hideEmail()">Email</a>
    <a class="pointer cl-telegram" onclick="hideTelegram()">Telegram</a>

    <?php
    include '../conexao.php';

    $sql = "SELECT * FROM granna80_bdlinks.links;";
    $result = $conn->query($sql);

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

    if ($result->num_rows > 0) {
        echo '<style>
            .colored-cell {
                padding: 0px; /* Add padding for better visibility */
                text-align: center;
                
            }
          </style>';

        //tabela inicio

        echo '<center><table style="border: 1px solid;">'; ?>
        <tr>
            <th>ID</th>
            <th><img src="https://www.plata.ie/images/td-desktop.png"></th>
            <th><img src="https://www.plata.ie/images/td-mobile.png"></th>
            <th>Score</th>
            <th>Platform</th>
            <th class="pointer cl-type"><a onclick="hideType()">Type</th>
            <th class="pointer cl-access"><a onclick="hideAccess()">Access</th>
            <th class="pointer cl-country"><a onclick="hideCountry()">Country</a></th> 
            <th class="pointer cl-rank"><a onclick="hideRank()">Rank</a></th>
            <th><img src="https://www.plata.ie/images/marketcap.png"></th>
            <th><img src="https://www.plata.ie/images/td-liquidity.png"></th>
            <th><img src="https://www.plata.ie/images/td-fully.png"></th>
            <th><img src="https://www.plata.ie/images/td-circulating.png"></th>
            <th><img src="https://www.plata.ie/images/td-max.png"></th>
            <th><img src="https://www.plata.ie/images/td-total.png"></th>
            <th><img src="https://www.plata.ie/images/td-price.png"></th>
            <th><img src="https://www.plata.ie/images/td-graph.png"></th>
            <th><img src="https://www.plata.ie/images/td-holders.png"></th>
            <th><img src="https://www.plata.ie/images/td-tokenlogo.png"></th>
            <th><img src="https://www.plata.ie/images/td-socialmedia.png"></th>
            <th><img src="https://www.plata.ie/images/td-metamask.png"></th>
            <th>Obs1</th>
            <th>Obs2</th>
            <th class="invisible pointer cl-email">Email</th>
            <th class="invisible pointer cl-telegram">Telegram</th>
            <th> Edit </th>
          </tr>
          
        <?php
        while ($row = $result->fetch_assoc()) {  
            echo '<tr>';
            echo '<td>' . $row["ID"] . '</td>';
            echo '<td class="colored-cell" style="' . getTXTcolor($row["Desktop"]) . '"></td>';
            echo '<td class="colored-cell" style="' . getTXTcolor($row["Mobile"]) . '"></td>';
            echo '<td><center> ' . $row["Score"] . ' </center></td>';
            echo '<td class="colored-cell" style="' . getTXTcolor($row["Platform"]) . '"><center> <a href="' . $row["Link"] . '" target="_blank">' . $row["Platform"] . '</a> </center></td>';
            echo '<td class="cl-type"><center> ' . $row["Type"] . ' </center></td>';
            echo '<td class="cl-access"><center> ' . $row["Access"] . ' </center></td>';
            echo '<td class="cl-country"><center><img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="' . $row["Country"] . '" height="20"></td><center>';
            echo '<td class="cl-rank"><center> ' . $row["Rank"] . ' </center></td>';
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
            echo '<td class="invisible cl-email">' . $row["Email"] . '</td>';
            echo '<td class="invisible cl-telegram colored-cell" style="' . getTXTcolor($row["Telegram"]) . '"><center> <a href="https://t.me/' . $row["Telegram"] . '" target="_blank">' . $row["Telegram"] . '</a> </center></td>';
            echo '<td><a href="edit.php?id=' . $row["ID"] . '"><img src="https://www.plata.ie/listingplatform/img/sheet-icon-edit.png"></a>
                      <a href="delete.php?id=' . $row["ID"] . '" onclick="return confirm(\'Are you sure you want to delete this record?\')"><img src="https://www.plata.ie/listingplatform/img/sheet-icon-delete.png"></a>
            </td>';

            echo '</tr>';
        }

        echo '</table></center><br><br>';
    } else {
        echo "Nenhum resultado encontrado.";
    }

    $conn->close();
    ?>
    </body>

</html>

