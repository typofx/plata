<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php

if (isset($_SESSION['order_saved'])) {
    $orderSaved = $_SESSION['order_saved'];
    echo "<script>alert('Ordem salva: $orderSaved');</script>";

    unset($_SESSION['order_saved']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
    
    <!-- DataTables CSS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS CDN -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 70px;
            background-color: #fff;
        }
        table.dataTable th,
        table.dataTable td {
            padding: 8px 12px;
            text-align: left;
        }
        table.dataTable th {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Methods</h1>
        <br>
        <br>
        <form id="orderForm" method="POST" action="save-order.php">
            <table id="paymentMethodsTable" class="display">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Icon</th>
                        <th>Description</th>
                        <th>Link</th>
                        <th>Visibled</th>
                        <th>Enabled</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'conexao.php';

                    $sql = "SELECT id, name, icon, description, link, visibled, enabled, `order-pay` FROM granna80_bdlinks.payment_methods";
                    $result = $conn->query($sql);

                    // Verificar se há resultados
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='order-num'>" . htmlspecialchars($row["order-pay"]) . "</td>";
                            echo "<td><input type='checkbox' name='selected_ids[]' value='" . htmlspecialchars($row["id"]) . "' data-id='" . htmlspecialchars($row["id"]) . "'></td>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td><img src='/images/" . htmlspecialchars($row["icon"]) . "' height='40'></td>";
                            echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($row["link"]) . "' target='_blank'>" . htmlspecialchars($row["name"]) . "</a></td>";
                            echo "<td>" . ($row["visibled"] ? 'Yes' : 'No') . "</td>";
                            echo "<td>" . ($row["enabled"] ? 'Yes' : 'No') . "</td>";
                            echo "<td><a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>edit</a>⠀<a href='delete.php?id=" . htmlspecialchars($row["id"]) . "'>delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No payment methods found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
            <input type="hidden" name="order" id="order">
            <br>
            <button type="submit">Save selected order</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#paymentMethodsTable').DataTable({
                "lengthMenu": [
                    [50, 100, 150, 250],
                    [50, 100, 150, 250]
                ],
                "columnDefs": [{
                    "width": "20px",
                    "targets": 0
                },
                {
                    "width": "20px",
                    "targets": 8
                }]
            });

            var selectedOrder = [];

            $('input[type="checkbox"]').on('change', function () {
                var id = $(this).data('id');

                if ($(this).is(':checked')) {
                    selectedOrder.push(id);
                } else {
                    selectedOrder = selectedOrder.filter(function (value) {
                        return value !== id;
                    });
                }

                $('#order').val(JSON.stringify(selectedOrder));
            });
        });
    </script>
</body>
</html>
