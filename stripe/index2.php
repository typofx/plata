<!DOCTYPE html>
<html>

<head>
    <title>Stripe Payment</title>
    <meta charset="UTF-8" />
</head>

<body>


    </head>

    <form id="myForm" method="post" action="checkout.php">
    <label for="customer_email">E-mail:</label>

    <input type="email" id="customer_email" name="customer_email" required>
    <br>

        <label for="unit_amount">Value:</label>
        <!-- The onchange event calls the formatCurrency function when the input value changes -->
        <input type="text" name="unit_amount_display" id="valor" onkeyup="formatCurrency(this)">
        <input type="hidden" name="unit_amount" id="value_cents">

<br>
        <label for="currency">Currency:</label><br>
        <select id="currency" name="currency" required>
            <option value="usd">USD</option>
            <option value="brl">BRL</option>
            <option value="eur">EUR</option>
        </select><br><br>

        <input type="submit" value="Checkout">
    </form>

    <script>
        function formatCurrency(value) {
            var formattedValue = value.value;
            formattedValue = formattedValue.replace(/\D/g, ""); // Remove all non-digits
            formattedValue = formattedValue.replace(/(\d+)(\d{2})$/, "$1,$2");
            formattedValue = formattedValue.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // Add dots every three digits
            value.value = formattedValue;
        }

        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var formattedAmount = document.getElementById('valor').value;
            var centsAmount = document.getElementById('valor').value; // Get the formatted value
            centsAmount = centsAmount.replace(/\D/g, ""); // Remove all non-digits
            centsAmount = parseFloat(centsAmount); // Convert to float
            console.log("Cents Amount:", centsAmount);
            document.getElementById('value_cents').value = centsAmount;
            this.submit(); // Submit the form after manipulating the value
        });
    </script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';?>




</body>

</html>