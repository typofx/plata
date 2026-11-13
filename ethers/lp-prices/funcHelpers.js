const { ethers } = require('ethers')
const ERC20ABI = require('./abi.json')

require('dotenv').config()

const provider = new ethers.JsonRpcProvider(process.env.POLYGON_CHAIN)

async function balanceOf(_wallet, _contract, _decimals, _chain) {
    const isNative = _contract.toLowerCase() === _chain.toLowerCase()

    const raw = isNative
        ? await provider.getBalance(_wallet)
        : await new ethers.Contract(_contract, ERC20ABI, provider).balanceOf(_wallet)

    return { isNative, amount: Number(ethers.formatUnits(raw, _decimals)) }
}

async function executePage(url) {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`)

    const output = await res.text()
    console.log(`Executed ${url}:\n`, output)
    return output
}

async function tokenInfo(_contract) {
    const token = new ethers.Contract(_contract, ERC20ABI, provider)
    const [name, ticker, decimals] = await Promise.all([token.name(), token.symbol(), token.decimals()])
    return { name, ticker, decimals: Number(decimals) }
}

module.exports = { balanceOf, executePage, tokenInfo }
