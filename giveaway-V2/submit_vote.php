<?php
include "conexao.php"; 



ob_start(); include '../en/mobile/price.php'; ob_end_clean();

//echo "1 Matic to Plata (PLT): $MATICPLT";  



if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Access denied.");
}

// Check if expected data was received
if (!isset($_POST['evm_wallet']) || !isset($_POST['vote_number'])) {
    die("Missing form data.");
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $evm_wallet = $_POST['evm_wallet'];
    $vote_number = $_POST['vote_number'];
    
    // Check if EVM address format is valid
    if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $evm_wallet)) {
        echo "Invalid EVM address.";
        die();
    }



    // Check if the address already exists in the database
    $check_sql = "SELECT COUNT(*) AS count FROM granna80_bdlinks.votes WHERE evm_wallet = '$evm_wallet'";
    $result = $conn->query($check_sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["count"] > 0) {
            echo "This Ethereum wallet address already exists in the database.";
            die();
        }
    } else {
        echo "Error checking database for existing wallet address.";
        die();
    }

    // Process image upload
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo($_FILES["vote_image"]["name"], PATHINFO_EXTENSION));
    $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
    $target_file = $target_dir . $unique_filename;

    if (!isset($_FILES["vote_image"]) || $_FILES["vote_image"]["size"] == 0) {
        $target_file = "platatoken400px.png";
    } else {
        $check = getimagesize($_FILES["vote_image"]["tmp_name"]);
        if($check === false) {
            echo "File is not an image.";
            die();
        }
        if ($_FILES["vote_image"]["size"] > 10000000) { // 10MB
            echo "Sorry, your file is too large.";
            die();
        }
        if (!move_uploaded_file($_FILES["vote_image"]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            die();
        }
    }

    // Insert data into the table
    $sql = "INSERT INTO granna80_bdlinks.votes (evm_wallet, vote_image, vote_number, MATICPLT, MATICUSD) VALUES ('$evm_wallet', '$target_file', '$vote_number', '$MATICPLT', '$MATICUSD')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<script>
    $(document).ready(function() {
        // Function to validate Ethereum address
        function isValidEtherWallet() {
            let address = $("#evm_wallet").val();
            let result = web3.utils.isAddress(address);
            if (!result) {
                alert("Invalid Ethereum address.");
            }
        }

        // Calling the validation function when the field loses focus
        $("#evm_wallet").blur(function() {
            isValidEtherWallet();
        });
    });
</script>
