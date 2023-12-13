<html>
<body>

<?php

if ($_GET['q'] == "circulating") echo "<pre>11153825483</pre>";
    else if ($_GET['q'] == "totalcoins") echo "<pre>22299000992</pre>";
        else if ($_GET['q'] == "maxsupply") echo "<pre>22299000992</pre>";
                else if ($_GET['q'] == "contract") echo "<pre>0xc298812164bd558268f51cc6e3b8b5daaf0b6341</pre>";
                        else if ($_GET['q'] == "symbol") echo "<pre>PLT</pre>";
                            else if ($_GET['q'] == "chain") echo "<pre>Polygon Chain (137)</pre>";

        else echo "<pre>Unsupported query. See https://github.com/typofx/plata/ for documentation.
                    <br>
    example : https://www.plata.ie/api/socket.php?q=circulating
    
    contract    -> Plata Token Contract Address;
    circulating -> Circulating Supply;
    totalcoins  -> Total Supply;
    maxsupply   -> Max Supply;
    symbol      -> Project Ticker;
    chain       -> Token's Blockchain;

    </pre>";

?>

</body>
</html>