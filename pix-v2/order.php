<?php
session_start();

// Verifica se a sessão está ativa e se a última atividade foi há menos de 5 minutos
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    // Se a última atividade foi há mais de 5 minutos, destrua a sessão e redirecione para a página inicial
    session_unset();     // remove todas as variáveis de sessão
    session_destroy();   // destrói a sessão
    header("Location: index.php");
    exit();
}

// Atualiza o timestamp da última atividade para o timestamp atual
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['user'])) {
    // Se a sessão não estiver iniciada, redireciona de volta para a página inicial
    header("Location: index.php");
    exit();
}


//echo $_SESSION['user'];

?>
<?php
session_start();

if (isset($_POST['emailUser'])) {
    $emailUser = $_POST['emailUser'];
    //echo 'Email recebido: ' . htmlspecialchars($emailUser);
} else {
    header("Location: email.php"); // Substitua "previous_page.php" pelo URL desejado
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <meta name="keywords" content="pix, qrcode pix, qr code, br code, brcode pix, pix copia e cola" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/0f8eed42e7.js" crossorigin="anonymous"></script>
    <style>
        .invisibled {
            font-size: 0px;
            text-align: center;
            border-style: none;
            resize: none;
            width: 0px;
            height: 0px;
        }
    </style>
    <title>Plata Token - Gerador de QR Code do PIX</title>
</head>

<body>
    <h3>Comprar Plata com Pix</h3>


    <br>

    <form id="form1" method="post" action="0xc298812164bd558268f51cc6e3b8b5daaf0b6341.php">
        <?php
        date_default_timezone_set('UTC');
        $date = date("H:i:s T d/m/Y");
        $Expdate = strtotime(date("H:i:s")) + 900; //15*60=900 seconds
        $Expdate = date("H:i:s T d/m/Y", $Expdate);
        $_POST["Expdate"] = $Expdate;
        ?>
        <div>
            <label for="email"><?php echo $emailUser;  ?></label><br>

            <input type="hidden" id="emailUser" name="emailUser" size="60" maxlength="90" value="<?php echo isset($_POST['emailUser']) ? $_POST['emailUser'] : ''; ?>" readonly required>

        </div>

        <div>
            <label for="valor">Valor a Pagar (BRL)</label><br>
        </div>
        <div>
            <input type="number" id="valorpix" name="valorpix" size="15" autocomplete="off" maxlength="13" step="0.01" min="1" value="<?= $_POST["valorpix"] ?? ''; ?>" onkeyup="BRLexec()" onkeypress="BRLexec()" onclick="select()" onfocusout="addZeroBRL()" required>
        </div>

        <div>
            <label for="PLTwanted">Tokens Plata Previstos (PLT)</label>
        </div>
        <div>
            <input type="number" id="PLTwanted" name="PLTwanted" size="15" step="0.0001" autocomplete="off" maxlength="13" min="1" value="<?= $_POST["PLTwanted"] ?? ''; ?>" onkeyup="PLTexec()" onkeypress="PLTexec()" onclick="select()" required>
        </div>



        <div>
            <label for="email">Carteira Web3 Polygon(MATIC)</label>
        </div>
        <div>
            <input type="text" id="web3wallet" name="web3wallet" placeholder="0x..." size="60" maxlength="42" value="<?= $_POST["web3wallet"] ?? ''; ?>" onclick="this.select();" onfocusout="isValidEtherWallet()" required>
        </div>
        <div style="display: none;">
            <label for="identificador">Identificador</label>
            <input type="text" id="identificador" name="identificador" value="<?php echo (rand(100, 999)); ?>" required>
        </div>

        <hr width="95%" />
        <button type="submit" name="verify_code">Gerar QR Code <i class="fas fa-qrcode"></i></button>
        <a href="https://www.plata.ie/pix3/email.php?order_cancel=true">Cancelar</a>
        <hr width="95%" />
    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleciona o formulário
            var form = document.getElementById('form2');

            // Adiciona um ouvinte de evento para quando o formulário for enviado
            form.addEventListener('submit', function(event) {
                // Define o atributo 'action' do formulário para 'index.php' antes de enviar
                form.action = '';
            });
        });
    </script>

    <script>
        //  document.addEventListener('DOMContentLoaded', (event) => {
        //   const form = document.getElementById('form1');

        //    const fields = [ 'valorpix', 'web3wallet'];

        // Carrega valores salvos do localStorage, se existirem
        //   fields.forEach(field => {
        //     const input = document.getElementById(field);
        //       const savedValue = localStorage.getItem(field);
        //        if (savedValue) {
        //            input.value = savedValue;
        //        }
        //     });

        // Salva valores no localStorage sempre que forem alterados
        //   form.addEventListener('input', (event) => {
        //       const input = event.target;
        //        if (fields.includes(input.id)) {
        //            let valueToSave = input.value;
        //
        // Formatar valor para BRL se for valorpix
        //     if (input.id === 'valorpix') {
        //           valueToSave = parseFloat(input.value).toFixed(2);
        //       }

        //      localStorage.setItem(input.id, valueToSave);
        //   }
        //   });
        // });
    </script>

    <?php include '../en/mobile/price.php'; ?>
    <br>
    <a id="dappVersion">PlataByPix dApp Version 0.1.3 (Beta)</a>
    <?php
    date_default_timezone_set('UTC');
    echo  $Expdate;
    ?>
    <script>
        document.getElementById("valorpix").value = "10.00";
        let _PLTBRL = <?php echo number_format($PLTBRL, 8, '.', ''); ?>;
        console.log("123: " + _PLTBRL);
        document.getElementById("txtCurrencyEnv").innerText = "(BRL)";
        document.getElementById("txtPAIR").innerText = "<?php echo $PLTBRL ?>";
        document.getElementById("tr-price").style.visibility = "collapse";

        function BRLexec() {
            let textBRL = Number(document.getElementById("valorpix").value);
            document.getElementById("PLTwanted").value = Number(textBRL / _PLTBRL).toFixed(4);
        }
        BRLexec();

        function addZeroBRL() {
            document.getElementById("valorpix").value = Number(document.getElementById("valorpix").value).toFixed(2);
        }

        function PLTexec() {
            let textPLT = Number(document.getElementById("PLTwanted").value);
            document.getElementById("valorpix").value = Number(textPLT * _PLTBRL).toFixed(2);
        }

        function isValidEtherWallet() {
            let address = document.getElementById("web3wallet").value;
            let result = Web3.utils.isAddress(address);
            if (result != true) document.getElementById("web3wallet").value = "";
            console.log(result); // => true?
        }
    </script>

</body>

</html>