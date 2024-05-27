<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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


// Função para gerar um código de verificação aleatório de 6 dígitos
function generateVerificationCode()
{
    return mt_rand(100000, 999999);
}

// Função para enviar o e-mail com o código de verificação
function sendVerificationCode($email, $code)
{
    $subject = 'Plata Token';
    $message = 'Verification code: ' . $code;
    $headers = "From: Typo FX <no-reply@plata.ie>\n";
    return mail($email, $subject, $message, $headers);
}

// Verifica se o limite de reenvio foi excedido
function checkResendLimit()
{
    if (!isset($_SESSION['resend_count'])) {
        $_SESSION['resend_count'] = 0;
    }
    $_SESSION['resend_count']++;
    return $_SESSION['resend_count'] < 3;
}

// Reseta o processo de verificação
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
    unset($_SESSION['email']);
    unset($_SESSION['verification_code']);
    unset($_SESSION['resend_count']);
    unset($_SESSION['code_sent']);
    unset($_SESSION['email_sent']);
    unset($_SESSION['user']);
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_email']) || isset($_POST['resend_code'])) {
        if (isset($_POST['email']) || isset($_SESSION['email'])) {
            $email = $_POST['email'] ?? $_SESSION['email'];
            if (!isset($_SESSION['code_sent']) || checkResendLimit()) {
                $_SESSION['email'] = $email; // Armazena o e-mail na sessão
                $_SESSION['verification_code'] = generateVerificationCode();
                $_SESSION['code_sent_time'] = time();
                sendVerificationCode($email, $_SESSION['verification_code']);
                $_SESSION['code_sent'] = true;
                echo $email;
                
            } else {
                echo 'Resend limit exceeded. Please, we are resetting the process.';
                resetVerification2();// Limpa as sessões
                echo '<script type="text/javascript"> window.location.href = "https://www.plata.ie/pix3/index.php";</script>'; 
            }
        } else {
            echo 'Please enter a valid email address.';
        }
    } elseif (isset($_POST['verify_code'])) {
        if (isset($_POST['verification_code']) && $_POST['verification_code'] == $_SESSION['verification_code']) {
            echo '<form id="redirectForm" method="post" action="order.php">';
            echo '<input type="hidden" name="emailUser" value="' . $_SESSION['email'] . '">';
            resetVerification();
            echo '</form>';
            echo '<script>document.getElementById("redirectForm").submit();</script>';
        } else {
            echo 'Incorrect verification code. Please try again.';
            resetVerification(); // Limpa as sessões
        }
    }
}

// Verifica se o tempo para confirmação excedeu 5 minutos
if (isset($_SESSION['code_sent_time']) && time() - $_SESSION['code_sent_time'] > 300) {
    echo 'Time exceeded to enter the verification code. Please restart the process.';
    resetVerification(); // Limpa as sessões
}

// Verifica se há um erro na URL
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'incorrect_verification_code':
            resetVerification();
            echo "Código de verificação incorreto. Por favor, tente novamente.";
            break;
        default:
            resetVerification();
            echo "Ocorreu um erro desconhecido.";
            break;
    }
}

if (isset($_GET['cancel'])) {
    resetVerification2();
    echo '<script type="text/javascript"> window.location.href = "https://www.plata.ie/pix3/index.php";</script>';
}
if (isset($_GET['order_cancel'])) {
    resetVerification();
    echo '<script type="text/javascript"> window.location.href = "https://www.plata.ie";</script>';
}

if (!isset($_SESSION['code_sent'])) {
    echo '<form id="form1" method="POST">';
    echo '<label for="email">Email:</label>';
    echo '<input type="email" name="email" autocomplete="off" required><br><br>';
    echo '<button type="submit" name="send_email">Send Email</button>';
    echo '</form>';
}
echo '<form id="form2" method="POST">';
echo '<label for="verification_code">Verification Code:</label>';
echo '<input type="number" id="verification_code" name="verification_code" max="999999" autocomplete="off"><br>';
echo '<button type="submit" name="verify_code">Confirmar Email</button>';
echo '<button type="submit" name="resend_code">Reenviar Código</button>';
echo '<a href="?cancel=true">Cancelar</a>';
echo '</form>';


?>
<br>
