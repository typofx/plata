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

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://www.plata.ie/sandbox/ux/split-mobile/mobile-split.css">
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
</head>

<body>
    <input class="radio-inv" type="radio" id="radio-2024" name="view" checked>
    <input class="radio-inv" type="radio" id="radio-2023" name="view">
    <input class="radio-inv" type="radio" id="radio-nft" name="view">
    <input class="radio-inv" type="radio" id="radio-2022" name="view">

    <div class="main-content">

        <div class="graph-content">
            <h1 class="token-title token-title-2024">Tokenomics (2024)</h1>
            <h1 class="token-title token-title-2023">Token Allocation (2023)</h1>
            <h1 class="token-title token-title-nft">NFT Marketplace</h1>
            <h1 class="token-title token-title-2022">Initial Split (2022)</h1>

            <div class="donut donut-2024">
                <div class="donut-background"></div>
                <div class="donut-hole"></div>
            </div>
            <div class="graph-view graph-view-2023">
                <img src="https://www.plata.ie/images/token-split-chart-2023.svg">
            </div>
            <div class="graph-view graph-view-nft">
                <img src="https://www.plata.ie/images/token-split-chart-nft.svg">
            </div>
            <div class="graph-view graph-view-2022">
                <img src="https://www.plata.ie/images/token-split-chart-2022.svg">
            </div>
        </div>
        <div class="legend">
            <ul class="token-list token-list-2024">
                <?php
                foreach ($data as $index => $item) {
                    $color = getColor($index, $colors);
                echo '<li class="token-item">
                    <span class="color-box" style="background-color: ' . $color . ';"></span><a>'.
                    $item['exchange'] . ' ( ' . $item['percentage'] * 100 . '% )</a></li>';
                }
                ?>
            </ul>
            <a> <ul class="token-list token-list-2023">
                <li class="token-item">
                    <span class="color-box" style="background-color: #72706f;"></span>
                    Null Address: 0x00...dEaD ( 49% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #dd137b;"></span>
                    Typo FX: Wallets ( 26% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #9d487c;"></span>
                    Uniswap V3 ( 5% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #7a7292;"></span>
                    Quickswap DEX ( 5% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #6b4f91;"></span>
                    SushiSwap V2 ( 5% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #622378;"></span>
                    Promotional Giveaway ( 5% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #969594;"></span>
                    AirDrop dApp ( 4% )
                </li>
            </ul> </a>
            <a> <ul class="token-list token-list-nft">
                <li class="token-item">
                    <span class="color-box" style="background-color: #6b4f91;"></span>
                    Artists who collaborated ( 20% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #622378;"></span>
                    Liquidity for ACTM Project ( 30% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #9d487c;"></span>
                    Rainfall's Victims in Brazil ( 50% )
                </li> 

            </ul> </a>
            <a><ul class="token-list token-list-2022">
                <li class="token-item">
                    <span class="color-box" style="background-color: #9d487c"></span>
                    Platform Operational Costs ( 10% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #622378;"></span>
                    Legal Support ( 20% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #6b4f91;"></span>
                    Decentralized Management ( 5% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #7a7292;"></span>
                    Expenses to Project Mentors ( 5% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #d8276e;"></span>
                    Marketing and Promotion ( 20% )
                </li>
                <li class="token-item">
                    <span class="color-box" style="background-color: #371d74;"></span>
                    Reserve Fund ( 40% )
                </li>
                
            </ul></a>
        </div>
        <div class="buttons-content">
            <label for="radio-2023" class="button label-2023">2023</label>
            <label for="radio-2024" class="button label-2024">2024</label>
            <label for="radio-nft" class="button label-nft">NFT</label>
            <label for="radio-2022" class="button label-2022">2022</label>
        </div>
    </div>
</body>

</html>