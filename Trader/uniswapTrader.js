const { ethers, MaxUint256 } = require('ethers')
const { abi: IUniswapV3PoolABI } = require('@uniswap/v3-core/artifacts/contracts/interfaces/IUniswapV3Pool.sol/IUniswapV3Pool.json')
const { abi: ISwapRouterABI } = require('@uniswap/v3-periphery/artifacts/contracts/interfaces/ISwapRouter.sol/ISwapRouter.json')
//const { abi: SwapRouterABI} = require('@uniswap/v3-periphery/artifacts/contracts/SwapRouter.sol/SwapRouter.json')

const { getPoolImmutables, getPoolState} = require ('./helpers')
const { abi: QuoterABI } = require("@uniswap/v3-periphery/artifacts/contracts/lens/Quoter.sol/Quoter.json"); //new
const ERC20ABI = require('./abi.json')

require('dotenv').config()
const POLYGON_CHAIN = process.env.POLYGON_CHAIN
const WALLET_ADDRESS = process.env.WALLET_ADDRESS
const WALLET_SECRET = process.env.WALLET_SECRET

//const provider = new ethers.providers.JsonRpcProvider(POLYGON_CHAIN)
const provider = new ethers.JsonRpcProvider(POLYGON_CHAIN);

const poolAddress = "0x475dfb5ceab85e69d7de22842e3a6c394ce2cc89"
const swapRouterAddress = '0xE592427A0AEce92De3Edee1F18E0157C05861564'

const name0 = 'Wrapped Matic'
const symbol0 = 'WMATIC'
const decimals0 = 18
const address0 = '0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270'

const name1 = 'Plata'
const symbol1 = 'PLT'
const decimals1 = 4
const address1 = '0xc298812164bd558268f51cc6e3b8b5daaf0b6341'


async function main() {
    const poolContract = new ethers.Contract(
        poolAddress,
        IUniswapV3PoolABI,
        provider
    )

    
    const immutables = await getPoolImmutables(poolContract)
    const state = await getPoolState(poolContract)

    const wallet = new ethers.Wallet(WALLET_SECRET)
    const connectedWallet = wallet.connect(provider)

    const swapRouterContract = new ethers.Contract(
        swapRouterAddress,
        ISwapRouterABI,
        provider
    )

    //let inputAmount = 1
    amountIn = (10000,18)
    
    //const amountIn = ethers.utils.parseUnits(inputAmount.toString(),decimals0)

    //console.log(amountIn)

    const approvalAmount = (1000000000 * 10).toString()
    const tokenContract0 = new ethers.Contract(
        address1, ERC20ABI, provider
    )

    const approvalResponse = await tokenContract0.connect(connectedWallet).approve(
        swapRouterAddress,
        approvalAmount
    )

    const params = {
        tokenIn: immutables.token0,
        tokenOut: immutables.token1,
        fee: immutables.fee,
        recipient: WALLET_ADDRESS,
        deadline: Math.floor(Date.now()/ 1000) + (60 * 10),
        amountIn: amountIn,
        amountOutMinimum: 0,
        sqrtPriceLimitX96: 0,
    }
        

    const transaction = swapRouterContract.connect(connectedWallet).exactInputSingle(
        params, {
            //gasLimit: 3000000,
            //maxPriorityFeePerGas: 3000000,
            //maxFeePerGas: 3000000,
            tokenIn: symbol1,
            tokenOut: symbol0,
        }

    ).then ( transaction => { console.log(transaction) }
)




}

function startInterval() {
    main(); // Executa a primeira vez imediatamente
    setInterval(main, 10000); // 30000 ms = 30 segundos
}

startInterval();