<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the reCAPTCHA response is set
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptchaSecretKey = ' TOKEN '; // Replace with your reCAPTCHA secret key
        $recaptchaResponse = $_POST['g-recaptcha-response'];

        // Send a POST request to reCAPTCHA verification endpoint
        $recaptchaVerifyURL = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = [
            'secret' => $recaptchaSecretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ];

        $recaptchaVerify = curl_init($recaptchaVerifyURL);
        curl_setopt($recaptchaVerify, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($recaptchaVerify, CURLOPT_POSTFIELDS, http_build_query($recaptchaData));
        $recaptchaResponseData = json_decode(curl_exec($recaptchaVerify), true);
        curl_close($recaptchaVerify);

        // Check if reCAPTCHA verification was successful
        if ($recaptchaResponseData['success']) {
            // Process the form data
            $projectName = $_POST['project_name'];
            $link = $_POST['link'];
            $type = $_POST['type'];
            $whatsapp = $_POST['whatsapp'];
            $telegram = $_POST['telegram'];
            $email = $_POST['email'];

          
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= 'From: ' . $email . "\r\n";

            $emailSubject = 'New Project Form Submission';
            $emailMessage = "<html><body>";
            $emailMessage .= "<h1>$emailSubject</h1>";
            $emailMessage .= "<p><strong>Project Name:</strong> $projectName</p>";
            $emailMessage .= "<p><strong>Link:</strong> $link</p>";
            $emailMessage .= "<p><strong>Type:</strong> $type</p>";
            $emailMessage .= "<p><strong>Whatsapp:</strong> $whatsapp</p>";
            $emailMessage .= "<p><strong>Telegram:</strong> $telegram</p>";
            $emailMessage .= "<p><strong>Email:</strong> $email</p>";
            $emailMessage .= "</body></html>";

            $toEmail = 'actm@plata.ie'; 
            if (mail($toEmail, $emailSubject, $emailMessage, $headers)) {
        
                echo 'Form sent successfully. Thanks!';
            } else {
                
                echo 'Error: Unable to send email. Please try again later.';
            }
        } else {
            
            echo 'Error: reCAPTCHA check failed. Please check the reCAPTCHA box.';
        }
    } else {
   
        echo 'Error: The reCAPTCHA response is missing. Please check the reCAPTCHA box.';
    }
} else {
   
    echo "<script>alert('Error: Invalid request method.');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>
