<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST["email"])) {
        $email = $_POST["email"];

      
        $token = bin2hex(random_bytes(16));

   
        include("conexao.php");

     
        $sql = "INSERT INTO token (email, token) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $token);

        if ($stmt->execute()) {
           
            $confirmation_url = "https://wedone.ie/email/confirmar_email.php?token=" . $token;

          
            $assunto = "Confirm your email address";
            $mensagem = "Click the following link to confirm your email address: $confirmation_url";
            $headers = "From: plata.ie";

            if (mail($email, $assunto, $mensagem, $headers)) {
                echo "A confirmation link has been sent to your email ($email). Check your inbox.";
            } else {
                echo "An error occurred while sending the confirmation link. Try again later.";
            }
        } else {
            echo "Error saving token to database.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "The email field is mandatory.";
    }
}
?>
