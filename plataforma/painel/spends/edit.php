<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include "conexao.php"; // Include database connection

// Function to convert month name to number (January -> 01, February -> 02, etc.)
function convertMonthToNumber($monthName) {
    return date('m', strtotime($monthName)); // Convert month name to a number
}

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the record
    $sql = "SELECT * FROM granna80_bdlinks.spends WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Get the data
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found!";
        exit;
    }

    // Reassemble the date from year, month, and day in the correct format (YYYY-MM-DD)
    $dateValue = $row['year'] . '-' . convertMonthToNumber($row['month']) . '-' . str_pad($row['day'], 2, '0', STR_PAD_LEFT); // Add leading zero to day if necessary
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // The ID of the record being edited
    $date = $_POST['date']; // Data no formato YYYY-MM-DD
    $year = date('Y', strtotime($date));
    $month = date('F', strtotime($date)); // English month name
    $day = date('d', strtotime($date));
    $good_service = $_POST['good_service'];
    $company = $_POST['company'];
    $status = $_POST['status'];
    $cost_eur = $_POST['cost_eur'];
    $eurusdt = $_POST['eurusdt'];
    $usdt = $_POST['usdt'];
    $pltusd = $_POST['pltusd'];
    $created_at = $_POST['created_at'];


    // Update the record in the database
    $sql = "UPDATE granna80_bdlinks.spends SET 
                year = '$year',
                month = '$month',
                day = '$day',
                good_service = '$good_service',
                company = '$company',
                status = '$status',
                cost_eur = '$cost_eur',
                usdt = '$usdt',
                pltusd = '$pltusd',
                eurusdt = '$eurusdt',
                created_at = '$created_at',
                updated_at = NOW()
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully!";
        // Redirect back to index.php after updating
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Spend</title>
</head>
<body>
    <h2>Edit Spend</h2>
    
    <form method="POST" action="">
        <!-- Hidden input to pass the ID -->
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <label for="created_at">Generated At:</label><br>
        <input type="datetime-local" id="created_at" name="created_at" value="<?php echo date('Y-m-d\TH:i', strtotime($row['created_at'])); ?>" required><br><br>


        <label for="date">Date:</label><br>
        <!-- Use the reassembled date in the correct format (YYYY-MM-DD) -->
        <input type="date" id="date" name="date" value="<?php echo $dateValue; ?>" required><br><br>

        <label for="good_service">Good/Service:</label><br>
        <input type="text" id="good_service" name="good_service" value="<?php echo $row['good_service']; ?>" required><br><br>

        <label for="company">Company:</label><br>
        <input type="text" id="company" name="company" value="<?php echo $row['company']; ?>" required><br><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="paid" <?php if ($row['status'] == 'paid') echo 'selected'; ?>>Paid</option>
            <option value="processing" <?php if ($row['status'] == 'processing') echo 'selected'; ?>>Processing</option>
            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
        </select><br><br>

        <label for="cost_eur">Cost (EUR):</label><br>
        <input type="number" step="0.01" id="cost_eur" name="cost_eur" value="<?php echo $row['cost_eur']; ?>" required><br><br>

        <label for="eurusdt">EURUSD:</label><br>
        <input type="number" step="0.0000001" id="eurusdt" name="eurusdt" value="<?php echo $row['eurusdt']; ?>" required><br><br>
        <label for="pltusd">PLTUSD:</label><br>
        <input type="number" step="0.0000001" id="pltusd" name="pltusd" value="<?php echo $row['pltusd']; ?>" required><br><br>
        <label for="usdt">USDT:</label><br>
        <input type="number" step="0.0000001" id="usdt" name="usdt" value="<?php echo $row['usdt']; ?>" required><br><br>

        <input type="submit" value="Update Spend">
    </form>
</body>
</html>
