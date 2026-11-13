<?php

$rpcUrl = getenv('INFURA_POLYGON_URL') ?: trim(file_get_contents(__DIR__ . '/infura.url'));

// Reads balanceOf(pool) and decimals of both tokens of every pool in one Multicall3 tryAggregate call.
// One RPC request per read hits Infura's rate limit (429).
// Returns [pool id => [balance A, decimals A, balance B, decimals B]], null where a read failed.
function getTokenInfos($rows)
{
    global $rpcUrl;
    $word = fn($hex) => str_pad($hex, 64, '0', STR_PAD_LEFT);
    $address = fn($a) => preg_match('/^0x[0-9a-f]{40}$/i', trim($a)) ? substr(trim($a), 2) : '';

    $calls = [];
    foreach ($rows as $row) {
        foreach ([$row['contract_asset_a'], $row['contract_asset_b']] as $token) {
            $calls[] = [$address($token), '70a08231' . $word($address($row['contract']))];
            $calls[] = [$address($token), '313ce567'];
        }
    }

    $offsets = '';
    $tuples = '';
    foreach ($calls as [$target, $data]) {
        $offsets .= $word(dechex(32 * count($calls) + strlen($tuples) / 2));
        $tuples .= $word($target) . $word('40') . $word(dechex(strlen($data) / 2)) . str_pad($data, ceil(strlen($data) / 64) * 64, '0');
    }
    $callData = '0xbce38bd7' . $word('0') . $word('40') . $word(dechex(count($calls))) . $offsets . $tuples;

    $response = file_get_contents($rpcUrl, false, stream_context_create(['http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode(['jsonrpc' => '2.0', 'id' => 1, 'method' => 'eth_call', 'params' => [['to' => '0xcA11bde05977b3631167028862bE2a173976CA11', 'data' => $callData], 'latest']]),
    ]]));
    $result = substr(json_decode($response, true)['result'] ?? '', 2);
    $at = fn($i) => hexdec(substr($result, $i * 64, 64));

    $values = [];
    foreach ($calls as $i => $call) {
        $pos = 2 + $at(2 + $i) / 32;
        $values[] = $at($pos) && $at($pos + 2) == 32 ? $at($pos + 3) : null;
    }

    $infos = [];
    foreach ($rows as $i => $row) {
        $infos[$row['id']] = array_slice($values, $i * 4, 4);
    }
    return $infos;
}
