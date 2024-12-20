<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wallet Connect</title>
  
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    
</head>
<body>
  <button onclick="connectWC()">Connect Wallet Connect</button>

  <script type="text/javascript">
    var account;

    // https://docs.walletconnect.com/quick-start/dapps/web3-provider
    var provider = new WalletConnectProvider.default({
      rpc: {
        1: "https://cloudflare-eth.com/", // https://ethereumnodes.com/
        137: "https://polygon-rpc.com/", // https://docs.polygon.technology/docs/develop/network-details/network/
        // ...

      },
      // bridge: 'https://bridge.walletconnect.org',
    });

    var connectWC = async () => {
      await provider.enable();

      //  Create Web3 instance
      const web3 = new Web3(provider);
      window.w3 = web3

      var accounts  = await web3.eth.getAccounts(); // get all connected accounts
      account = accounts[0]; // get the primary account
    }


    var sign = async (msg) => {
      if (w3) {
        return await w3.eth.personal.sign(msg, account)
      } else {
        return false
      }
    }

    var contract = async (abi, address) => {
      if (w3) {
        return new w3.eth.Contract(abi, address)
      } else {
        return false
      }
    }

    var disconnect = async () => {
      // Close provider session
      await provider.disconnect()
    }


    var address = "0x4b4f8ca8fb3e66b5ddafcebfe86312cec486dae1"
    var abi = [{"inputs":[],"name":"count","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"increment","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"nonpayable","type":"function"}]


  </script>

</body>
</html>