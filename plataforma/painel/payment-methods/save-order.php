<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order = $_POST['order'] ?? '';

    if (!empty($order)) {
        $selectedOrder = json_decode($order, true);


        include 'conexao.php';

        function shiftOrders($conn, $fromValue) {

            $nextValue = $fromValue + 1;

   
            $checkStmt = $conn->prepare("SELECT id FROM granna80_bdlinks.payment_methods WHERE `order-pay` = ?");
            $checkStmt->bind_param("i", $nextValue);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {

                shiftOrders($conn, $nextValue);
            }

            
            $updateStmt = $conn->prepare("UPDATE granna80_bdlinks.payment_methods SET `order-pay` = ? WHERE `order-pay` = ?");
            $updateStmt->bind_param("ii", $nextValue, $fromValue);
            $updateStmt->execute();

            $checkStmt->close();
            $updateStmt->close();
        }

   
        $stmt = $conn->prepare("UPDATE granna80_bdlinks.payment_methods SET `order-pay` = ? WHERE id = ?");


        $orderValue = 1;
        foreach ($selectedOrder as $id) {

            shiftOrders($conn, $orderValue);

       
            $stmt->bind_param("ii", $orderValue, $id);
            $stmt->execute();
            $orderValue++;
        }

   
        $stmt->close();


        $conn->close();


        $_SESSION['order_saved'] = json_encode($selectedOrder);


        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        echo "Error.";
    }
} else {
    echo "Error.";
}
?>
