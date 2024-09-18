<?php
// URL do arquivo JSON
$json_url = "https://www.plata.ie/plataforma/painel/token-historical-data/token_data.json";

// Buscar os dados do JSON
$json_data = file_get_contents($json_url);

// Decodificar o JSON para um array PHP
$data = json_decode($json_data, true);

// Limitar os registros a 180
$data = array_slice($data, 0, 180);

// Inverter a ordem dos dados para que as datas mais recentes sejam processadas primeiro
$data = array_reverse($data);

// Variáveis para montar os pontos do gráfico
$filtered_data = array_filter($data, function ($value) {
    return $value['price'] > 0;
});

$min_price = PHP_INT_MAX;
$max_price = PHP_INT_MIN;
$oldest_date = null;
$latest_date = null;

// Encontrar o preço mínimo e máximo para ajustar a escala do gráfico
foreach ($data as $point) {
    $price = $point['price'];
    if ($price > 0) {
        if ($price < $min_price)
            $min_price = $price;
        if ($price > $max_price)
            $max_price = $price;
    }

    // Definir a data mais antiga e a mais recente
    if (!$latest_date) {
        $latest_date = $point['date'];
    }
    $oldest_date = $point['date'];
}

// Extrair apenas a data
$old_date = strtotime(explode(' ', $oldest_date)[0]);
$last_date = strtotime(explode(' ', $latest_date)[0]);

// Inicializar um contador de dias
$days_to_next = 0;

// Loop para encontrar o proximo dia
while (true) {
    // Incrementar 1 dia
    $last_date = strtotime("+1 day", $last_date);
    $current_day = date('d', $last_date);

    // Se encontrar o dia, encerra o loop
    if ($current_day == date('d', $old_date)) {
        break;
    }
    // Incrementar o contador de dias
    $days_to_next++;
}

$extra_points = $days_to_next;

$num_points = count($filtered_data) + 1 + $extra_points;
$graph_width = 100;
$graph_height = 70;

// Ajustando os limites do grafico
$price_range = ($max_price - $min_price) * 1.4;
$graph_height = max($graph_height, min($graph_height, $price_range * 10));
$x_step = $graph_width / ($num_points - 1);

$num_points_adjusted = count($filtered_data) + 1;
$x_step_adjusted = $graph_width / ($num_points_adjusted - 1);

// Montar o atributo 'd' para o path, convertendo os preços em coordenadas Y
$x = 0;
$y_offset = -10;
$path_d = "M";
foreach ($filtered_data as $point) {
    $price = $point['price'];
    $scaled_y = $graph_height - ($price - $min_price) / $price_range * $graph_height + $y_offset;
    $path_d .= "$x,$scaled_y ";
    $x += $x_step_adjusted;
}

// Grid Horizontal
$grid_lines = "";
$num_lines = 9;
$line_spacing = $graph_height / ($num_lines - 1);

for ($i = 0; $i < $num_lines; $i++) {
    $y = $i * $line_spacing;
    $grid_lines .= "<line x1='0' y1='$y' x2='$graph_width' y2='$y' class='horizontal' />\n";
}

$start_date = strtotime($oldest_date);
$end_date = strtotime($latest_date);
// Quantidade de meses no intervalo de tempo
$diff_in_days = ($start_date - $end_date) / (60 * 60 * 24);
// arredondando a quantidade de meses
$num_months = round($diff_in_days / 30);
$months = [];

// Iterando sobre os meses
$months = [];
for ($i = $num_months; $i >= 0; $i--) {
    if ($i == $num_months || $i == 0) {
        $current_month = date("d-M", strtotime("-$i month", $start_date));
    } else {
        $current_month = date("M", strtotime("-$i month", $start_date));
    }
    $months[] = $current_month;
}

$grid_lines_vertical = "";

// Espaçamento total disponível
$total_width = $graph_width - $extra_points * $x_step;

// Número total de intervalos (número de linhas - 1)
$num_intervals = $num_months;

// Calcular o espaçamento uniforme
$uniform_x_step = $total_width / $num_intervals;

// Adicionar a primeira linha com o espaço extra
$grid_lines_vertical .= "<line x1='0' y1='0' x2='0' y2='$graph_height' class='vertical' />\n";
$x = $extra_points * $x_step;
$grid_lines_vertical .= "<line x1='$x' y1='0' x2='$x' y2='$graph_height' class='vertical' />\n";

// Adicionar as linhas restantes com espaçamento uniforme
for ($i = 1; $i <= $num_months; $i++) {
    $x = $extra_points * $x_step + $i * $uniform_x_step;
    $grid_lines_vertical .= "<line x1='$x' y1='0' x2='$x' y2='$graph_height' class='vertical' />\n";
}

