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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
          .camp {
        width: 350px; /* Defina a largura desejada para o campo */
        word-wrap: break-word; /* Quebra de palavras para evitar corte */
    }
    </style>

</head>
<body>
    <h2>Edit Documents - Form</h2>
    <a href="https://plata.ie/plataforma/painel/team/add.php">[Add new member]</a>
     <a href="https://plata.ie/plataforma/painel/menu.php">[Root Menu]</a>
     <a href="https://plata.ie/plataforma/painel/team/index.php">[Edit Team]</a>
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

        // SQL query to retrieve the teamName for the specific ID
        $sql_team = "SELECT teamName FROM granna80_bdlinks.team WHERE id = $id";
        $result_team = $conn->query($sql_team);

        if ($result_team->num_rows > 0) {
            $team_row = $result_team->fetch_assoc();
            $teamName = $team_row['teamName'];

            // SQL query to retrieve the data from team_docs for the specific teamName
            $sql_docs = "SELECT * FROM granna80_bdlinks.team_docs WHERE member_name = '$teamName'";
            $result_docs = $conn->query($sql_docs);

            if ($result_docs->num_rows > 0) {
                $row = $result_docs->fetch_assoc();
            } else {
                // Initialize empty fields if no record found
                $row = [
                    'defi_wallet' => '',
                    'cex_wallet' => '',
                    'binance_id' => '',
                    'binanceName' => '',
                    'cpf' => '',
                    'passport' => '',
                    'passport_photo' => '',
                    'pix' => '',
                    'location' => '',
                    'location_photo' => ''
                ];
            }
        } else {
            echo "No record found in team for ID: $id";
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
        
        <?php
        // Exibe o membro selecionado (já obtido)
        echo "<h3>Member: ". htmlspecialchars($teamName) ."</h3>";
        echo "<input type='hidden' value='". htmlspecialchars($teamName) ."' name='member_name' id='member_name'>";
        ?>

        <br><br>

        <label for="defi_wallet">DeFi Wallet:</label><br>
        <input type="text" name="defi_wallet" id="defi_wallet" class="camp" value="<?php echo htmlspecialchars($row['defi_wallet']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['defi_wallet']); ?>')"><i class="fa-solid fa-copy"></i></button>

        <br><br>

        <label for="cex_wallet">CEX Wallet:</label><br>
        <input type="text" name="cex_wallet" id="cex_wallet" class="camp" value="<?php echo htmlspecialchars($row['cex_wallet']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['cex_wallet']); ?>')"><i class="fa-solid fa-copy"></i></button>

        <br><br>

        <label for="binance_id">BinanceID:</label><br>
        <input type="text" name="binance_id" id="binance_id" value="<?php echo htmlspecialchars($row['binance_id']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['binance_id']); ?>')"><i class="fa-solid fa-copy"></i></button>

        <br><br>

        <label for="binanceName">BinanceNickname:</label><br>
        <input type="text" name="binanceName" id="binanceName" value="<?php echo htmlspecialchars($row['binanceName']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['binanceName']); ?>')"><i class="fa-solid fa-copy"></i></button>

        <br><br>

        <label for="cpf">CPF:</label><br>
        <input type="text" name="cpf" id="cpf" value="<?php echo htmlspecialchars($row['cpf']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['cpf']); ?>')"><i class="fa-solid fa-copy"></i></button>

        <br><br>

        <label for="passport">Passport (PDF):</label><br>
        <input type="text" name="passport_code" id="passport_code" value="<?php echo htmlspecialchars($row['passport']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['passport']); ?>')"><i class="fa-solid fa-copy"></i></button><br>
        <?php if (!empty($row['passport'])): ?>
            <a href="/images/uploads-docs/<?php echo htmlspecialchars($row['passport_photo']); ?>" target="_blank">View Passport PDF</a>
            <a href="/images/uploads-docs/<?php echo htmlspecialchars($row['passport_photo']); ?>" download>Download Location PDF</a>
        <?php endif; ?><br>
        <input type="file" name="passport" id="passport" accept=".pdf">

        <br><br>

        <label for="pix">Pix:</label><br>
        <input type="text" name="pix" id="pix" value="<?php echo htmlspecialchars($row['pix']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['pix']); ?>')"><i class="fa-solid fa-copy"></i></button>

        <br><br>

        <label for="location">Location:</label><br>
        <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($row['location']); ?>">
        <button type="button" onclick="copyTextToClipboard('<?php echo htmlspecialchars($row['location']); ?>')"><i class="fa-solid fa-copy"></i></button><br>
        <?php if (!empty($row['location_photo'])): ?>
            <a href="/images/uploads-docs/<?php echo htmlspecialchars($row['location_photo']); ?>" target="_blank">View Location PDF</a>
            <a href="/images/uploads-docs/<?php echo htmlspecialchars($row['location_photo']); ?>" download>Download Location PDF</a>
        <?php endif; ?><br>
        <input type="file" name="location_photo" id="location_photo" accept=".pdf">

        <br><br>

        <a href="../index.php">[Back]</a>
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
        $passport_code = $_POST['passport_code'];
        $pix = $_POST['pix'];
        $location = $_POST['location'];
        
        // File upload handling for passport and location photo
        $passport_photo = $row['passport_photo']; // keep the existing photo if not uploaded
        $location_photo = $row['location_photo']; // keep the existing photo if not uploaded

        if (!empty($_FILES['passport']['name'])) {
            $passport_file = $_FILES['passport']['name'];
            $target_dir = $file_path; // Adjust the target directory as needed
            $target_file = $target_dir . basename($_FILES["passport"]["name"]);

            // Check if file has been uploaded
            if(move_uploaded_file($_FILES["passport"]["tmp_name"], $target_file)) {
                $passport_photo = $passport_file;
                echo "<p>The file ". htmlspecialchars( basename( $_FILES["passport"]["name"])). " has been uploaded.</p>";
            } else {
                echo "<p>Sorry, there was an error uploading your Passport file.</p>";
            }
        }

        if (!empty($_FILES['location_photo']['name'])) {
            $location_file = $_FILES['location_photo']['name'];
            $target_dir = $file_path; // Adjust the target directory as needed
            $target_file = $target_dir . basename($_FILES["location_photo"]["name"]);

            // Check if file has been uploaded
            if(move_uploaded_file($_FILES["location_photo"]["tmp_name"], $target_file)) {
                $location_photo = $location_file;
                echo "<p>The file ". htmlspecialchars( basename( $_FILES["location_photo"]["name"])). " has been uploaded.</p>";
            } else {
                echo "<p>Sorry, there was an error uploading your Location Photo file.</p>";
            }
        }

        // Check if there is an existing record for the team member
        $sql_check_existing = "SELECT * FROM granna80_bdlinks.team_docs WHERE member_name = '$member_name'";
        $result_check_existing = $conn->query($sql_check_existing);

        if ($result_check_existing->num_rows > 0) {
            // Update existing record in team_docs table
            $sql_update = "UPDATE granna80_bdlinks.team_docs 
                           SET member_name='$member_name', defi_wallet='$defi_wallet', cex_wallet='$cex_wallet', 
                               binance_id='$binance_id', binanceName='$binanceName', cpf='$cpf', 
                               passport='$passport_code', passport_photo='$passport_photo', pix='$pix', 
                               location='$location', location_photo='$location_photo'
                           WHERE member_name = '$member_name'";

            if ($conn->query($sql_update) === TRUE) {
                echo "<p>Data updated successfully!</p>";
                echo '<script>window.location.href="edit.php?id='.$id.'";</script>';
            } else {
                echo "Error updating data: " . $conn->error;
            }
        } else {
            // Insert new record into team_docs table
            $sql_insert = "INSERT INTO granna80_bdlinks.team_docs (member_name, defi_wallet, cex_wallet, binance_id, binanceName, cpf, passport, passport_photo, pix, location, location_photo) 
                           VALUES ('$member_name', '$defi_wallet', '$cex_wallet', '$binance_id', '$binanceName', '$cpf', '$passport_code', '$passport_photo', '$pix', '$location', '$location_photo')";

            if ($conn->query($sql_insert) === TRUE) {
                echo "<p>New data inserted successfully!</p>";
                echo '<script>window.location.href="edit.php?id='.$id.'";</script>';
            } else {
                echo "Error inserting data: " . $conn->error;
            }
        }

        // Close connection
        $conn->close();
    }
    ?>
 <script>
    
        function copyTextToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    alert('Copied to clipboard!');
                })
                .catch(err => {
                    console.error('Error copying text: ', err);
                    alert('Failed to copy to clipboard.');
                });
        }
    </script>
</body>
</html>
