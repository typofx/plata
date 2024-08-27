<?php
// Include the database connection
include 'conexao.php';

if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week = $_GET['week'];
    $employee_id = $_GET['employee_id'];

    // Specify the database before the table name
    $sql = "SELECT ww.*, p.employee, p.rate, p.pay_type 
            FROM granna80_bdlinks.work_weeks ww
            JOIN granna80_bdlinks.payout p ON ww.employee_id = p.id
            WHERE ww.work_week = ? AND ww.employee_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('si', $week, $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Receipt details
        $employee_name = $row['employee'];
        $start_date = date('d M Y', strtotime($row['start_week']));
        $end_date = date('d M Y', strtotime($row['end_week']));
        $status = $row['status'];
        $rate = $row['rate'];
        $pay_type = $row['pay_type'];

        // Calculate the total payment
        $total_payment = ($pay_type == 'Hourly') ? ($rate * $hours_worked) : $rate;

        // Display the receipt
        echo "<h1>Weekly Receipt</h1>";
        echo "<p><strong>Employee:</strong> $employee_name</p>";
        echo "<p><strong>Week:</strong> $week</p>";
        echo "<p><strong>Period:</strong> $start_date - $end_date</p>";
        echo "<p><strong>Status:</strong> $status</p>";
        echo "<p><strong>Payment Type:</strong> $pay_type</p>";
        echo "<p><strong>Rate:</strong> $rate</p>";
        echo "<p><strong>Total Payment:</strong> $total_payment</p>";
        
    } else {
        echo "No records found for this week and employee ID.";
    }

    $stmt->close();
} else {
    echo "Week or employee ID not provided.";
}

$conn->close();
?>
