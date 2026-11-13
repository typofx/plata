<?php

function save_json_output($source_name, $prices_vs_usd, $usd_vs_prices, $plata_values)
{
    $json_dir = __DIR__ . '/json';

    // Criar diretório json/ se não existir
    if (!is_dir($json_dir)) {
        mkdir($json_dir, 0755, true);
    }

    // Montar estrutura do JSON
    $output = [
        'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
        'id' => $source_name . ' v3',
        'prices_vs_usd' => $prices_vs_usd,
        'usd_vs_prices' => $usd_vs_prices,
        'plt_prices' => $plata_values['plt_prices'],
        'plt_marketcap' => $plata_values['plt_marketcap']
    ];

    // Formato: all_pricesV3.{nome_provedora}.json
    $filename = $json_dir . '/all_pricesV3.' . $source_name . '.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

/**
 * Salva a saída JSON estatística (mean, median, midrange) no diretório json/
 * 
 * @param string $stat_name Nome da estatística (ex: 'mean', 'median', 'midrange')
 * @param array $prices_vs_usd Array de preços vs USD
 * @param array $usd_vs_prices Array de USD vs preços
 * @param array $plt_prices Array de preços PLT
 * @param array $plt_marketcap Array de marketcap PLT
 * @return string Caminho do arquivo salvo
 */
function save_stats_json_output($stat_name, $prices_vs_usd, $usd_vs_prices, $plt_prices, $plt_marketcap)
{
    $json_dir = __DIR__ . '/json';

    if (!is_dir($json_dir)) {
        mkdir($json_dir, 0755, true);
    }

    $output = [
        'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
        'id' => $stat_name . ' v3',
        'prices_vs_usd' => $prices_vs_usd,
        'usd_vs_prices' => $usd_vs_prices,
        'plt_prices' => $plt_prices,
        'plt_marketcap' => $plt_marketcap
    ];

    $filename = $json_dir . '/all_pricesV3.' . $stat_name . '.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

function save_json($_file_name, $_items)
{

    //$_files_name = 'coinpaprika_tokens_id';

    foreach ($_items as $_item) { //$entry
        [$ticker, $id] = explode(' : ', $_item, 2);
        $_map[$ticker] = $id; //sym....bol
    }

    $json_dir = __DIR__ . '/json';
    if (!is_dir($json_dir))
        mkdir($json_dir, 0755, true);


    file_put_contents(
        $json_dir . '/' . $_file_name . '.json',
        json_encode(['last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC', 'tokens_id' => $_map], JSON_PRETTY_PRINT)
    );


}

function save_json_token_output($source_name, $_ticker, $_data, $_token_name)
{
    $json_dir = __DIR__ . '/json/' . $_token_name . '/' . gmdate('Y/m/d/H') . 'H';

    if (!is_dir($json_dir))
        mkdir($json_dir, 0755, true);

    if (isset($_data['prices'])) {
        $first = $last = [];
        foreach ($_data['prices'] as $pair => $price) {
            if ($pair == $_ticker . $_ticker) continue;
            if (str_starts_with($pair, $_ticker)) $first[$pair] = $price;
            elseif (str_ends_with($pair, $_ticker)) $last[$pair] = $price;
        }
        $_data['prices'] = $first + $last;
    }

    $output = [
        'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
        'id' => $source_name . ' v3'
    ] + $_data;

    $filename = $json_dir . '/' . $_ticker . '.' . gmdate('Y-m-d@H:i:s:') . 'UTC.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

function save_json_plt_live_ticker($ticker, $plata_values)
{
    $json_dir = __DIR__ . '/json';
    if (!is_dir($json_dir . '/plata.token'))
        mkdir($json_dir . '/plata.token', 0755, true);

    $output = [
        'dt' => gmdate('D, j F Y H:i:s') . ' UTC',
        'pc' => strval(number_format($plata_values['plt_prices']['PLT' . $ticker], 8, '.', ',')),
        'ft' => strval(number_format($plata_values['plt_prices'][$ticker . 'PLT'], 4, '.', ',')),
        'mk' => strval(number_format($plata_values['plt_marketcap'][$ticker], 4, '.', ','))
    ];

    // Formato: all_pricesV3.{nome_provedora}.json
    $filename = $json_dir . '/plata.token/' . $ticker . '.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

function save_json_token_live_ticker($_ticker, $_token_value, $_token_name)
{
    $json_dir = __DIR__ . '/json';
    if (!is_dir($json_dir . '/' . $_token_name))
        mkdir($json_dir . '/' . $_token_name, 0755, true);

    // Montar estrutura do JSON
    $output = [
        'dt' => gmdate('D, j F Y H:i:s') . ' UTC',
        'pc' => strval(number_format($_token_value, 8, '.', ','))
    ];

    // Formato: all_pricesV3.{nome_provedora}.json
    $filename = $json_dir . '/' . $_token_name . '/' . $_ticker . '.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

function save_json_fiat_live_ticker($_ticker, $_pc, $_ft, $_fiat_name)
{
    $json_dir = __DIR__ . '/json/' . $_fiat_name;
    if (!is_dir($json_dir))
        mkdir($json_dir, 0755, true);

    $output = [
        'dt' => gmdate('D, j F Y H:i:s') . ' UTC',
        'pc' => strval(number_format($_pc, 8, '.', ',')),
        'ft' => strval(number_format($_ft, 8, '.', ','))
    ];

    $filename = $json_dir . '/' . $_ticker . '.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

function save_json_swift_status($_source_name, $_credits, $_status)
{

    $output = [
        'timestamp' => gmdate('d.m.Y H:i:s') . ' UTC',
        'api' => $_source_name,
        'balance' => $_credits,
        'status' => $_status
    ];

    // Formato: all_pricesV3.{nome_provedora}.json
    $json_dir = __DIR__ . '/json/status';
    if (!is_dir($json_dir))
        mkdir($json_dir, 0755, true);
    $filename = $json_dir . '/status.' . $_source_name . '.json';

    file_put_contents($filename, json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION));
    return $filename;
}

?>