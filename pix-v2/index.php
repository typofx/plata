<?php
session_start();

// Function to generate a random 6-digit code
function generateVerificationCode()
{
    return mt_rand(100000, 999999);
}

// Function to send the email with the verification code
function sendVerificationCode($email, $code)
{
    $subject = 'Plata Token';
    $message = 'Verification code : ' . $code;
    $headers  = "From: Typo FX <no-reply@plata.ie>\n";

    return mail($email, $subject, $message, $headers);
}

// Verifies if the verification code entered by the user is correct
function verifyCode($userCode, $expectedCode)
{
    return $userCode == $expectedCode;
}

// Checks if the resend limit has been exceeded
function checkResendLimit()
{
    if (!isset($_SESSION['resend_count'])) {
        $_SESSION['resend_count'] = 0;
    }
    $_SESSION['resend_count']++;

    return $_SESSION['resend_count'] < 3;
}

// Resets the verification process
function resetVerification()
{
    unset($_SESSION['email']);
    unset($_SESSION['verification_code']);
    unset($_SESSION['resend_count']);
    unset($_SESSION['code_sent']);
    unset($_SESSION['email_sent']);
}

function resetVerification2()
{

    unset($_SESSION['resend_count']);
    unset($_SESSION['code_sent']);
    unset($_SESSION['email_sent']);
}

// Checks if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_email']) && !isset($_SESSION['code_sent'])) {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $_SESSION['email'] = $email; // Stores the email in the session
            $_SESSION['verification_code'] = generateVerificationCode();
            $_SESSION['code_sent_time'] = time();
            sendVerificationCode($email, $_SESSION['verification_code']);
            $_SESSION['code_sent'] = true;
            echo 'Email sent successfully! Please check your inbox.';
        } else {
            echo 'Please enter a valid email address.';
        }
    } elseif (isset($_POST['confirm_code'])) {
        if (isset($_POST['verification_code'])) {
            $userCode = $_POST['verification_code'];
            $expectedCode = $_SESSION['verification_code'];
            if (verifyCode($userCode, $expectedCode)) {
                echo 'Verification code correct. Email confirmed!';
                resetVerification2(); // Clears the sessions
            } else {
                echo 'Incorrect verification code. Please try again.';
            }
        } else {
            echo 'Please enter the verification code.';
        }
    } elseif (isset($_POST['resend_code'])) {
        if (checkResendLimit()) {
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $_SESSION['verification_code'] = generateVerificationCode();
                $_SESSION['code_sent_time'] = time();
                sendVerificationCode($email, $_SESSION['verification_code']);
                echo 'Email resent successfully! Please check your inbox.';
            } else {
                echo 'An error occurred while resending the email. Please send the email again.';
            }
        } else {
            echo 'Resend limit exceeded. Please, we are resetting the process.';
            resetVerification(); // Clears the sessions
        }
    }
}
// Checks if the confirmation time has exceeded 3 minutes
if (isset($_SESSION['code_sent_time']) && time() - $_SESSION['code_sent_time'] > 300) {
    echo 'Time exceeded to enter the verification code. Please restart the process.';
    resetVerification(); // Clears the sessions
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
    <script>
        function copiar() {
            var copyText = document.getElementById("brcodepix");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            document.execCommand("copy");
            document.getElementById("clip_btn").innerHTML = '<i class="fas fa-clipboard-check"></i>';
        }

        function reais(v) {
            v = v.replace(/\D/g, "");
            v = v / 100;
            v = v.toFixed(2);
            return v;
        }

        function mascara(o, f) {
            v_obj = o;
            v_fun = f;
            setTimeout("execmascara()", 1);
        }

        function execmascara() {
            v_obj.value = v_fun(v_obj.value);
        }
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
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
    <form method="POST">

        <label for="email">Email:</label> <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required><br>
        <label for="verification_code">Verification Code:</label> <input type="number" id="verification_code" name="verification_code" max="999999" value="<?php echo $userCode  ?>"><br>
        <br>
        <button type="submit" name="send_email">Send Email</button>
        <button type="submit" name="resend_code">Resend Code</button>
        <button type="submit" name="confirm_code">Confirm Code</button>
    </form>



    <br>
   




                <form method="post" action="0xc298812164bd558268f51cc6e3b8b5daaf0b6341.php" target="_blank">
                    <?php
                    date_default_timezone_set('UTC');
                    $date = date("H:i:s T d/m/Y");
                    $Expdate = strtotime(date("H:i:s")) + 900; //15*60=900 seconds
                    $Expdate = date("H:i:s T d/m/Y", $Expdate);
                    $_POST["Expdate"] = $Expdate;
                    ?>
                    <div>
                        <label for="valor">Valor a Pagar (BRL)</label>
                    </div>
                    <div>
                        <input type="number" id="valorpix" name="valorpix" size="15" autocomplete="off" maxlength="13" step="0.001" min="0.001" value="<?= $_POST["valorpix"]; ?>" onkeyup="BRLexec()" onkeypress="BRLexec()" onclick="select()" onfocusout="addZeroBRL()" required>
                    </div>
                    <div>
                        <label for="PLTwanted">Tokens Plata Previstos (PLT)</label>
                    </div>
                    <div>
                        <input type="number" id="PLTwanted" name="PLTwanted" size="15" step="0.0001" autocomplete="off" maxlength="13" min="0.0001" value="<?= $_POST["PLTwanted"]; ?>" onkeyup="PLTexec()" onkeypress="PLTexec()" onclick="select()" required>
                    </div>
                    <div>

                    </div>
                    <div>
                        <input type="hidden" id="emailUser" name="emailUser" size="60" maxlength="90" value="<?php echo $_SESSION['email']; ?>" onclick="this.select();" required>
                    </div>


                    <div>
                        <label for="email">Carteira Web3 Polygon(MATIC)</label>
                    </div>
                    <div>
                        <input type="text" id="web3wallet" name="web3wallet" placeholder="0x..." size="60" maxlength="42" value="<?= $_POST["web3wallet"]; ?>" onclick="this.select();" onfocusout="isValidEtherWallet()" required>
                    </div>
                    <div style="display: none;">
                        <label for="identificador">Identificador</label>
                        <input type="text" id="identificador" name="identificador" value="<?php echo (rand(100, 999)); ?>" required>
                    </div>
                    <hr width="95%" />
                    <button id="submitButton" name="submit" onclick="checkemail()">Gerar QR Code <i class="fas fa-qrcode"></i></button>
                    <hr width="95%" />
                </form>
     
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

        function checkemail() {
            if (document.getElementById("emailUser").value != (document.getElementById("confirmemail").value)) document.getElementById("confirmemail").value = "";
        }
    </script>
    
    <div id="popup2" class="overlay">
	<div class="popup">
		<h1>Text01</h1>
		<a class="close" href="#">&times;</a>
	</div>

</div>
    
</body>

</html>