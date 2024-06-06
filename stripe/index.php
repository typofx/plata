<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <title>Checkout Form</title>
    <?php
    ob_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
    $price_content = ob_get_clean();

    ?>
    <div style="display: none;">
        <?php echo $price_content; ?>
    </div>
    <!-- reCAPTCHA Enterprise Script -->
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
 
    <script>
        function atrAssets() {
            _USDPLT = <?php echo number_format($USDPLT, 8, '.', ''); ?>;
            _PLTUSD = <?php echo number_format($PLTUSD, 8, '.', ''); ?>;
            _USDEUR = <?php echo number_format($USDEUR, 8, '.', ''); ?>;
            _USDBRL = <?php echo number_format($USDBRL, 8, '.', ''); ?>;
        }

        function calcFromCurrency() {
            atrAssets();
            const currency = document.getElementById('currency').value;
            const amountStr = document.getElementById('valor').value.replace(/[^0-9,.-]+/g, "").replace(",", ".");
            const amount = parseFloat(amountStr);
            let pltValue = 0;

            if (!isNaN(amount) && amount > 0) {
                switch (currency) {
                    case 'usd':
                        pltValue = amount / _PLTUSD;
                        break;
                    case 'eur':
                        pltValue = (amount / _USDEUR) / _PLTUSD;
                        break;
                    case 'brl':
                        pltValue = (amount / _USDBRL) / _PLTUSD;
                        break;
                }
                document.getElementById('PLTvalue').value = pltValue.toFixed(4);
            } else {
                document.getElementById('PLTvalue').value = "";
            }
        }

        function calcFromPLT() {
            atrAssets();
            const pltValueStr = document.getElementById('PLTvalue').value;
            const pltValue = parseFloat(pltValueStr);
            const currency = document.getElementById('currency').value;
            let amount = 0;

            if (!isNaN(pltValue) && pltValue > 0) {
                switch (currency) {
                    case 'usd':
                        amount = pltValue * _PLTUSD;
                        break;
                    case 'eur':
                        amount = pltValue * _PLTUSD * _USDEUR;
                        break;
                    case 'brl':
                        amount = pltValue * _PLTUSD * _USDBRL;
                        break;
                }

                document.getElementById('valor').value = amount.toLocaleString('de-DE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).replace(".", ",");
                document.getElementById('value_cents').value = (amount * 100).toFixed(0);
            } else {
                document.getElementById('valor').value = "";
                document.getElementById('value_cents').value = "";
            }
        }

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
            var centsAmount = formattedAmount.replace(/\D/g, ""); // Remove all non-digits
            centsAmount = parseFloat(centsAmount) / 100; // Convert to float
            console.log("Cents Amount:", centsAmount);
            document.getElementById('value_cents').value = centsAmount.toFixed(0); // Ensure it's a valid number
            this.submit(); // Submit the form after manipulating the value
        });


        
        function onValueChange(value) {
            formatCurrency(value);
            calcFromCurrency();
        }

        function onPLTChange(value) {
            calcFromPLT();
        }
    </script>
</head>

<body>
    <form id="myForm" method="post" action="checkout.php" onsubmit="return get_action();">
        <label for="customer_email">E-mail:</label>
        <input type="email" id="customer_email" name="customer_email" required>
        <br>

        <label for="webWallet">webWallet:</label>
        <input type="text" id="web_wallet" name="web_wallet" onfocusout="isValidEtherWallet()" required>
        <br>

        <label for="unit_amount">Value:</label>
        <input type="text" name="unit_amount_display" id="valor" onkeyup="onValueChange(this)" required>
        <input type="hidden" name="unit_amount" id="value_cents">
        <br>

        <label for="currency">Currency:</label>
        <select id="currency" name="currency" onchange="calcFromCurrency()" required>
            <option value="usd">USD</option>
            <option value="brl">BRL</option>
            <option value="eur">EUR</option>
        </select>
        <br><br>

        <label for="PLTvalue">Predicted Plata Tokens (PLT):</label>
        <input type="number" step="0.0001" id="PLTvalue" name="PLTvalue" onkeyup="onPLTChange(this)" required>
        <br><br>
        <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
        <div id="recaptcha-container" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey=""></div><br>
        <input type="submit" value="Checkout">
    </form>


<script>
        function isValidEtherWallet() {
            let address = document.getElementById("web_wallet").value;
            let result = Web3.utils.isAddress(address);
            if (result != true) document.getElementById("web_wallet").value = "";
            console.log(result); // => true?
        }
        function get_action() {
            // Check if reCAPTCHA is checked
            if (document.getElementById('recaptchaChecked').value === '1') {
                console.log("reCAPTCHA checked.");
                return true; // Allow form submission
            } else {
                console.log("reCAPTCHA not checked.");
                alert("Please check the reCAPTCHA.");
                return false; // Prevent form submission
            }
        }

        // Function to update reCAPTCHA state when checked
        function recaptchaChecked() {
            document.getElementById('recaptchaChecked').value = '1';
        }
    </script>

    
    

</body>

</html>
