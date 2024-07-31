<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wallet Connect</title>
  <script src="https://cdn.jsdelivr.net/npm/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
  <script type="text/javascript" src="giveaway.js"></script>
</head>
<body>
    

    
  <button onclick="connectMetamaskPC()">Metamask</button><br>  
  <button onclick="connectWC()">Wallet Connect</button><br>
  <br>
  <br>
  <button onclick="changeNetworkToPolygon()">Change Network</button><br>
  <button onclick="addPlata()">Add Plata</button><br>
  <button onclick="requestEtherWORKING()">Claim Metamask</button><br>
  <button onclick="requestPlataWalletConnect()">Claim WalletConnect</button><br>
  <button onclick="sign-message()">Contract Test</button><br>
  <br>
  <button onclick="disconnect()">Disconnect</button><br>
  <br>
  
  
  
  <a id="NetNetWork">(X)</a><br>
  <a id="ConnectedWallet">Disconnected</a>


  <script type="text/javascript">
    var account;

    // https://docs.walletconnect.com/quick-start/dapps/web3-provider
    var provider = new WalletConnectProvider.default({
      rpc: {
          1: "https://mainnet.mycustomnode.com",
        137: "https://polygon-rpc.com/", // https://docs.polygon.technology/docs/develop/network-details/network/
        // ...

      },
      bridge: 'https://bridge.walletconnect.org',
    });


    var connectWC = async () => {

      await provider.enable();
      const web3 = new Web3(provider);
      window.w3 = web3;


      var accounts  = await web3.eth.getAccounts(); // get all connected accounts
      account = accounts[0]; // get the primary account
      document.getElementById("ConnectedWallet").innerText = accounts[0];
      const chainId = await web3.eth.getChainId();
      
      if (chainId != 137) 
      {
          alert ("Wrong Chain, please select POLYGON (137) ");
          disconnect();
      }
      
      document.getElementById("NetNetWork").innerText = "Chain id: " + chainId;

    }


    var sign = async (msg) => {
      if (w3) {
        return await w3.eth.personal.sign(msg, account);
      } else {
        return false;
      }
    }

    var contract = async (abi, address) => {
      if (w3) {
        return new w3.eth.Contract(abi, address);
      } else {
        return false;
      }
    }

    var disconnect = async () => {
      // Close provider session
      await provider.disconnect();
      document.getElementById("ConnectedWallet").innerText = "disconnected";
      document.getElementById("NetNetWork").innerText = "";
    }


    var address = "0x4b4f8ca8FB3e66B5DdAfCEbFE86312cEC486DAE1";
    var abi = [{"inputs":[],"name":"count","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"increment","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"nonpayable","type":"function"}];
    
    var addressGiveAway = "0x40955A82Ef6fBe989D9ce9393E80A4dD8c93fd0B";
    var abiGiveAway = [{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"_from","type":"address"},{"indexed":false,"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"TransferReceived","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"_from","type":"address"},{"indexed":false,"internalType":"address","name":"_destAddr","type":"address"},{"indexed":false,"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"TransferSent","type":"event"},{"inputs":[{"internalType":"contract IERC20","name":"token","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"WithdrawTotal","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"balance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"endGiveaway","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"contract IERC20","name":"token","type":"address"},{"internalType":"address","name":"to","type":"address"}],"name":"giveAwayERC20","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_account","type":"address"}],"name":"setExcludeFromBlackList","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"_account","type":"address"}],"name":"setIncludeToBlackList","outputs":[],"stateMutability":"nonpayable","type":"function"},{"stateMutability":"payable","type":"receive"}];


  </script>

    <script>
    
    let PolygonWallet;
    let accounts;
    
window.onload= () => {
  if(window.ethereum) {
     document.getElementById("NetNetWork").innerText = "MetaMask Detected, ";
     web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:8545'));
     web3 = new Web3(window.ethereum)
  }
  else {}
     //document.getElementById("NetNetWork").innerText = "MetaMask not detected";
}



           function requestEtherWORKING(){
            
            new Promise((resolve, reject) => {
                getAccounts(function(result) {
                    const Faucet = new web3.eth.Contract(abiGiveAway, addressGiveAway);
                    
                    Faucet.methods.giveAwayERC20("0xc298812164bd558268f51cc6e3b8b5daaf0b6341" , document.getElementById("ConnectedWallet").innerText ).send({from:result[0]},function (error, result){
                        if(!error){
                            console.log(result);
                        }else{
                            console.log(error);
                        }
                    });
                });
                resolve();
            });
            
        }
   
   
        var requestPlataWalletConnect = async () =>
            {
                const token = String("0xc298812164bd558268f51cc6e3b8b5daaf0b6341");
                conn = new w3.eth.Contract(abiGiveAway, addressGiveAway);
                await conn.methods.giveAwayERC20(token,account).send({from:account});
            }
   
        var changeNetworkToPolygonWalletConnect = async () =>
            {

    await w3.eth
  .request({
    method: "wallet_addEthereumChain",
    params: [
        { chainId: "0x89",
          chainName: "Polygon Mainnet",
          rpcUrls:[  "https://polygon-rpc.com"],
          blockExplorerUrls: ["https://polygonscan.com/"],
          
          nativeCurrency: {
                        name: "MATIC",
                        symbol: "MATIC",
                        decimals: 18,
                      },
        }   ],
  })
  .catch(() => {});


            }
   
   
        function getAccounts(callback) {
            web3.eth.getAccounts((error,result) => {
                if (error) {
                    console.log(error);
                } else {
                    callback(result);
                }
            });
        }
        
const addPlata = async() => {
await window.ethereum

  .request({
    method: 'wallet_watchAsset',
    params: {
      type: 'ERC20',
      options: {
        address: '0xc298812164bd558268f51cc6e3b8b5daaf0b6341',
        symbol: 'PLT',
        decimals: 4,
        image: 'https://www.plata.ie/PlataImage.svg',
      },
    },
  })
  .then((success) => {
    if (success) {
      console.log('PLATA successfully added to wallet!');
    } else {
      throw new Error('Something went wrong.');
    }
  })
  .catch(console.error);
}

const changeNetworkToPolygon = async() => {
await window.ethereum
  .request({
    method: "wallet_addEthereumChain",
    params: [
        { chainId: "0x89",
          chainName: "Polygon Mainnet",
          rpcUrls:[  "https://polygon-rpc.com"],
          blockExplorerUrls: ["https://polygonscan.com/"],
          
          nativeCurrency: {
                        name: "MATIC",
                        symbol: "MATIC",
                        decimals: 18,
                      },
        }   ],
  })
  .catch(() => {});
}

//const getAccounts = async () =>  await window.ethereum.request({method: 'eth_accounts'})[0] || false;

    const connectMetamaskPC = async() => {
        accounts = await window.ethereum.request({method : 'eth_requestAccounts'}).catch((err) => {
   })
    document.getElementById("NetNetWork").innerText = "Polygon Network: ";

    changeNetworkToPolygon();
    let PolygonWallet = accounts[0];
    document.getElementById("ConnectedWallet").innerText = accounts[0];
    console.log(PolygonWallet);
    }
        
        
    </script>


</body>
</html>