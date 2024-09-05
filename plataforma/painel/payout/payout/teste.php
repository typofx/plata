<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

// Função para gerar o PDF
function generatePdf($htmlContent) {
    $dompdf = new Dompdf();
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    return $dompdf->output();
}

// Função para enviar o email
function sendEmailWithPdf($to, $subject, $message, $pdfContent) {
    $boundary = md5(uniqid(time()));

    $headers = "From: seuemail@exemplo.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= "$message\r\n";

    $body .= "--$boundary\r\n";
    $body .= "Content-Type: application/pdf; name=\"documento.pdf\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"documento.pdf\"\r\n\r\n";

    $body .= chunk_split(base64_encode($pdfContent)) . "\r\n"; 
    $body .= "--$boundary--";

    return mail($to, $subject, $body, $headers);
}

// Dados do teste
$htmlContent = '<h1>Teste PDF</h1><p>Este é um PDF gerado para teste.</p>';
$to = 'softgamebr4@gmail.com';
$subject = 'Teste PDF Gerado';
$message = 'Aqui está o PDF gerado para teste.';

// Gera o PDF
$pdfContent = generatePdf($htmlContent);

// Salva o PDF no servidor (opcional, para verificar)
file_put_contents('teste.pdf', $pdfContent);

// Envia o email
if (sendEmailWithPdf($to, $subject, $message, $pdfContent)) {
    echo 'Email enviado com sucesso!';
} else {
    echo 'Erro ao enviar o email.';
}
?>
