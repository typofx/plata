<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include "conexao.php"; // Include database connection

// Function to convert month name to number (January -> 01, February -> 02, etc.)
function convertMonthToNumber($monthName)
{
    return date('m', strtotime($monthName)); // Convert month name to a number
}

// Function to generate a unique hash for the file name
// Function to generate a unique hash in the format 0000.AAAA+DATE
// Function to generate a unique hash in the format XXXXXXXXX.XXXXXXXX (16 mixed letters and numbers)
function generateHash()
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $hash = '';

    // Generate 16 random characters (mix of letters and numbers)
    for ($i = 0; $i < 16; $i++) {
        $hash .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    // Insert a dot after the 8th character to split the string into two 8-character parts
    return substr($hash, 0, 8) . '.' . substr($hash, 8, 8);
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
    $invoice = $_POST['invoice'];
    $vat = $_POST['vat'];

    // Handle PDF upload
    $pdf_path = $row['pdf_path']; // Keep the old PDF path if no new file is uploaded
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        // Define upload path
        $upload_dir =  'uploads/';
        $pdf_extension = pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION); // Get file extension
        $pdf_hash_name = generateHash($_FILES['pdf']['name']) . '.' . $pdf_extension; // Generate hash name
        $upload_file = $upload_dir . $pdf_hash_name;

        // Move the uploaded file to the server directory
        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $upload_file)) {
            $pdf_path = 'uploads/' . $pdf_hash_name; // Store the relative path
        } else {
            echo "Error uploading PDF.";
            exit;
        }
    }

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
                pdf_path = '$pdf_path',
                invoice ='$invoice',
                vat ='$vat',
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
    <title>Edit Expense</title>
</head>

<body>
    <h2>Edit Expense</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <!-- Hidden input to pass the ID -->
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <label for="created_at">Generated At:</label><br>
        <input type="datetime-local" id="created_at" name="created_at" value="<?php echo date('Y-m-d\TH:i', strtotime($row['created_at'])); ?>" required><br><br>

        <label for="date">Date:</label><br>
        <!-- Use the reassembled date in the correct format (YYYY-MM-DD) -->
        <input type="date" id="date" name="date" value="<?php echo $dateValue; ?>" required><br><br>

        <label for="good_service">Good/Service:</label><br>
        <input type="text" id="good_service" name="good_service" value="<?php echo $row['good_service']; ?>" required><br><br>

        <label for="invoice">Invoice:</label><br>
        <input type="text" id="invoice" name="invoice" value="<?php echo $row['invoice']; ?>" ><br><br>

        <label for="company">Company:</label><br>
        <input type="text" id="company" name="company" value="<?php echo $row['company']; ?>" required><br><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="paid" <?php if ($row['status'] == 'paid') echo 'selected'; ?>>Paid</option>
            <option value="processing" <?php if ($row['status'] == 'processing') echo 'selected'; ?>>Processing</option>
            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
        </select><br><br>

        <label for="vat">VAT Taxation:</label><br>
        <select id="vat" name="vat" >
            <option value="23" <?php if ($row['vat'] == '23') echo 'selected'; ?>>Standard rate 23%</option>
            <option value="13.5" <?php if ($row['vat'] == '13.5') echo 'selected'; ?>>1st Reduced rate 13.5%</option>
            <option value="9" <?php if ($row['vat'] == '9') echo 'selected'; ?>>2st Reduced rate 9%</option>
            <option value="4.8" <?php if ($row['vat'] == '4.8') echo 'selected'; ?>>SPR Reduced rate 13.5%</option>
            <option value="0" <?php if ($row['vat'] == '0') echo 'selected'; ?>>Zero-rated 0%</option>
        </select><br><br>

        <label for="cost_eur">Cost (EUR):</label><br>
        <input type="number" step="0.01" id="cost_eur" name="cost_eur" value="<?php echo $row['cost_eur']; ?>" required><br><br>

        <label for="eurusdt">EURUSD:</label><br>
        <input type="number" step="0.0000001" id="eurusdt" name="eurusdt" value="<?php echo $row['eurusdt']; ?>" required><br><br>
        <label for="pltusd">PLTUSD:</label><br>
        <input type="number" step="0.0000001" id="pltusd" name="pltusd" value="<?php echo $row['pltusd']; ?>" required><br><br>
        <label for="usdt">USDT:</label><br>
        <input type="number" step="0.0000001" id="usdt" name="usdt" value="<?php echo $row['usdt']; ?>" required><br><br>

        <label for="pdf">Upload PDF (Payment receipt):</label><br>
        <input type="file" id="pdf" name="pdf" accept=".pdf"><br>
        <?php if (!empty($row['pdf_path'])): ?>
            <small>Current file: <?php echo basename($row['pdf_path']); ?></small><br><br>
            <a href="delete_pdf.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete the PDF?');">Delete PDF</a><br><br>
        <?php else: ?>
            <br> <small><b>No PDF file uploaded.</b></small><br><br>
        <?php endif; ?>


        <input type="submit" value="Update Spend">
        <a href="index.php">[back]</a>
    </form>
</body>

</html>