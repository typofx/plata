<!DOCTYPE html>

<html onmouseover="checkNetworkVer()" onmouseout="checkNetworkVer()" >
    
<head>
  <meta charset="utf-8">
  <title>50K $PLT Plata Giveaway</title>
  <link rel="icon" type="image/x-icon" href="../favicon.ico">
  <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wallet Connect</title>
  <script src="https://cdn.jsdelivr.net/npm/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
  <script src="../copyContract.js"></script>
  <script type="text/javascript" src="giveaway.js"></script>
  <link rel="stylesheet" href="giveawaystyle.css">
</head>
<body>
    
  <div align="right" id="walletInjectBar">

  <button id= "btnChain" class="btnChain" onclick="changeNetworkToPolygon()">​</button></a>
  <button id= "btnConnectWallet" class="btnConnectWallet" onclick="changeNetworkToPolygon(),showModal()">Connect Wallet</button></a>

  </div>
  
    <br>
    <div id="boxApp" align="center">
        <div id="box" class="box">
	        <br>
            <br>
            <button id= "btnMetaListPlata"  class= "btnMenuWallet" onclick= "listPlataMetamaskPC()">List Plata Token</button>
            <br>
            <button id= "btnMetaClaimPlata" class= "btnMenuWallet" onclick= "requestEtherWORKING()">Claim 50K $PLT</button>
            <button id= "btnWallClaimPlata" class= "btnMenuWallet" onclick= "requestPlataWalletConnect()">Claim 50K $PLT</button>
            <br>
            <br>
            <hr width="90%" class="hrline">
            <a id="NetNetWork"></a>
            <br>
            <a id="ConnectedWallet">Disconnected</a>
            <br>
            <br>
            <div id="box2" class="boxSmallButtons">
                <div align="right">
                <button id="btnCopyContract" class="btnCopyContract" onclick="copyContract()">​</button>
                <button id="btnBlockExplorer" class="btnBlockExplorer" onclick="window.open('https://polygonscan.com/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341')">​</button>
                <button id="btnDisconnect" class="btnPower" onclick="disconnect()">​</button>
                </div>
            </div>
        </div>
    </div>
  
  <script>
  //<button onclick="sign-message()">Contract Test</button><br>
  </script>
  
  <br>
  <br>
  <br>
  
  
  <br>

  <br>
  <a><i>Polygon Blockchain has been busy.</i></a><br>
  <a><i>Avoid a fallen claim increasing the gas limit on Metamask (Agressive).</i></a>
  <br><br>
  <a><i>Using WalletConnect from PC to Mobile only gets connected on Main Wallet.</i></a>
  <br><br>
  <a id="OperatingSystem">#Operating System#</a>
  
  
  <!-- The Modal --> <!-- Modal content -->
<div id="myModal" class="modal">

  <div class="modal-content">
    <center>
	<span onclick="closeModal()"></span>
	<h4>Select your Web3 Wallet</h4>
    <button class="button" onclick="connectMetamaskPC(),closeModal()">Metamask</button><br><br>
	<button class="button" onclick="connectWC(),closeModal()">WalletConnect</button><br><br>
    <button class="button" onclick="connectMetamaskPC(),closeModal()">Coinbase</button><br><br>
	</center>
  </div>

</div>
  
  
</body>
</html>




