<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <title>Painel - Listing places</title>

    <style>
        body {
            font-family: Verdana, sans-serif;
            font-size: 10px;
        }

        table.dataTable {
            width: 300px;

            border-collapse: collapse;
            /* Remove espaços entre bordas */
        }

        table.dataTable th,
        table.dataTable td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
            /* Adiciona borda entre as células */
        }

        table.dataTable th {
            background-color: #fff;
            border-bottom: 2px solid #ccc;
            /* Destaque para o cabeçalho */
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
            /* Destaque ao passar o mouse */
        }

        /* Paginação */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
            cursor: pointer;
        }

        /* Classes utilitárias */
        .invisible {
            display: none;
        }

        .pointer {
            cursor: pointer;
        }



        .colored-cell span {
            margin-right: 9px;
            /* Espaçamento à direita de cada elemento */
        }

        th.no-wrap-icons {
            white-space: nowrap;
            /* Evita quebra de linha no conteúdo do <th> */
        }

        th.no-wrap-icons i {
            display: inline-block;
            /* Exibe os ícones como blocos em linha */
            font-size: 1.3em;
            margin-right: 8px;
            /* Adiciona espaçamento à direita dos ícones */
            vertical-align: middle;
            /* Alinha verticalmente os ícones */
        }

        .status-link {

            align-items: center;
            text-decoration: none;


            border-radius: 4px;
            font-size: 12px;

        }

        .status-link img {
            margin-right: 4px;
        }
    </style>

    <style>
   .hidden-column {
    display: none;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">


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

    <?php
    include '../conexao.php';

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


        echo '<center>
        <div>
            <label>
                <input type="checkbox" id="toggleObs1" onchange="toggleColumn(\'obs1-column\')"> Show/Hide Obs1
            </label>
            <label style="margin-left: 10px;">
                <input type="checkbox" id="toggleObs2" onchange="toggleColumn(\'obs2-column\')"> Show/Hide Obs2
            </label>
        </div>
        <table id="example" style="width: 70%;">';

    ?>

        <thead>
            <tr>
                <th>ID</th>
                <th><i class="fas fa-desktop" title="Desktop"></i></th> <!-- Desktop -->
                <th><i class="fas fa-mobile-alt" title="Mobile"></i></th> <!-- Mobile -->

                <th>Score</th>
                <th>Platform</th>
                <th></th>
                <th class="pointer cl-type"><a onclick="hideType()">Type</a></th>
                <th class="pointer cl-access"><a onclick="hideAccess()">Access</a></th>
                <th class="pointer cl-country"><a onclick="hideCountry()"> </a></th>
                <th class="pointer cl-rank"><a onclick="hideRank()">Rank</a></th>
                <th class="no-wrap-icons">

                </th>



                <th class="obs1-column">Obs1</th>
                <th class="obs2-column">Obs2</th>


                <th>Last Updated           </th>
                <th>Last Edited By</th>
                <th>Actions      </th>
            </tr>
        </thead>

        <tbody>
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
                echo '<td><center>' . intval($cont) . '</center></td>';

                echo '<td class="colored-cell">';
                if (empty($row["full_logo"]) || strtolower(trim($row["full_logo"])) === "no") {
                    echo '<i class="fas fa-desktop" title="Mobile" style="color: green;"></i>';
                } else {
                    echo ''; // Mantém vazio se for "yes"
                }
                echo '</td>';
                echo '<td class="colored-cell">';
                if (!empty($row["full_logo"]) && strtolower(trim($row["full_logo"])) === "yes") {
                    echo '<fas fa-mobile-alt" title="Desktop" style="color: green;"></i>';
                } else {
                    echo '';
                }
                echo '</td>';



                echo '<td class="colored-cell" style="' . setBGcolor($row["Score"] ?? "") . '"><center>' . round(floatval($row["Score"]), 4) . '</center></td>';
                echo '<td class="colored-cell" style="' . setBGcolor($row["Score"] ?? "") . '"><center><a href="' . $row["Link"] . '" target="_blank">' . (!empty($row["Platform"]) ? $row["Platform"] : "update") . '</a></center></td>';

                // Botão de status
                echo '<td class="colored-cell" style="' . setBGcolor($row["Score"] ?? "") . '">
                <a href="new_status.php?id=' . $row["ID"] . '" target="_blank" class="status-link">
                    <i class="fa-solid fa-gear" style="color: ' .
                    (strpos($row["status"], 'green') !== false ? 'green' : 'red') . '; font-size: 16px;"></i>
                </a>
            </td>';



                echo '<td class="cl-type" style="' . setBGcolor($row["Score"] ?? "") . '"><center>' . $row["Type"] . '</center></td>';
                echo '<td class="cl-access" style="' . setBGcolor($row["Score"] ?? "") . '"><center>' . $row["Access"] . '</center></td>';
                echo '<td class="cl-country" style="' . setBGcolor($row["Score"] ?? "") . '"><center><img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="' . $row["Country"] . '" height="20" width="20"></center></td>';
                echo '<td class="cl-rank" style="' . setBGcolor($row["Score"] ?? "") . '"><center>' . $row["Rank"] . '</center></td>';
                echo '<td class="colored-cell" style="white-space: nowrap;">';
                echo '<span style="' . getTXTcolor($row["MarketCap"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-chart-line" title="Market Cap"></i></span>';
                echo '<span style="' . getTXTcolor($row["Liquidity"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-water" title="Liquidity"></i></span>';
                echo '<span style="' . getTXTcolor($row["FullyDilutedMKC"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-coins" title="Fully Diluted Market Cap"></i></span>';
                echo '<span style="' . getTXTcolor($row["CirculatingSupply"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-circle" title="Circulating Supply"></i></span>';
                echo '<span style="' . getTXTcolor($row["MaxSupply"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-layer-group" title="Max Supply"></i></span>';
                echo '<span style="' . getTXTcolor($row["TotalSupply"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-cubes" title="Total Supply"></i></span>';
                echo '<span style="' . getTXTcolor($row["Price"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-dollar-sign" title="Price"></i></span>';
                echo '<span style="' . getTXTcolor($row["Graph"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-chart-bar" title="Graph"></i></span>';
                echo '<span style="' . getTXTcolor($row["Holders"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-users" title="Holders"></i></span>';
                echo '<span style="' . getTXTcolor($row["TokenLogo"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-shield-alt" title="Token Logo"></i></span>';
                echo '<span style="' . getTXTcolor($row["SocialMedia"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-share-alt" title="Social Media"></i></span>';
                echo '<span style="' . getTXTcolor($row["MetamaskButton"] ?? "") . '; font-size: 1.5em;"><i class="fas fa-wallet" title="Metamask"></i></span>';
                echo '</td>';



                echo '<td class="obs1-column" style="' . setBGcolor($row["Score"] ?? "") . '">' . $row["Obs1"] . '</td>';
                echo '<td class="obs2-column" style="' . setBGcolor($row["Score"] ?? "") . '">' . $row["Obs2"] . '</td>';

                echo '<td style="' . setBGcolor($row["Score"] ?? "") . '">' . date("d/m/Y H:i", strtotime($row["last_updated"] ?? "")) . ' (UTC)</td>';
                echo '<td style="' . setBGcolor($row["Score"] ?? "") . '">' . $row["editedBy"] . '</td>';

                // Ações (Telegram, Email, Edit, Delete)
                echo '<td>
        <a href="https://t.me/' . $row["Telegram"] . '" target="_blank"><img src="https://www.plata.ie/images/telegram-logo.svg" alt="Telegram" height="20"></a>
        <a href="mailto:' . $row["Email"] . '" target="_blank"><img src="https://www.plata.ie/images/sheet-icon-email.png" alt="Email" width="15" height="15"></a>
        <a href="edit.php?id=' . $row["ID"] . '"><img src="https://www.plata.ie/plataforma/img/sheet-icon-edit.png" width="15" height="15"></a>
        <a href="delete.php?id=' . $row["ID"] . '" onclick="return confirm(\'Are you sure you want to delete this record?\')"><img src="https://www.plata.ie/plataforma/img/sheet-icon-delete.png" width="15" height="15"></a>
    </td>';

                echo '</tr>';
                $cont++;
            }
            ?>
        </tbody>
        </table>
        </center>

    <?php
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        /* Define largura reduzida para colunas específicas */
        .narrow-column {
            width: 5px !important;
            /* Força a largura desejada */
            min-width: 5px !important;
            /* Garante o mínimo */
            max-width: 5px !important;
            /* Evita aumentar */
            text-align: center;
        }
    </style>
<script>
    function toggleColumn(columnClass) {
        const elements = document.querySelectorAll('.' + columnClass);
        elements.forEach(element => {
            element.style.display = (element.style.display === 'none') ? '' : 'none';
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Inicialmente ocultar as colunas Obs1 e Obs2
        toggleColumn('obs1-column');
        toggleColumn('obs2-column');
    });
</script>



    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                // Configurações adicionais
                "paging": true,
                "pageLength": 100,
                "lengthMenu": [10, 25, 50, 100],
                "searching": true, // Caixa de busca
                "info": true, // Informação de páginas
                "autoWidth": false, // Desativa ajuste automático de largura

            });
        });
    </script>



</body>

</html>