// Linha do gráfico para não ultrapassar o valor limite
$dasharray = max(150, $graph_width / 2) + ($num_points * 2);

// Cálculo da variação percentual entre o registro mais recente e o mais antigo
$oldest_price = $data[0]['price']; // Preço mais antigo (último na lista original)
$latest_price = $data[count($data) - 1]['price']; // Preço mais recente (primeiro na lista original)

if ($oldest_price > 0) {
    $percentage_change = (($latest_price - $oldest_price) / $oldest_price) * 100;
} else {
    $percentage_change = 0; // Evitar divisão por zero
}
//echo $percentage_change;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
    <title>Graph</title>
    <link rel="stylesheet" href="https://www.plata.ie/sandbox/ux/graph/style.css">
</head>

<body>
    <div class="charts-container cf">
        <div class="chart" id="graph-1-container">
            <div class="title-container">
                <h2 class="title">Plata (PLTUSDT)</h2>
                <!-- Variação porcentual -->
                <div class="percentage-change <?php echo ($percentage_change < 0) ? 'negative' : 'positive'; ?>">
                    <span><?php echo ($percentage_change > 0) ? '+' : ''; ?><?php echo number_format($percentage_change, 2); ?>%</span>
                </div>
            </div>
            <div class="chart-svg">
                <!-- Legenda dos preços -->
                <div class="price-legend">
                    <text class="legend-y max">$<?php echo number_format($max_price, 8); ?></text>
                    <text class="legend-y min">$<?php echo number_format($min_price, 8); ?></text>
                </div>
                <!-- Ajuste dinâmico do gráfico -->
                <svg class="chart-line" id="chart-1"
                    viewBox="0 0 <?php echo $graph_width + 5 ?> <?php echo $graph_height + 5 ?>">
                    <defs>
                        <clipPath id="clip" x="0" y="0" width="<?php echo $graph_width ?>"
                            height="<?php echo $graph_height ?>">
                            <rect id="clip-rect" x="-10" y="0" width="10" height="10" />
                        </clipPath>

                        <!-- Definição do gradiente -->
                        <linearGradient id="gradient-1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#c8c8c8" />
                            <stop offset="100%" stop-color="pink" />
                        </linearGradient>
                    </defs>

                    <g id="grid">
                        <?php echo $grid_lines; ?>
                        <?php echo $grid_lines_vertical; ?>
                    </g>

                    <!-- Linha do gráfico com gradiente aplicado ao stroke -->
                    <path id="graph-1" d="<?php echo $path_d; ?>" stroke="url(#gradient-1)" stroke-width="1"
                        fill="transparent" stroke-dasharray="<?php echo $dasharray; ?>"
                        stroke-dashoffset="<?php echo $dasharray; ?>" />
                </svg>

                <!-- Legenda da direita -->
                <div class="price-legend-right">
                    <?php
                  
                    $price_steps = [
                        $max_price * 1.2,                         // 1. HIGHER PRICE * 1.2
                        $max_price,                               // 2. HIGHER PRICE
                        ($max_price + $min_price) * 0.8,          // 3. (HIGHER PRICE + LOWER PRICE) * 0.8
                        ($max_price + $min_price) * 0.6,          // 4. (HIGHER PRICE + LOWER PRICE) * 0.6
                        ($max_price + $min_price) * 0.5,          // 5. (HIGHER PRICE + LOWER PRICE) * 0.5
                        ($max_price + $min_price) * 0.4,          // 6. (HIGHER PRICE + LOWER PRICE) * 0.4
                        ($max_price + $min_price) * 0.2,          // 7. (HIGHER PRICE + LOWER PRICE) * 0.2
                        $min_price,                               // 8. LOWER PRICE
                        $min_price * 0.8                          // 9. LOWER PRICE * 0.8
                    ];

                    for ($i = 0; $i < $num_lines; $i++):
                        $top_position = ($i / ($num_lines - 1)) * 100;
                        $price_step_value = isset($price_steps[$i]) ? $price_steps[$i] : 0;
                    ?>
                        <h3 class="price-step" style="position: absolute; top: <?php echo $top_position; ?>%;">
                            $<?php echo number_format($price_step_value, 8); ?>
                        </h3>
                    <?php endfor; ?>
                </div>

                <!-- Legenda das datas -->
                <div class="time-legend">
                    <?php foreach ($months as $month): ?>
                        <h3 class="time-month" style="width: <?php echo 100 / count($months); ?>%; display: inline-block;">
                            <?php echo $month; ?>
                        </h3>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>