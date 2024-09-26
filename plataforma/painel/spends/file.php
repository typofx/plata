<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
require_once 'vendor/autoload.php';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Spend_receipt.pdf"');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Include the database connection
include 'conexao.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the query to get the spend data
    $stmt = $conn->prepare("SELECT * FROM granna80_bdlinks.spends WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any records were found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Start output buffering
        ob_start();
        ?>

        <h1>Spend Receipt</h1>
        
        <p><strong>Date:</strong> <?php echo $row['day'] . ' ' . $row['month'] . ' ' . $row['year']; ?></p>
        <p><strong>Good/Service:</strong> <?php echo $row['good_service']; ?></p>
        <p><strong>Company:</strong> <?php echo $row['company']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
        <p><strong>Cost (EUR):</strong> €<?php echo number_format($row['cost_eur'], 2); ?></p>
        <p><strong>USDT:</strong> $<?php echo number_format($row['cost_eur'] * $row['eurusdt'], 2); ?></p>
        <p><strong>Generated At:</strong> <?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></p>
        
      

        <?php
        // Get the HTML content from the buffer
        $html = ob_get_clean();

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Output the generated PDF
        $dompdf->stream(
            'Spend_receipt_' . $row['id'] . '.pdf',
            array('Attachment' => 1) // Change to 1 for download, 0 for inline view
        );

    } else {
        echo "No records found for this Spend";
    }

    $stmt->close();
} else {
    echo "Spend ID not provided.";
}

$conn->close();
?>
