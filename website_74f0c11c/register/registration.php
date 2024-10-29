<?php
include 'conexao.php';

// Include Google Authenticator classes
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $token = $_POST["token"];
    $name = $_POST["name"];
    $lastName = $_POST["last_name"];
    $birth = $_POST["birth"];
    $ddi = $_POST["ddi"];
    $phone = $_POST["phone"];
    $username = $name . '_' . $lastName;

    if (!$g->checkCode($secret, $token)) {
        echo "Invalid token. Registration cannot be completed.";
        exit();
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO granna80_bdlinks.granna_users (email, password, name, last_name, birth, ddi, phone, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssss", $email, $hashed_password, $name, $lastName, $birth, $ddi, $phone, $username);

        if ($stmt->execute()) {
            echo "Registration done successfully!";
        } else {
            echo "Error registering user: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }

    $conn->close();
} else {
    echo "The form was not submitted correctly.";
}
?>
