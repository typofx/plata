<?php
// Verifica se algum dado foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Itera sobre todos os dados enviados via POST
    foreach ($_POST as $key => $value) {
        echo $key . ': ' . $value . '<br>';
    
    }
    $unitAmount = $_POST["unit_amount_display"];
    

    echo $unitAmount = str_replace(['.', ','], '', $unitAmount);
} else {
    echo "Nenhum dado foi enviado via POST.";
}
?>