<script>

    var account;
    var MetaAccount;
    const accounts = [];

    // https://docs.walletconnect.com/quick-start/dapps/web3-provider
    var provider = new WalletConnectProvider.default({
      rpc: {
          1: "https://mainnet.mycustomnode.com",
        137: "https://polygon-rpc.com/", // https://docs.polygon.technology/docs/develop/network-details/network/
        // ...

      },
      bridge: 'https://bridge.walletconnect.org',
    });


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
      document.getElementById("ConnectedWallet").innerText = "Disconnected";
      document.getElementById("btnConnectButton").innerText = "Connect Wallet";
      //document.getElementById("NetNetWork").innerText = "";
      //accounts[0] = "";
      //account = "";
      
      hideAllConnectedButtons();
    }


    var address = "0x4b4f8ca8FB3e66B5DdAfCEbFE86312cEC486DAE1";
    var abi = [{"inputs":[],"name":"count","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"increment","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"nonpayable","type":"function"}];
    
    var addressGiveAway = "0x9921413Eb2A101A31B37291C33d33883B7581B7F";
    var abiGiveAway = [{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"_from","type":"address"},{"indexed":false,"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"TransferReceived","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"_from","type":"address"},{"indexed":false,"internalType":"address","name":"_destAddr","type":"address"},{"indexed":false,"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"TransferSent","type":"event"},{"inputs":[{"internalType":"contract IERC20","name":"token","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"WithdrawTotal","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"balance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"endGiveaway","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"contract IERC20","name":"token","type":"address"},{"internalType":"address","name":"to","type":"address"}],"name":"giveAwayPlataToken","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_account","type":"address"}],"name":"setExcludeFromBlackList","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"_account","type":"address"}],"name":"setIncludeToBlackList","outputs":[],"stateMutability":"nonpayable","type":"function"},{"stateMutability":"payable","type":"receive"}];


           function requestEtherWORKING(){
            
            new Promise((resolve, reject) => {
                getAccounts(function(result) {
                    const Faucet = new web3.eth.Contract(abiGiveAway, addressGiveAway);
                    
                    Faucet.methods.giveAwayPlataToken("0xc298812164bd558268f51cc6e3b8b5daaf0b6341" , ConnectedWallet(MetaAccount) ).send({from:result[0]},function (error, result){
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
    

    function checkNetworkVer() {
        
        let networkVer = parseInt(window.ethereum.networkVersion);
        
        if(networkVer == 137) {
            document.getElementById("NetNetWork").innerText = "Polygon Network: 137";
            document.getElementById("btnChain").className = "btnChain";
            
            //document.getElementById("btnConnect").disabled = true;
        }
        else
        {
            disconnect();
            document.getElementById("NetNetWork").innerText = "Unknown Network";
            document.getElementById("btnConnectButton").innerText = "Switch Chain";
            document.getElementById("btnChain").className = "btnWrongChain";
            //document.getElementById("btnConnect").disabled = false;
        }
    }
    
    function listPlataMetamaskPC() {
        
        let networkVer = parseInt(window.ethereum.networkVersion);

        if(networkVer == 137) {
            
            addPlata();
            
        } else console.log('Wrong Network');
            
    }
    
    const addPlata = async() =>
            {

                await window.ethereum.request({
                method: "wallet_watchAsset",
                params: {
                    type: 'ERC20',
                    options: {
                    address: '0xc298812164bd558268f51cc6e3b8b5daaf0b6341',
                    symbol: 'PLT',
                    decimals: 4,
                    image: 'https://www.plata.ie/plataI2.svg',
                    },
                },
            }).catch();

            } 

    const changeNetworkToPolygon = async() =>
    {
        await window.ethereum.request({
        method: "wallet_addEthereumChain",
        params: [{
                    chainId: "0x89",
                    chainName: "Polygon Mainnet",
                    rpcUrls:[  "https://polygon-rpc.com"],
                    blockExplorerUrls: ["https://polygonscan.com/"],
          
                    nativeCurrency: {
                                        name: "MATIC",
                                        symbol: "MATIC",
                                        decimals: 18,
                                    },
                }],
        }).catch();
    }
    
    function hideAllConnectedButtons(){
        
        document.getElementById("boxApp").style.visibility = "hidden";
        document.getElementById("boxApp").style.display = "none";
        
        document.getElementById("btnMetaListPlata").style.display = "none";
        document.getElementById("btnMetaClaimPlata").style.display = "none";
        document.getElementById("btnWallClaimPlata").style.display = "none";

        document.getElementById("btnBlockExplorer").style.visibility = "hidden";
        document.getElementById("btnCopyContract").style.visibility = "hidden";
        document.getElementById("btnDisconnect").style.visibility = "hidden";

    } hideAllConnectedButtons();

    function showConnectedMenuMetamask(){

        document.getElementById("boxApp").style.visibility = "visible";
        document.getElementById("boxApp").style.display = "block";
        
        document.getElementById("btnMetaListPlata").style.display = "block";
        document.getElementById("btnMetaListPlata").disabled = false;
        document.getElementById("btnMetaClaimPlata").style.display = "block";
        document.getElementById("btnWallClaimPlata").style.display = "none";
        
        document.getElementById("btnBlockExplorer").style.visibility = "visible";
        document.getElementById("btnDisconnect").style.visibility = "visible";
        document.getElementById("btnCopyContract").style.visibility = "visible";

    } 

    function showConnectedWalletConnect(){

        document.getElementById("boxApp").style.visibility = "visible";
        document.getElementById("boxApp").style.display = "block";
        
        document.getElementById("btnMetaListPlata").style.display = "block";
        document.getElementById("btnMetaListPlata").disabled = true;

        document.getElementById("btnMetaClaimPlata").style.display = "none";
        document.getElementById("btnWallClaimPlata").style.display = "block";
        
        document.getElementById("btnBlockExplorer").style.visibility = "visible";
        document.getElementById("btnDisconnect").style.visibility = "visible";
        document.getElementById("btnCopyContract").style.visibility = "visible";
        
    }

    const connectMetamaskPC = async() => {
        
        let accounts = await window.ethereum.request ( {method : 'eth_requestAccounts'}).catch((err) => {} )
        
        changeNetworkToPolygon();
        
        MetaAccount = accounts[0];

        console.log("ConnectedWallet " + MetaAccount);
        console.log( "ReducedConnectedWallet " + ReducedStringNameWalletAddress(MetaAccount) );
        
        document.getElementById("ConnectedWallet").innerText = ReducedStringNameWalletAddress(MetaAccount) + " Connected";
        document.getElementById("btnConnectButton").innerText = ReducedStringNameWalletAddress(MetaAccount);
        
        
        showConnectedMenuMetamask();
        document.getElementById("btnDisconnect").style.display = "visible";
        
    } 


    window.onload = () => {
        if(window.ethereum) {
            document.getElementById("NetNetWork").innerText = "MetaMask Detected";
            web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:8545'));
            web3 = new Web3(window.ethereum);
        }
        else {}
     //document.getElementById("NetNetWork").innerText = "MetaMask not detected";
    }
    
    const connectWC = async () => {

    document.getElementById("ConnectedWallet").innerText = "Connecting...";
    
        await provider.enable();
        const web3 = new Web3(provider);
        window.w3 = web3;
        var accounts  = await web3.eth.getAccounts();
        const chainId = await web3.eth.getChainId();
        
        account = accounts[0];
        
        //document.getElementById("ConnectedWallet").innerText = accounts[0];

        if (chainId != 137) {
          alert ("Unknown Chain, it must be Polygon (137)");
          disconnect(); 
        } else {
            
        console.log("ConnectedWallet " + account);
        console.log("ReducedConnectedWallet " + ReducedStringNameWalletAddress(account));
        document.getElementById("ConnectedWallet").innerText = ReducedStringNameWalletAddress(account) + " Connected";
        document.getElementById("NetNetWork").innerText = "Polygon Network: 137";
        document.getElementById("btnConnectButton").innerText = ReducedStringNameWalletAddress(account);
        
        showConnectedWalletConnect();
        
        }
        
    }
    
    
    
    function showConnectedMenuWallConnect(){
        document.getElementById("ClaimWallConnect").style.visibility="visible";
    }
    
    function ConnectedWallet(acc){
        let _account = acc;
        return _account;
    }

    function ReducedStringNameWalletAddress(account) {
        let WalletAddress = account + " ";
        return ( WalletAddress.slice(0,6) + "..." + WalletAddress.slice(-5,-1) );;
    }

    function getOS() {
        
        var OS = "Unknown";
        var _isMobile = navigator.userAgent.toLowerCase().match(/mobile/i);
        var isMobile = "PC";
        
        if (navigator.appVersion.indexOf("Win")!=-1) OS="Windows";
            else if (navigator.appVersion.indexOf("Mac")!=-1) OS="MacOS";
                else if (navigator.appVersion.indexOf("X11")!=-1) OS="UNIX";
                    else if (navigator.appVersion.indexOf("Linux")!=-1) OS="Linux";

    if (_isMobile == "mobile") isMobile = "mobile";

    //console.log(navigator.userAgent);

        let userAgent = navigator.userAgent;
        let browserName;
         
        if(userAgent.match(/chrome|chromium|crios/i)){
            browserName = "Chrome";
        } else if(userAgent.match(/firefox|fxios/i)){
                    browserName = "Firefox";
                } else if(userAgent.match(/safari/i)){
                            browserName = "Safari";
                        }else if(userAgent.match(/opr\//i)){
                                    browserName = "Opera";
                                } else if(userAgent.match(/edg/i)){
                                            browserName = "Edge";
                                        } else { browserName="Unknown Browser";
           }
         
    //console.log(browserName);
    
          document.getElementById("OperatingSystem").innerText = "Operating System: " + OS + " " + isMobile + " " + browserName;

    } getOS();
    
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    function showModal() {
        modal.style.display = "block";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    function AlertBox(){
        alert("I am an alert box!");
    }
    
</script>


