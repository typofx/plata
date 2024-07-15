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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Edit Promo Code</title>
    <!-- Include Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div>
        <h2>Edit Promo Code</h2>
        <?php
        // Include database connection file
        include 'conexao.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if ID and new promo code are sent via POST
            if (isset($_POST['id']) && isset($_POST['promo_code'])) {
                $id = $_POST['id'];
                $promo_code = $_POST['promo_code'];

                // Prepare SQL statement to update promo code
                $sql = "UPDATE granna80_bdlinks.promo_codes SET promo_code = ? WHERE id = ?";
                
                // Prepare statement
                $stmt = $conn->prepare($sql);
                
                // Bind parameters
                $stmt->bind_param("si", $promo_code, $id);
                
                // Execute statement
                if ($stmt->execute()) {
                    echo '<p>Promo Code updated successfully!</p>';
                    echo '<script>window.location.href = "edit.php?id='.$id.'";</script>';
                } else {
                    echo '<p>Error updating promo code: ' . $stmt->error . '</p>';
                }

                // Close statement
                $stmt->close();
            } else {
                echo '<p>Promo code ID or new promo code not specified.</p>';
            }
        }

        // Check if ID is passed via GET
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // SQL query to get promo code data based on ID
            $sql = "SELECT id, promo_code FROM granna80_bdlinks.promo_codes WHERE id = $id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $promo_code = $row['promo_code'];
                ?>

                <!-- Form to edit the promo code -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <label for="promo_code">Promo Code:</label><br>
                    <input type="text" id="promo_code" name="promo_code" value="<?php echo $promo_code; ?>" required><br><br>
                    <button type="submit"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="index.php"><i class="fas fa-arrow-left"></i> Back</a>
                </form>

                <?php
            } else {
                echo '<p>Promo code not found.</p>';
            }
        } else {
            echo '<p>Promo code ID not specified.</p>';
        }

        // Close database connection
        $conn->close();
        ?>
    </div>

    <!-- Include jQuery for form functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
