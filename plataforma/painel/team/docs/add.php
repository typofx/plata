<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Documents - Form</title>
</head>
<body>
    <h2>Add Documents - Form</h2>
    <?php $file_path = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-docs/'; ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
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
            while($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['teamName'] . '">' . $row['teamName'] . '</option>';
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
        <input type="text" name="defi_wallet" id="defi_wallet">

        <br><br>

        <label for="cex_wallet">CEX Wallet:</label><br>
        <input type="text" name="cex_wallet" id="cex_wallet">

        <br><br>

        <label for="binance_id">BinanceID:</label><br>
        <input type="text" name="binance_id" id="binance_id"><br>
        <label for="binance_id">BinanceNickname</label><br>
        <input type="text" name="binanceName" id="binanceName">
        <br><br>

        <label for="cpf">CPF:</label><br>
        <input type="text" name="cpf" id="cpf">

        <br><br>

        <label for="passport">Passport:</label><br>
        <input type="text" name="passport" id="passport">
        <input type="file" name="passport_photo" id="passport_photo">

        <br><br>

        <label for="pix">Pix:</label><br>
        <input type="text" name="pix" id="pix">

        <br><br>
        <a href="index.php">[Back]</a>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    // Form processing and database insertion
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
        $passport_photo = $_FILES['passport_photo']['name'];
        $target_dir = $file_path; // Adjust the target directory as needed
        $target_file = $target_dir . basename($_FILES["passport_photo"]["name"]);

        // Check if file has been uploaded
        if(move_uploaded_file($_FILES["passport_photo"]["tmp_name"], $target_file)) {
            echo "<p>The file ". htmlspecialchars( basename( $_FILES["passport_photo"]["name"])). " has been uploaded.</p>";
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }

        // SQL statement to insert data into team_docs table
        $sql_insert = "INSERT INTO granna80_bdlinks.team_docs (member_name, defi_wallet, cex_wallet, binance_id, cpf, passport, passport_photo, pix, binanceName)
                       VALUES ('$member_name', '$defi_wallet', '$cex_wallet', '$binance_id', '$cpf', '$passport', '$passport_photo', '$pix', '$binanceName')";

        if ($conn->query($sql_insert) === TRUE) {
            echo "<p>Data inserted successfully!</p>";
        } else {
            echo "Error inserting data: " . $conn->error;
        }

        // Close connection
        $conn->close();
    }
    ?>

</body>
</html>
