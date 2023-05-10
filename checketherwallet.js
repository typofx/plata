//Code to check if provided data is a valid ether wallet or not

<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

<script>    
    function isValidEtherWallet(){
        let address = document.getElementById("txtEtherWallet").value;
        let result = Web3.utils.isAddress(address);
        console.log(result);  // => true?
    }  
</script>

<html>
    <head>
    </head>
    <body>
        <label for="fname">Ether Wallet:</label>
        <input type="text" id="txtEtherWallet" name="txtEtherWallet"><br><br>
        <button type="button" onclick="isValidEtherWallet()">Submit</button>
    </body>
</html>
