<? include ('/home2/granna80/%/env.php'); ?>

<?php
echo '<pre>';

ini_set('precision', 16);


if(isset($_GET[QSTBOT])) {

    $save = trim($_GET['save']);
    //echo $save . '<br>';
    
    if (strpos($save, '@') === false) {
        echo 'json(fail)';
        exit;
    }

    $clean_save = trim($save, '@');
    //echo $clean_save . '<br>';
    $parts = explode('@', $clean_save);

    // required structure: token @ ticker @ pc [@ circulating supply]
    if (count($parts) < 3) {
        echo 'json(fail)';
        exit;
    }

    $base_token = preg_replace('/[^a-zA-Z0-9_\-]/', '', $parts[0]);
    echo 'base_token: '. $base_token . '<br>';
    if (empty($base_token)) {
        echo 'json(fail)';
        exit;
    }

    $nmid = '';
    foreach (json_decode(file_get_contents(__DIR__ . '/assets.json'), true) as $asset) {
        if (strcasecmp($asset['contract'], $base_token) === 0) {
            $nmid = $asset['nmid'];
            break;
        }
    }
    echo 'nmid: '. $nmid . '<br>';
    if (empty($nmid)) {
        echo 'json(fail)';
        exit;
    }

    $ticker = preg_replace('/[^a-zA-Z0-9_\-]/', '', $parts[1]);
    if (empty($ticker)) {
        echo 'json(fail)';
        exit;
    }

    $raw_pc = trim($parts[2]);
    $clean_pc = str_replace('d', '.', $raw_pc);

    if (!is_numeric($clean_pc)) {
        echo 'json(fail)';
        exit;
    }

    $pc_num = (float)$clean_pc;
    if ($pc_num == 0.0) {
        echo 'json(fail)';
        exit;
    }

    $ft_str = (string)(1 / $pc_num);

    $data = [
        'dt' => gmdate('D, j F Y H:i:s') . ' UTC',
        'pc' => $clean_pc,
        'ft' => $ft_str
    ];

    if (isset($parts[3]) && trim($parts[3]) !== '') {
        $clean_mk_mult = str_replace('d', '.', trim($parts[3]));
        if (is_numeric($clean_mk_mult)) {
            $mk_val = (float)$clean_mk_mult * $pc_num;
            $data['mk'] = (string)$mk_val;
        }
    }

    $filepath = '/home2/granna80/public_html/_www-typofx/.scr/library/json/'. $nmid . '/'. $ticker . '.json';
    $json_output = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    if (file_put_contents($filepath, $json_output) !== false) {
        echo 'json(ok)';
    } else {
        echo 'json(fail)';
    }
} else {
    echo 'json(fail)';
}

echo '</pre>';

/*

/home2/granna80/public_html/_www-typofx/.scr/library/json/dai.stablecoin

0x8f3Cf7ad23Cd3CaDbD9735AFf958023239c6A063 dai.stablecoin *
0x18ec0A6E18E5bc3784fDd3a3634b31245ab704F6 monerium.eur *
0xE6A537a407488807F0bbeb0038B79004f19DDDFb brla.token *
polygon
plata.token
0x1BFD67037B42Cf73acF2047067bd4F2C47D9BfD6 wrap.bitcoin
0x7ceB23fD6bC0adD59E62ac25578270cFf1b9f619 wrap.ethereum
0x0d500b1d8e8ef31e21c99d1db9a6444d3adf1270 wrap.polygon
*/


