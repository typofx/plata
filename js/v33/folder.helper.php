<?php

// cria $_base_dir/ano/mes/dia/hora (ex.: 2026/10/22/16H), só o que ainda não existe
// uma pasta por hora: o ftp trava com ~10k arquivos numa pasta (25s/25s = 3600 por hora)
function create_date_dir()
{
    $dir = gmdate('Y/m/d/H\H');
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    return $dir;
}  create_date_dir();

?>
