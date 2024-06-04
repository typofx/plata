<?php
// Include the connection file if necessary
include 'conexao.php';

// Retrieve the payment ID from the URL
$id = $_GET['id'];

// Fetch the email address associated with the payment ID from the database
$sql = "SELECT email FROM granna80_bdlinks.payments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($email);
$stmt->fetch();

// Define email content
$subject = 'Plata Token';
$message = 'Your payment has been completed'; // Assuming $code is defined somewhere in your script
$headers = "From: Typo FX <no-reply@plata.ie>\r\n"; // Using \r\n for compatibility with various email servers

// Send the email
if (mail($email, $subject, $message, $headers)) {
    echo "Email sent successfully.";
    echo "<script>window.location.href = 'index.php';</script>";
} else {
    echo "Failed to send email.";
}
?>
