<?php
include 'conexao.php'; // Include the database connection file

if (isset($_GET['month']) && isset($_GET['employee_id'])) {
    $month_name = $_GET['month'];
    $employee_id = $_GET['employee_id'];

    // Convert the month name to the month number
    $month_number = date('m', strtotime($month_name));
    $year = date('Y'); // Current year

    // Adjust the year if the requested month is earlier than the current month
    if ($month_number > date('m')) {
        $year--;
    }

    // Function to calculate the number of weeks in a specific month from the database
    function getWeeksInMonthFromDatabase($conn, $month, $year, $employee_id) {
        // Calculate the first and last day of the month
        $start_of_month = "$year-$month-01";
        $end_of_month = date('Y-m-t', strtotime($start_of_month));

        $sql = "SELECT start_week, end_week FROM granna80_bdlinks.work_weeks WHERE employee_id = ? AND (status = 'Paid' OR status = 'Processing')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $weeks_count = 0;
        while ($row = $result->fetch_assoc()) {
            $start_week = new DateTime($row['start_week']);
            $end_week = new DateTime($row['end_week']);
            
            // Check if the week is within the month's range
            if ($start_week <= new DateTime($end_of_month) && $end_week >= new DateTime($start_of_month)) {
                $weeks_count++;
            }
        }

        return $weeks_count;
    }

    // Function to calculate the total payment for the month
    function calculateMonthlyInvoice($conn, $month_number, $year, $employee_id) {
        $weeks_in_month = getWeeksInMonthFromDatabase($conn, $month_number, $year, $employee_id);

        // Get the employee's payment rate
        $sql = "SELECT rate FROM granna80_bdlinks.payout WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rate = $row['rate'];

            // Calculate the total amount based on the number of weeks and the rate
            $total_amount = $weeks_in_month * $rate;

            // Convert the month number to the month name
            $month_name = strftime('%B', strtotime("$year-$month_number-01"));

            // Start of HTML output
            echo "<html><head><title>Monthly Invoice</title><style>
                    table { width: 100%; border-collapse: collapse; }
                    table, th, td { border: 1px solid black; }
                    th, td { padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                  </style></head><body>";
            echo "<h1>Monthly Invoice</h1>";
            echo "<p>Month: " . ucfirst($month_name) . " $year</p>"; // Displaying the month and year correctly
            echo "<p>Total Weeks: $weeks_in_month</p>";
            echo "<p>Weekly Rate: " . number_format($rate, 2) . "</p>";
            echo "<p>Total Amount: " . number_format($total_amount, 2) . "</p>";
            echo "</body></html>";
        } else {
            echo "No payment information found for the selected employee.";
        }

        $stmt->close();
    }

    calculateMonthlyInvoice($conn, $month_number, $year, $employee_id);

    $conn->close();
} else {
    echo "Month and employee ID not provided.";
}
?>
