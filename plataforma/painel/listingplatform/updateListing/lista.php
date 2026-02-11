<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Painel - Listing places</title>

    <style>
        body {
            font-family: Verdana;
            font-size: 10px;
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
        function hideCountry() {
            const listElement = document.querySelectorAll(".cl-country");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
        }

        function hideAccess() {
            const listElement = document.querySelectorAll(".cl-access");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
        }

        function hideType() {
            const listElement = document.querySelectorAll(".cl-type");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
        }

        function hideEmail() {
            const listElement = document.querySelectorAll(".cl-email");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
        }

        function hideTelegram() {
            const listElement = document.querySelectorAll(".cl-telegram");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
        }

        function hideRank() {
            const listElement = document.querySelectorAll(".cl-rank");
            for (let i = 0; i < listElement.length; i++) {
                listElement[i].classList.toggle("invisible");
            }
        }
    </script>
</head>

<body>

    <!--

    <a href="https://plata.ie/listingplatform/?mode=full" class="btn btn-primary" target="_blank">Main(Index)</a>
    <a href="insert.php" class="btn btn-primary">Add Record</a>
    <a href="cadastro" class="btn btn-primary">Add New user</a>
    <a href="roadmap" class="btn btn-primary">EditRoadmap</a>
    <form action="new_status.php" method="post" target="_blank">
        <button type="submit" name="status">Update Status</button>
    </form>
    -->
    <a href="painel-v2.php">[ PAINEL V2]</a>
    <?php

    include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/conexao.php';

    $sql = "SELECT * FROM granna80_bdlinks.links ORDER BY `Score` DESC, Access DESC, Rank DESC;";

    $result = $conn->query($sql);
    $links_data = [];

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

    function setBGcolor($score)
    {
        $score = floatval($score);

        if ($score >= 70 && $score <= 100) {
            return 'background-color: #90EE90;';
        } elseif ($score >= 45 && $score < 70) {
            return 'background-color: #fdfd96;';
        } else {
            return 'background-color: #ff6961;';
        }
    }

    //  function verificaStatusSite($url)
    //  {
    //  $headers = @get_headers($url);

    //  if ($headers) {
    // Extrai o código de status HTTP
    //   $statusCode = explode(' ', $headers[0])[1];

    // Array de códigos de status que consideramos como "Online"
    //    $onlineStatusCodes = array('200', '401', '403', '500', '308');
    //
    //      if (in_array($statusCode, $onlineStatusCodes)) {
    //          return '<span style="color: green;">Online HTTP CODE: ' . $statusCode . '</span>';
    //      } else {
    //          return '<span style="color: red;">Offline HTTP CODE:' . $statusCode . '</span>';
    //       }
    //   } else {
    //        return '<span style="color: red;">Failed to get headers</span>';
    //    }
    // }



    if ($result->num_rows > 0) {
        echo '<style>
            .colored-cell {
                padding: 0px; /* Add padding for better visibility */
                text-align: center;
                
            }
          </style>';

        //tabela inicio

        echo '<center><table style="border: 1px solid;width:100%">'; ?>
        <tr>
            <th>ID</th>
            <th><img src="https://www.plata.ie/images/td-desktop.png"></th>
            <th><img src="https://www.plata.ie/images/td-mobile.png"></th>
            <th>Score</th>
            <th>Platform</th>
            <th>Status</th>
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
            <th>Obs2</th>]
            <th>Full logo</th>
            <th>Last Updated</th>
            <th>Last edited by</th>
        </tr>
        <style>
            .icon-button {
                border: none;
                padding: 0;
                background: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
            }

            .icon-button img {
                margin-right: 5px;
            }
        </style>

    <?php
        $cont = 1;
        while ($row = $result->fetch_assoc()) {

            $links_data[] = [
                "ID" => intval($cont),
                "Platform" => !empty($row["Platform"]) ? $row["Platform"] : "update",
                "link" => $row["Link"],
                "Score" => round(floatval($row["Score"]), 4),
                "Icone" => $row["logo"],
                "Full-Logo" => $row["full_logo"],
                "Type" => $row["Type"],
                "Access" => intval($row["Access"]),
                "Country" => $row["Country"],
                "Rank" => $row["Rank"],
                "MarketCap" => $row["MarketCap"],
                "Liquidity" => $row["Liquidity"],
                "FullyDilutedMKC" => $row["FullyDilutedMKC"],
                "CirculatingSupply" => $row["CirculatingSupply"],
                "MaxSupply" => $row["MaxSupply"],
                "TotalSupply" => $row["TotalSupply"],
                "Price" => $row["Price"],
                "Graph" => $row["Graph"],
                "Holders" => $row["Holders"],
                "TokenLogo" => $row["TokenLogo"],
                "SocialMedia" => $row["SocialMedia"],
                "MetamaskButton" => $row["MetamaskButton"],
                "Obs1" => $row["Obs1"],
                "Obs2" => $row["Obs2"],
                "LastUpdated" => date("d-m-Y H:i", strtotime($row["last_updated"])),
                "EditedBy" => $row["editedBy"]
            ];

            $update = 'Update';
            echo '<tr>';
            echo '<td><center>' . $row["ID"] . '</center></td>';
            //echo '<td class="colored-cell" style="' . setBGcolor($row["Score"]) . '"><center> ' . $row["Listed"] . ' </center></td>';
            echo '<td class="colored-cell" style="' . getTXTcolor($row["Desktop"]) . '"></td>';
            echo '<td class="colored-cell" style="' . getTXTcolor($row["Mobile"]) . '"></td>';
            echo '<td class="colored-cell" style="' . setBGcolor($row["Score"]) . '"><center>' . $row["Score"] . '</center></td>';
            echo '<td class="colored-cell" style="' . setBGcolor($row["Score"]) . '"><center><a href="' . $row["Link"] . '" target="_blank">' . (!empty($row["Platform"]) ? $row["Platform"] : "update") . '</a></center></td>';
            echo '<td class="colored-cell" style="' . setBGcolor($row["Score"]) . '">
            <center>';
            if (empty($row["status"])) {
                echo '<form method="post" target="_blank" action="new_status.php">
                <input type="hidden" name="id" value="' . $row["ID"] . '">
                <button type="submit" name="atualizar_individual" class="icon-button">
                    <img src="update.png" alt="Ícone" width="20" height="20"> 
                    <span> <a href="' . $row["Link"] . '" target="_blank">' . $update . '</a> </span>
                </button>
              </form>';
            } else {
                echo '<form method="post" target="_blank" action="new_status.php">
                <input type="hidden" name="id" value="' . $row["ID"] . '">
                <button type="submit" name="atualizar_individual" class="icon-button">
                    <img src="update.png" alt="Ícone" width="20" height="20"> 
                    <span> <a href="' . $row["Link"] . '" target="_blank">' . $row["status"] . '</a> </span>
                </button>
              </form>';
            }
            echo '</center>
        </td>';



            echo '<td class="cl-type" style="' . setBGcolor($row["Score"]) . '"><center> ' . $row["Type"] . ' </center></td>';
            echo '<td class="cl-access" style="' . setBGcolor($row["Score"]) . '"><center> ' . $row["Access"] . ' </center></td>';
            echo '<td class="cl-country" style="' . setBGcolor($row["Score"]) . '"><center><img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="' . $row["Country"] . '" height="20"></td><center>';
            echo '<td class="cl-rank" style="' . setBGcolor($row["Score"]) . '"><center> ' . $row["Rank"] . ' </center></td>';
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

            echo '<td style="' . setBGcolor($row["Score"]) . '">' . $row["Obs1"] . '</td>';
            echo '<td style="' . setBGcolor($row["Score"]) . '">' . $row["Obs2"] . '</td>';
            echo '<td style="' . setBGcolor($row["Score"]) . '"><center>' . (!empty($row["full_logo"]) ? 'Yes' : 'No') . '</center></td>';

            echo '<td style="' . setBGcolor($row["Score"]) . '">' . date("d/m/Y H:i", strtotime($row["last_updated"])) . ' (UTC)</td>';
            echo '<td style="' . setBGcolor($row["Score"]) . '">' . $row["editedBy"] . '</td>';
            echo '<td>
                    <a href="https://t.me/' . $row["Telegram"] . '" target="_blank"><img src="https://www.plata.ie/images/telegram-logo.svg" alt="Telegram" width="20px" height="20px"></a>
                    <a href="mailto:' . $row["Email"] . '" target="_blank"><img src="https://www.plata.ie/images/sheet-icon-email.png" alt="Email"></a>
                    <a href="edit.php?id=' . $row["ID"] . '" style="' . setBGcolor($row["Listed"]) . '"><img src="https://www.plata.ie/plataforma/img/sheet-icon-edit.png"></a>
                    <a href="delete.php?id=' . $row["ID"] . '" onclick="return confirm(\'Are you sure you want to delete this record?\')" style="' . setBGcolor($row["Listed"]) . '"><img src="https://www.plata.ie/plataforma/img/sheet-icon-delete.png"></a>
            </td>';

            echo '</tr>';
            $cont++;
        }

        echo '</table></center><br><br>';
        $json_data = json_encode($links_data,  JSON_NUMERIC_CHECK);


        $file = 'links_data.json';

        if (file_put_contents($file, $json_data)) {
            echo "JSON data has been stored in 'links_data.json'.";
        } else {
            echo "Error writing JSON data to the file.";
        }

        echo "<p>JSON file successfully created: <a href='links_data.json' target='_blank'>[JSON]</a></p>";
    } else {
        echo "No results found.";
    }

    $conn->close();
    ?>
</body>

</html>