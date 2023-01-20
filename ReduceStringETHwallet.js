    function ConnectedWallet(){
        return accounts[0];
    }

    function ReducedStringNameWalletAddress() {
        let WalletAddress = ConnectedWallet();
        return ( WalletAddress.slice(0,5) + "..." + WalletAddress.slice(-5,-1) );;
    }

    const connectMetamaskPC = async() =>
    {    
        accounts = await window.ethereum.request ( {method : 'eth_requestAccounts'}).catch((err) => {} )
        changeNetworkToPolygon();
        
        console.log( "ConnectedWallet " + ConnectedWallet() );
        console.log( "ReducedConnectedWallet " + ReduceStringNameWalletAddress() );
        
        document.getElementById("ConnectedWallet").innerText = ReducedStringNameWalletAddress() + " Connected";
    }
