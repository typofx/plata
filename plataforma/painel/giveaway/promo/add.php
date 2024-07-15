<?php
// Include database connection file
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get promo code from the form
    $promo_code = $_POST['promo_code'];

    // SQL query to insert promo code into the table
    $sql = "INSERT INTO granna80_bdlinks.promo_codes (promo_code) VALUES ('$promo_code')";

    if ($conn->query($sql) === TRUE) {
        echo "Promo code added successfully!";
    } else {
        echo "Error adding promo code: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Add Promo Code</title>
</head>
<body>
    <h2>Add Promo Code</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="promo_code">Promo Code:</label><br>
        <input type="text" id="promo_code" name="promo_code" required><br><br>
        
        <a href="index.php">[back]</a>
        <input type="submit" value="Add">
    </form>
</body>
</html>
