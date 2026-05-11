<?php include $_SERVER['DOCUMENT_ROOT'] . '/.scr/env.php'; ?>

<?php

$conn = new mysqli($BD_PASSWORD_SERVERNAME, $BD_PASSWORD_USERNAME, $BD_PASSWORD_PASSWORD);

if ($conn->connect_error) {
    echo "3rr0r";
    die("3rr0r : " . $conn->connect_error);
}

?>