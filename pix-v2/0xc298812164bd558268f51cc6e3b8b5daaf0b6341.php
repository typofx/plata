<?php
include

    $emailSubject = "Compra de Token Plata via PIX";
$emailUser = $_POST['emailUser'];

if ($emailUser == "") {
    echo "<script>window.close();</script>";
    die();
}

$headers = "From: noreply@plata.ie";

$valor_pix = number_format($_POST['valorpix'], 2, '.', ',');
$chave_pix = "pix@plata.ie";
$beneficiario_pix = "Adam Warlock Soares";
$cidade_pix = "São Vicente";
$web3wallet = $_POST['web3wallet'];
$identificador = $_POST['identificador'];
$gerar_qrcode = true;
$PLTwanted = number_format($_POST["PLTwanted"], 4, '.', ',');
$TXTdate = $_POST["TXTdate"];

date_default_timezone_set('UTC');
$date = date("H:i:s T d/m/Y");
$Expdate = strtotime(date("H:i:s")) + 900; // 15*60=900 seconds
$Expdate = date("H:i:s T d/m/Y", $Expdate);

$emailMessage =
    "Email do usuário: " . $emailUser . "\n" . "\n" .
    "Ordem Executada Em : " . $date . "\n" .
    "Valor Aguardado (BRL) : " . $valor_pix . "\n" .
    "Tokens Plata Previstas (PLT) : " . $PLTwanted . "\n" .
    "Web3 Wallet : " . $web3wallet . "\n" .
    "Chain ID : Polygon (137)" . "\n" .
    "Identificador : " . $identificador . "\n" .
    "Orçamento Válido Até : " . $Expdate;

mail($emailUser, $emailSubject, $emailMessage, $headers);
mail('salesdone@plata.ie', $emailSubject, $emailMessage, $headers);
mail('uarloque@live.com', $emailSubject, $emailMessage, $headers);

include "conexao.php"; // Inclui o arquivo com a configuração da conexão com o banco de dados

$date = date("Y-m-d H:i:s");
$bank = "PIX";
$plata = $PLTwanted;
$amount = $_POST['valorpix']; // Supondo que o valor PIX esteja disponível no formulário
$asset = "PLT";
$address = $web3wallet;
$txid = "polygon"; // Você pode definir isso conforme necessário
$email = $_POST['emailUser']; // Supondo que o email do usuário esteja disponível no formulário
$status = "pending"; // Você pode definir isso conforme necessário

// Consulta SQL para inserir os dados na tabela payments
$sql = "INSERT INTO granna80_bdlinks.payments (date, bank, plata, amount, asset, address, txid, email, status) 
        VALUES ('$date', '$bank', '$plata', $amount, '$asset', '$address', '$txid', '$email', '$status')";

// Executar a consulta SQL
if ($conn->query($sql) === TRUE) {
    $text1 = "Dados do pagamento inseridos com sucesso.";
} else {
    $text2 = "Erro ao inserir dados do pagamento: " . $conn->error;
}




