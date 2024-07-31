<?php

if (isset($_GET["token"])) {
    $token = $_GET["token"];

    
    include("conexao.php");


    $sql = "SELECT email FROM token WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
    
        $stmt->bind_result($email);
        if ($stmt->fetch()) {
          
            $checkEmailSql = "SELECT email FROM workers WHERE email = ?";
            $checkEmailStmt = $conn->prepare($checkEmailSql);
            $checkEmailStmt->bind_param("s", $email);
            $checkEmailStmt->execute();
            $checkEmailStmt->store_result();

            if ($checkEmailStmt->num_rows > 0) {
         
                $updateSql = "UPDATE workers SET status_confirmacao = 1 WHERE email = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("s", $email);

                if ($updateStmt->execute()) {
                    echo "Your email ($email) has been confirmed successfully!";
                } else {
                    echo "An error occurred while confirming your email. Please contact support.";
                }
            } else {
                echo "The email address ($email) is not registered.";
            }

            // Feche as conexões e declarações
            $checkEmailStmt->close();
            $updateStmt->close();
        } else {
            echo "Invalid token. Check the confirmation link.";
        }
    } else {
        echo "Invalid token. Check the confirmation link.";
    }

    
    $stmt->close();
    $conn->close();
} else {
    echo "Confirmation token not provided in URL.";
}
?>
