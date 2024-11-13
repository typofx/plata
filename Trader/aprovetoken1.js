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

const name1 = 'Plata'
const symbol1 = 'PLT'
const decimals1 = 4
const address1 = '0xc298812164bd558268f51cc6e3b8b5daaf0b6341'

//Aprove Plata

async function aproveToken1() {

    const wallet = new ethers.Wallet(WALLET_SECRET)
    const connectedWallet = wallet.connect(provider)

    const inputAmountIn = 1000000
    amountIn = ethers.parseUnits(inputAmountIn.toString(),decimals1)

    const approvalAmount1 = (amountIn).toString() //Token1 Plata   
    const tokenContract1 = new ethers.Contract(address1, ERC20ABI, provider) //Token1 Plata  
    const approvalResponse1 = await tokenContract1.connect(connectedWallet).approve(
            
    swapRouterAddress,approvalAmount1).then ( approvalResponse1 => { console.log(approvalResponse1) } )

    console.log("Token " + name1 + "(" + symbol1 + ") Aproved")
    
}
aproveToken1()
