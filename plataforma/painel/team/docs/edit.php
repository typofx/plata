<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Documents - Form</title>
</head>
<body>
    <h2>Edit Documents - Form</h2>
    
    <?php
    // Database connection (replace with your credentials)
    include 'conexao.php';
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-docs/';
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the ID from the URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        
        // SQL query to retrieve the data for the specific ID
        $sql = "SELECT * FROM granna80_bdlinks.team_docs WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "No record found for ID: $id";
            exit();
        }
    } else {
        echo "ID not provided.";
        exit();
    }

    // Close connection (will reconnect for form processing)
    $conn->close();
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label for="member_name">Select team member:</label>
        <?php
        // Database connection (replace with your credentials)
        include 'conexao.php';

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to retrieve team names
        $sql = "SELECT teamName FROM granna80_bdlinks.team";
        $result = $conn->query($sql);

        // If there are results, create the select dropdown
        if ($result->num_rows > 0) {
            echo '<select name="member_name" id="member_name" required>';
            echo '<option value="">Select a team member</option>';
            // Display each team name as an option
            while($team = $result->fetch_assoc()) {
                $selected = ($team['teamName'] == $row['member_name']) ? 'selected' : '';
                echo '<option value="' . $team['teamName'] . '" ' . $selected . '>' . $team['teamName'] . '</option>';
            }
            echo '</select>';
        } else {
            echo "No team members found.";
        }

        // Close connection
        $conn->close();
        ?>

        <br><br>

        <label for="defi_wallet">DeFi Wallet:</label><br>
        <input type="text" name="defi_wallet" id="defi_wallet" value="<?php echo $row['defi_wallet']; ?>">

        <br><br>

        <label for="cex_wallet">CEX Wallet:</label><br>
        <input type="text" name="cex_wallet" id="cex_wallet" value="<?php echo $row['cex_wallet']; ?>">

        <br><br>

        <label for="binance_id">BinanceID:</label><br>
        <input type="text" name="binance_id" id="binance_id" value="<?php echo $row['binance_id']; ?>"><br>
        <label for="binance_id">BinanceNickname</label><br>
        <input type="text" name="binanceName" id="binanceName" value="<?php echo $row['binanceName']; ?>">

        <br><br>

        <label for="cpf">CPF:</label><br>
        <input type="text" name="cpf" id="cpf" value="<?php echo $row['cpf']; ?>">

        <br><br>

        <label for="passport">Passport:</label><br>
        <input type="text" name="passport" id="passport" value="<?php echo $row['passport']; ?>"><br>
        <label for="passport_photo">Passport Photo:</label><br>
        <img src="/images/uploads-docs/<?php echo $row['passport_photo']; ?>" alt="Current Passport Photo" style="max-width:200px;"><br>
        <input type="file" name="passport_photo" id="passport_photo">

        <br><br>

        <label for="pix">Pix:</label><br>
        <input type="text" name="pix" id="pix" value="<?php echo $row['pix']; ?>">

        <br><br>
<a href="index.php">[Back]</a>
        <input type="submit" name="submit" value="Update">
    </form>

    <?php
    // Form processing and database update
    if (isset($_POST['submit'])) {
        // Database connection (replace with your credentials)
        include 'conexao.php';

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Form data
        $member_name = $_POST['member_name'];
        $defi_wallet = $_POST['defi_wallet'];
        $cex_wallet = $_POST['cex_wallet'];
        $binance_id = $_POST['binance_id'];
        $binanceName = $_POST['binanceName'];
        $cpf = $_POST['cpf'];
        $passport = $_POST['passport'];
        $pix = $_POST['pix'];
        
        // File upload handling for passport photo
        if (!empty($_FILES['passport_photo']['name'])) {
            $passport_photo = $_FILES['passport_photo']['name'];
            $target_dir = $file_path; // Adjust the target directory as needed
            $target_file = $target_dir . basename($_FILES["passport_photo"]["name"]);

            // Check if file has been uploaded
            if(move_uploaded_file($_FILES["passport_photo"]["tmp_name"], $target_file)) {
                echo "<p>The file ". htmlspecialchars( basename( $_FILES["passport_photo"]["name"])). " has been uploaded.</p>";
            } else {
                echo "<p>Sorry, there was an error uploading your file.</p>";
            }
        } else {
            $passport_photo = $row['passport_photo'];
        }

        // SQL statement to update data in team_docs table
        $sql_update = "UPDATE granna80_bdlinks.team_docs 
                       SET member_name='$member_name', defi_wallet='$defi_wallet', cex_wallet='$cex_wallet', 
                           binance_id='$binance_id', binanceName='$binanceName', cpf='$cpf', 
                           passport='$passport', passport_photo='$passport_photo', pix='$pix'
                       WHERE id = $id";

        if ($conn->query($sql_update) === TRUE) {
            echo "<p>Data updated successfully!</p>";
       echo '<script>window.location.href="edit.php?id='.$id.'";</script>';

        } else {
            echo "Error updating data: " . $conn->error;
        }

        // Close connection
        $conn->close();
    }
    ?>

</body>
</html>
