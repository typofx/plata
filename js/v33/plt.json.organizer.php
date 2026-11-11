<pre>
<?php
// move os PLT.Y-m-d@H:i:s:UTC.json soltos em json/ para json/plata.token/Y/m/d/HH/
// pode rodar de novo: o robô ainda grava na pasta plana, cada execução pega o que acumulou
const JSON_DIR = '/home2/granna80/public_html/_www-typofx/.scr/library/json/';
const PLATA_DIR = JSON_DIR . 'plata.token/';

echo '<br>'. gmdate('d/m/y H:i:s.U') . ' UTC<br>' . "\n";

set_time_limit(0);

// ?dry só lista o que seria movido (as primeiras 50 linhas), sem mexer em nada
$dry = isset($_GET['dry']);
$moved = 0;
$skipped = [];
foreach (scandir(JSON_DIR, SCANDIR_SORT_NONE) as $file) {
    if (!preg_match('/^PLT\.(\d{4})-(\d{2})-(\d{2})@(\d{2})/', $file, $m)) {
        if (strpos($file, 'PLT') === 0) $skipped[] = $file;
        continue;
    }

    $dir = PLATA_DIR . "$m[1]/$m[2]/$m[3]/$m[4]H";
    if ($dry) {
        if ($moved < 50) echo "$file -> $dir\n";
        $moved++;
        continue;
    }

    if (!is_dir($dir)) mkdir($dir, 0755, true);

    if (rename(JSON_DIR . $file, "$dir/$file")) $moved++;
}

echo ($dry ? 'seriam movidos' : 'transfered'), ": $moved\n";
echo 'failed : ', count($skipped), "\n";
//print_r(array_slice($skipped, 0, 20));

echo '<br>'. gmdate('d/m/y H:i:s.U') . ' UTC<br>';
?>
</pre>
