<section id="AnchorInitialSplit">
    <?php
    $gradient = '';
    $start = 0;

    // URL do JSON
    $url = 'https://plata.ie/plataforma/painel/tokenomics/liquidity_data.json';

    // Obter o conteúdo JSON da URL
    $json = file_get_contents($url);

    // Decodificar o JSON em um array associativo
    $data = json_decode($json, true);

    // Verificar se a decodificação foi bem-sucedida
    if ($data === null) {
        echo 'Error decoding JSON.';
        exit;
    }

    $colors = ['#dd137b', '#974578', '#7a7292', '#6b4f91', '#622378', '#969594', '#72706f', '#371d74', '#9d487c', '#622378'];

    function getColor($index, $colors)
    {
        return $colors[$index % count($colors)];
    }

    foreach ($data as $index => $item) {
        $color = getColor($index, $colors);
        $percentage = $item['percentage'] * 100;
        $end = $start + $percentage;

        if ($index > 0) {
            $gradient .= ', ';
        }

        $gradient .= $color . ' ' . $start . '% ' . $end . '%';
        $start = $end;
    }

    $gradient .= ', black ' . $start . '% 100%';

    ?>
    <link rel="stylesheet" href="https://www.plata.ie/sandbox/ux/split/desktop-split.css">
    <style>
        .donut-background {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(from 315deg,
                    <?php echo $gradient; ?>
                );
        }
    </style>

    <input class="radio-inv" type="radio" id="radio-2024" name="view" checked>
    <input class="radio-inv" type="radio" id="radio-2023" name="view">
    <input class="radio-inv" type="radio" id="radio-nft" name="view">
    <input class="radio-inv" type="radio" id="radio-2022" name="view">

    <div class="background-ground">
        <table class="tb-token">
            <tbody>
                <tr>
                    <td class="tb-subtitle cursor" style="display: table-cell;" colspan="8">
                        <center>
                            <h2>Plata Tokenomics</h2>
                        </center>
                    </td>
                </tr>
                <tr class="line-column-border">
                    <td class="td-token-column td-token-split" colspan="4">
                        <table class="split-table" style="display:table;">
                            <tbody>
                                <tr>
                                    <td>
                                        <h3 class="tb-subtitle cursor token-title-2024">Token Allocation (2024)</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3 class="tb-subtitle cursor token-title-2023">Token Allocation (2023)</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3 class="tb-subtitle cursor token-title-nft">NFT Marketplace</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h3 class="tb-subtitle cursor token-title-2022">Initial Split (2022)</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="split-table donut-2024">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="donut">
                                            <div class="donut-background"></div>
                                            <div class="donut-white"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="split-table graph-view-2023">
                            <tbody>
                                <tr>
                                    <td>
                                        <img class="center-img"
                                            src="https://www.plata.ie/images/token-split-chart-2023.svg">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="split-table graph-view-nft">
                            <tbody>
                                <tr>
                                    <td>
                                        <img class="center-img"
                                            src="https://www.plata.ie/images/token-split-chart-nft.svg">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="split-table graph-view-2022">
                            <tbody>
                                <tr>
                                    <td>
                                        <img class="center-img"
                                            src="https://www.plata.ie/images/token-split-chart-2022.svg">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="split-table" style="display:table">
                            <tbody>
                                <tr>
                                    <td class="td-suply">
                                        <span class="gray-button cursor">Circulating Supply: 11,299,000,992 PLT</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="td-tb-info td-token-column line-column-border" colspan="4">
                        <table class="tb-info border-text-align cursor token-list-2024">
                            <tbody>
                                <?php
                                foreach ($data as $index => $item) {
                                    $color = getColor($index, $colors);
                                    echo '<tr> <td class="td-tb-info td-info-top td-info-left">';
                                    echo '<span class="color-box" style="background-color: ' . $color . ';"></span>';
                                    echo '<a class="a-list"> ' . $item['exchange'];
                                    echo ' ( ' . $item['percentage'] * 100 . '% )</a>';
                                    echo '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <table class="tb-info border-text-align cursor token-list-2023">
                            <tbody>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #72706f;"></span>
                                        <a class="a-list">Null Address: 0x00...dEaD ( 49% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #dd137b;"></span>
                                        <a class="a-list">Typo FX: Wallets ( 26% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #9d487c;"></span>
                                        <a class="a-list">Uniswap V3 ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #9d487c;"></span>
                                        <a class="a-list">Uniswap V3 ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #7a7292;"></span>
                                        <a class="a-list">Quickswap DEX ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #6b4f91;"></span>
                                        <a class="a-list">SushiSwap V2 ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #622378;"></span>
                                        <a class="a-list">Promotional Giveaway ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #969594;"></span>
                                        <a class="a-list">AirDrop dApp ( 4% )</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="tb-info border-text-align cursor token-list-nft">
                            <tbody>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #6b4f91;"></span>
                                        <a class="a-list">Artists who collaborated ( 20% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #622378;"></span>
                                        <a class="a-list">Liquidity for ACTM Project ( 30% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #9d487c;"></span>
                                        <a class="a-list">Rainfall's Victims in Brazil ( 50% )</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="tb-info border-text-align cursor token-list-2022">
                            <tbody>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #9d487c"></span>
                                        <a class="a-list">Platform Operational Costs ( 10% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #622378;"></span>
                                        <a class="a-list">Legal Support ( 20% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #6b4f91;"></span>
                                        <a class="a-list">Decentralized Management ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #7a7292;"></span>
                                        <a class="a-list">Expenses to Project Mentors ( 5% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #d8276e;"></span>
                                        <a class="a-list">Marketing and Promotion ( 20% )</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-tb-info td-info-top td-info-left">
                                        <span class="color-box" style="background-color: #371d74;"></span>
                                        <a class="a-list">Reserve Fund ( 40% )</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="split-table-buttons">
                            <tbody>
                                <tr>
                                    <td class="tdwid">
                                        <label for="radio-2024" class="button-year label-2024">2024</label>
                                    </td>
                                    <td class="tdwid">
                                        <label for="radio-2023" class="button-year label-2023">2023</label>
                                    </td>
                                    <td class="tdwid">
                                        <label for="radio-nft" class="button-year label-nft">NFT</label>
                                    </td>
                                    <td class="tdwid">
                                        <label for="radio-2022" class="button-year label-2022">2022</label>
                                    </td>
                                    <td class="tb-typofx cursor">
                                        <span>Typo FX</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>