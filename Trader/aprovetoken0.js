const { ethers, MaxUint256 } = require('ethers')
const { abi: IUniswapV3PoolABI } = require('@uniswap/v3-core/artifacts/contracts/interfaces/IUniswapV3Pool.sol/IUniswapV3Pool.json')
const { abi: ISwapRouterABI } = require('@uniswap/v3-periphery/artifacts/contracts/interfaces/ISwapRouter.sol/ISwapRouter.json')
//const { abi: SwapRouterABI} = require('@uniswap/v3-periphery/artifacts/contracts/SwapRouter.sol/SwapRouter.json')

const { getPoolImmutables, getPoolState} = require ('./helpers')
const { abi: QuoterABI } = require("@uniswap/v3-periphery/artifacts/contracts/lens/Quoter.sol/Quoter.json") //new
const ERC20ABI = require('./abi.json')

require('dotenv').config()
const POLYGON_CHAIN = process.env.POLYGON_CHAIN
const WALLET_ADDRESS = process.env.WALLET_ADDRESS
const WALLET_SECRET = process.env.WALLET_SECRET

//const provider = new ethers.providers.JsonRpcProvider(POLYGON_CHAIN)
const provider = new ethers.JsonRpcProvider(POLYGON_CHAIN);

const poolAddress = "0x475dfb5ceab85e69d7de22842e3a6c394ce2cc89"
const swapRouterAddress = '0xE592427A0AEce92De3Edee1F18E0157C05861564'

const name0 = 'Wrapped MATIC'
const symbol0 = 'WMATIC'
const decimals0 = 18
const address0 = '0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270'

//Aprove WMATIC

async function aproveToken0() {

    const wallet = new ethers.Wallet(WALLET_SECRET)
    const connectedWallet = wallet.connect(provider)

    const inputAmountOut = await provider.getBalance(WALLET_ADDRESS)
    amountOut = ethers.parseUnits(inputAmountOut.toString(),decimals0)

    const approvalAmount0 = (inputAmountOut).toString() //Token0 WMATIC
    const tokenContract0 = new ethers.Contract(address0, ERC20ABI, provider) //Token0 WMATIC
    const approvalResponse0 = await tokenContract0.connect(connectedWallet).approve(
            
    swapRouterAddress,approvalAmount0).then ( approvalResponse0 => { console.log(approvalResponse0) } )

    console.log("Token " + name0 + "(" + symbol0 + ") Aproved")
    
}

setTimeout(() => { aproveToken0() }, 5000)
