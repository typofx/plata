    function ConnectedWallet(){
        return accounts[0];
    }

    function ReducedStringNameWalletAddress() {
        let WalletAddress = ConnectedWallet() + " ";
        return ( WalletAddress.slice(0,6) + "..." + WalletAddress.slice(-5,-1) );;
    }
