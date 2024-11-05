<?php 
session_start();

if (!isset($_SESSION['user_email'])) {
    echo "<p style='color: red;'>Session expired. Please come back and enter your email.</p>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

include 'conexao.php';

// Function to generate a unique deposit code
function generateDepCode($conn) {
    do {
        // Generate a random code with 5 uppercase letters, 3 numbers, and 1 uppercase letter
        $code = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)) .
                substr(str_shuffle("0123456789"), 0, 3) .
                strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1));

        // Check if the code already exists in the database
        $sql = "SELECT dep_code FROM granna80_bdlinks.granna_users WHERE dep_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();
        $isUnique = $stmt->num_rows == 0;
        $stmt->close();
    } while (!$isUnique);

    return $code;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_SESSION['user_email'];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $lastName = $_POST["last_name"];
    $birth = $_POST["birth"];
    $ddi = $_POST["ddi"];
    $phone = $_POST["phone"];
    $username = $name . '_' . $lastName;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate the unique deposit code
    $dep_code = generateDepCode($conn);

    $sql = "INSERT INTO granna80_bdlinks.granna_users (name, last_name, email, birth, ddi, phone, password, username, dep_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $lastName, $email, $birth, $ddi, $phone, $hashed_password, $username, $dep_code);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Registration completed successfully!</p>";
        session_unset();
        session_destroy();
        // echo "<script>window.location.href = 'welcome.php';</script>";
    } else {
        echo "<p style='color: red;'>Error registering. Please try again.</p>";
    }

    $stmt->close();
    $conn->close();
}