include "phpqrcode/qrlib.php";
include "funcoes_pix.php";
$px[00] = "01"; // Payload Format Indicator, Obrigatório, valor fixo: 01
// Se o QR Code for para pagamento único (só puder ser utilizado uma vez), descomente a linha a seguir.
$px[01] = "12"; // Se o valor 12 estiver presente, significa que o BR Code só pode ser utilizado uma vez.
$px[26][00] = "br.gov.bcb.pix"; // Indica arranjo específico; “00” (GUI) obrigatório e valor fixo: br.gov.bcb.pix
$px[26][01] = $chave_pix;
if (!empty($descricao)) {
    /*
                    Não é possível que a chave pix e infoAdicionais cheguem simultaneamente a seus tamanhos máximos potenciais.
                    Conforme página 15 do Anexo I - Padrões para Iniciação do PIX  versão 1.2.006.
                    */
    $tam_max_descr = 99 - (4 + 4 + 4 + 14 + strlen($chave_pix));
    if (strlen($descricao) > $tam_max_descr) {
        $descricao = substr($descricao, 0, $tam_max_descr);
    }
    $px[26][02] = $descricao;
}
$px[52] = "0000"; // Merchant Category Code “0000” ou MCC ISO18245
$px[53] = "986"; // Moeda, “986” = BRL: real brasileiro - ISO4217
if ($valor_pix > 0) {
    // Na versão 1.2.006 do Anexo I - Padrões para Iniciação do PIX estabelece o campo valor (54) como um campo opcional.
    $px[54] = $valor_pix;
}
$px[58] = "BR"; // “BR” – Código de país ISO3166-1 alpha 2
$px[59] = $beneficiario_pix; // Nome do beneficiário/recebedor. Máximo: 25 caracteres.
$px[60] = $cidade_pix; // Nome cidade onde é efetuada a transação. Máximo 15 caracteres.
$px[62][05] = $identificador;
//   $px[62][50][00]="BR.GOV.BCB.BRCODE"; //Payment system specific template - GUI
//   $px[62][50][01]="1.2.006"; //Payment system specific template - versão
$pix = montaPix($px);
$pix .= "6304"; // Adiciona o campo do CRC no fim da linha do pix.
$pix .= crcChecksum($pix); // Calcula o checksum CRC16 e acrescenta ao final.
$linhas = round(strlen($pix) / 120) + 1;

?>
<link rel="stylesheet" href="pix-style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<style>
    .invisibled {
        font-size: 0px;
        text-align: center;
        border-style: none;
        resize: none;
        width: 0px;
        height: 0px;
    }

    table,
    td,
    th {
        border: 1px solid black;
    }

    .center {
        margin-left: auto;
        margin-right: auto;
    }
</style>
<script>
    function copiar() {
        var copyText = document.getElementById("brcodepix");
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        document.execCommand("copy");
        alert("Código PIX Copiado!");
    }
</script>
<br>
<div class="card">
    <center>
        <h2>Pedido Gerado</h2>
    </center>
    <center>Tokens Plata Reservados.<br>Aguardando Pagamento</center><br>
    <div class="row" style="">
        <div>
            <textarea id="brcodepix" class="invisibled" rows="<?= $linhas; ?>" cols="130"><?= $pix; ?></textarea>
        </div>
    </div>
</div>
<center>
    <?php
    ob_start();
    QRCode::png($pix, null, 'M', 5);
    $imageString = base64_encode(ob_get_contents());
    ob_end_clean();
    // Exibe a imagem diretamente no navegador codificada em base64.
    echo '<img src="data:image/png;base64,' . $imageString . '"></center>';
    ?>
    <br>
    <center>Um email foi enviado!</center>

    <br>
    <center><img src="https://www.plata.ie/images/pix-full-logo.svg"></center>
    <br>
    <div>
        <center><button type="button" id="clip_btn" class="buttonBuyNow" data-toggle="tooltip" data-placement="top" onclick="copiar()">Pix Copia e Cola<i class="fas fa-clipboard"></i></button></center>
    </div>

    <?php
    echo "<br>";
    echo "  Ordem Executada Em : " . $date . "<br>";
    echo "  Valor Aguardado (BRL) : " . $valor_pix . "<br><br>";

    echo "  Email : " . $emailUser . "<br>";
    echo "  Tokens Plata Previstas (PLT) : " . $PLTwanted . "<br>";
    echo "  Carteira Web3  : <br>";
    echo "  " . $web3wallet . "<br>";
    echo "  Rede ID : Polygon (137)" . "<br>";
    echo "  Identificador : " . $identificador . "<br>";
    echo "  Orçamento Válido Até : " . $Expdate . "<br>";
    ?>
    <div>
        <center><br>
            Abra o aplicativo do seu banco no celular.<br>
            Selecione a opção de pagar com PIX, ler QR code.<br>
            Você receberá Tokens Plata através da Polygon Chain (137) após confirmação do pagamento.<br>
        </center>
    </div>
    <br>




    </body>

    </html>


   