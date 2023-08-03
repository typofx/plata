const tokenAddress = '0xC298812164BD558268f51cc6E3B8b5daAf0b6341';
        const tokenSymbol = 'PLT';
        const tokenDecimals = 4;
        const tokenImage = 'https://plata.ie/images/platatoken200px.png';

        async function addTokenFunction() {
            try {
                const wasAdded = await ethereum.request({
                    method: 'wallet_watchAsset',
                    params: {
                        type: 'ERC20',
                        options: {
                            address: tokenAddress,
                            symbol: tokenSymbol,
                            decimals: tokenDecimals,
                            image: tokenImage,
                        },
                    },
                });

                if (wasAdded) {
                    console.log('Token adicionado à carteira.');
                } else {
                    console.log('Usuário cancelou a adição do token.');
                }
            } catch (error) {
                console.log(error);
            }
        }
