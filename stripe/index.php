    <?php
    ob_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
    $price_content = ob_get_clean();
    ?>

<link rel="stylesheet" href="https://www.plata.ie/card/card-style.css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<html lang="">

<head>
</head>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <title>Checkout Form</title>

    <div style="display: none;">
        <?php echo $price_content; ?>
    </div>
    <!-- reCAPTCHA Enterprise Script -->
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
 

</head>

<body>
    <div id="boxApp" align="center">
        <div id="box" class="box">
            <h3>Plata Token by Card</h3>
            <div>
                
                <form id="myForm" method="post" action="checkout.php" onsubmit="return get_action();">
                    <?php
                    if (isset($_SESSION['email'])) {
                        echo htmlspecialchars($_SESSION['email']);
                    }
                    ?> 
                    
                    <div class="div-label"><label for="webWallet">E-mail:</label></div>
                    <div><input type="email" id="customer_email" name="customer_email" required></div>
                    
                    <div class="div-label"><label for="webWallet">EVM Wallet (Polygon Chain 137):</label></div>
                    <div><input type="text" id="web_wallet" name="web_wallet" onfocusout="isValidEtherWallet()" required></div>

                    <div class="div-label"><label for="amount">Payment Amount:</label></div>
                    <select class="select-list" id="currency" name="currency" onchange="calcFromCurrency()" required>
                        <option class="option" >USD</option>
                        <option class="option" >BRL</option>
                        <option class="option" >EUR</option> 
                    </select>
                    <input class="input-amount" type="tel" name="unit_amount_display" id="valor" autocomplete="off" maxlength="5" value="20.00" onfocusout="formatCurrency(this);" onkeyup="onValueChange(this);" required>
                    <input type="hidden" name="unit_amount" id="value_cents">
                    
                    <hr width="95%" class="hrline" />
                    <div class="div-label"><label for="PLTvalue">Predicted Plata Tokens (PLT):</label></div>
                    <div><input class="input" type="tel" id="PLTvalue" name="PLTvalue" maxlength="10" autocomplete="off" value="<?php echo number_format( (20 / $PLTUSD ), 4, '.', ','); ?>" onfocusout="formatPLT(this);" onkeyup="onPLTChange(this);" required></div>

                    <hr width="95%" class="hrline" />
                    <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0"> 
            
                    <hr width="95%" class="hrline" />
                    <div><button type="submit" class="buttonBuyNow" value="Checkout">Checkout</button></div>
                    <hr width="95%" class="hrline" />
                    <a href="https://www.plata.ie/?cancel=true">Cancel</a>
                    <br><br><br>
                </form>
 
            </div>
        </div>
        <br>
        <center><a id="dappVersion">PlataByCard dApp Version 1.0.0 (Beta)</a></center><br>

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

    <script>
        
        var _currency = "USD";
        var _selected = document.getElementById("currency").options;
        var _amount = parseFloat(20);
        const _USDPLT = parseFloat(<?php echo number_format($USDPLT, 15, '.', ','); ?>);
        const _PLTUSD = parseFloat(<?php echo number_format($PLTUSD, 15, '.', ','); ?>);
        const _USDEUR = parseFloat(<?php echo number_format($USDEUR, 15, '.', ','); ?>);
        const _USDBRL = parseFloat(<?php echo number_format($USDBRL, 15, '.', ','); ?>);
        
        
        //function atrAssets() {
        //    _USDPLT = parseFloat(<?php echo number_format($USDPLT, 15, '.', ','); ?>);
        //    _PLTUSD = parseFloat(<?php echo number_format($PLTUSD, 15, '.', ','); ?>);
        //    _USDEUR = parseFloat(<?php echo number_format($USDEUR, 15, '.', ','); ?>);
        //    _USDBRL = parseFloat(<?php echo number_format($USDBRL, 15, '.', ','); ?>);
        //}

        function calcFromCurrency() {
            //atrAssets();
            
            _currency = document.getElementById("currency").selectedIndex;
            _selected = document.getElementById("currency").options;
            
            var _pltValue = parseFloat(0);
            var _pltValueBackUp = document.getElementById("PLTvalue").value;


            var _amount = document.getElementById('valor').value;
            
            if ( isNaN(_amount) || (parseFloat(_amount) <= 0) || (parseFloat(_amount) > 5000) ) { 
                _amount = parseFloat( checkCurrencyAmount (_selected[_currency].text,_pltValueBackUp) );
                document.getElementById('valor').value = _amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".");
            } else {

                switch (_selected[_currency].text) {
                    case 'USD':
                        _pltValue = parseFloat(_amount) / parseFloat(_PLTUSD);
                        break;
                    case 'EUR':
                        _pltValue = ( parseFloat(_amount) / parseFloat(_USDEUR) ) / parseFloat(_PLTUSD);
                        break;
                    case 'BRL':
                        _pltValue = ( parseFloat(_amount) / parseFloat(_USDBRL) ) / parseFloat(_PLTUSD);
                        break;
                }
                console.log("calcFromCurrency() Index: " + _selected[_currency].index + " is " + _selected[_currency].text);
                document.getElementById('PLTvalue').value = _pltValue.toLocaleString('en-US', { minimumFractionDigits: 4, maximumFractionDigits: 4 }).replace(".", ".");
            }    

            if ( isNaN(_pltValue) || (parseFloat(_pltValue) <= 0) )  document.getElementById('PLTvalue').value = _pltValueBackUp.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".");            
                
        };
        
        
        function calcFromPLT() {
            //atrAssets();
            
            _currency = document.getElementById("currency").selectedIndex;
            _selected = document.getElementById("currency").options;
            
            var _amount = parseFloat(0);
            var _amountBackUp = document.getElementById("valor").value;


            var _pltValue = document.getElementById('PLTvalue').value;

            if ( isNaN(_pltValue) || (parseFloat(_pltValue) <= 0) ) { 
                
                _pltValue = parseFloat(checkCurrencyPLT(_selected[_currency].text,_amountBackUp) );
                document.getElementById('PLTvalue').value = _pltValue.toLocaleString('en-US', { minimumFractionDigits: 4, maximumFractionDigits: 4 }).replace(".", ".");
            } else {
                
 
                switch (_selected[_currency].text) {
                    case 'USD':
                        _amount = parseFloat(_pltValue * parseFloat(_PLTUSD) );
                        break;
                    case 'EUR':
                        _amount = parseFloat(_pltValue) * parseFloat(_PLTUSD) * parseFloat(_USDEUR);
                        break;
                    case 'BRL':
                        _amount = parseFloat(_pltValue) * parseFloat(_PLTUSD) * parseFloat(_USDBRL);
                        break;
                } 
                console.log("calcFromPLT() Index: " + _selected[_currency].index + " is " + _selected[_currency].text);
                document.getElementById('valor').value = _amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".");
            } 
                
                if ( isNaN(_amount) || (parseFloat(_amount) <= 0) )  document.getElementById('valor').value = _amountBackUp.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".");            
        }; 

        function formatCurrency(value) {
            //atrAssets();
            
            _currency = document.getElementById("currency").selectedIndex;
            _selected = document.getElementById("currency").options;
            
            var _pltValue = parseFloat(document.getElementById('PLTvalue').value);
            var _amount = parseFloat(document.getElementById('valor').value);
            
            if ( isNaN(_amount) || parseFloat(_amount) <= 0 || !parseFloat(_amount) ) { 
                 
                //document.getElementById('valor').value = checkCurrencyAmount(_selected[_currency].text,_pltValue);
                _amount = checkCurrencyAmount(_selected[_currency].text,_pltValue).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".");
                //alert("Index: " + _selected[_currency].index + " is " + _selected[_currency].text);
                document.getElementById('valor').value = _amount;

            } else if ( (_amount) != ( _amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".") ) ) {
                    document.getElementById('valor').value = _amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(".", ".");
                }
            calcFromCurrency();
            
        };
        
        function formatPLT(value) {
            //atrAssets();
            
            _currency = document.getElementById("currency").selectedIndex;
            _selected = document.getElementById("currency").options;
            
            var _pltValue = parseFloat(document.getElementById('PLTvalue').value);
            var _amount = parseFloat(document.getElementById('valor').value);
            
            
            if ( isNaN(_pltValue) || parseFloat(_pltValue) <= 0 || !parseFloat(_pltValue) ) { 
 
                document.getElementById('PLTvalue').value = checkCurrencyPLT(_selected[_currency].text,_amount);
                //alert("Index: " + _selected[_currency].index + " is " + _selected[_currency].text);
                _pltValue = checkCurrencyPLT(_selected[_currency].text,_amount).toLocaleString('en-US', { minimumFractionDigits: 4, maximumFractionDigits: 4 }).replace(".", ".");
                alert("Index: " + _selected[_currency].index + " is " + _selected[_currency].text);
                document.getElementById('PLTvalue').value = _pltValue;
                
            } else if ( (_pltValue) != ( _pltValue.toLocaleString('en-US', { minimumFractionDigits: 4, maximumFractionDigits: 4 }).replace(".", ".") ) ) {
                    document.getElementById('PLTvalue').value = _pltValue.toLocaleString('en-US', { minimumFractionDigits: 4, maximumFractionDigits: 4 }).replace(".", ".");
                }
            calcFromPLT();
        };
            

        
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
            calcFromCurrency();
            formatCurrency();
        }

        function onPLTChange(value) {
            calcFromPLT();
            formatPLT();
        }
        
        function checkCurrencyPLT(currency,value) {
            
            _currency = document.getElementById("currency").selectedIndex;
            _selected = document.getElementById("currency").options;
            
        switch (_selected[_currency].text) {
                    case 'USD':
                        _pltValue = parseFloat(value) / parseFloat(_PLTUSD);
                        break;
                    case 'USD':
                        _pltValue = ( parseFloat(value) / parseFloat(_USDEUR) ) / parseFloat(_PLTUSD);
                        break;
                    case 'USD':
                        _pltValue = ( parseFloat(value) / parseFloat(_USDBRL) ) / parseFloat(_PLTUSD);
                        break;
                }
        return _pltValue;
        
        }
        
        
        function checkCurrencyAmount(currency,pltValue) {

            _currency = document.getElementById("currency").selectedIndex;
            _selected = document.getElementById("currency").options;
            
        switch (_selected[_currency].text) {
                    case 'USD':
                        _amount = parseFloat(pltValue) * parseFloat(_PLTUSD);
                        break;
                    case 'USD':
                        _amount = parseFloat(pltValue) * parseFloat(_PLTUSD) * parseFloat(_USDEUR);
                        break;
                    case 'USD':
                        _amount = parseFloat(pltValue) * parseFloat(_PLTUSD) * parseFloat(_USDBRL);
                        break;
                } 
        return _amount;
        
        }
        
    </script>    
    

</body>

</html>