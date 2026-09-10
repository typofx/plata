const { ethers } = require('ethers')
const ERC20ABI = require('./abi.json')
const { getAssets } = require('./remoteData')
const fs = require('fs')

require('dotenv').config()

const WALLET_ADDRESS = process.env.WALLET_ADDRESS
const CRYPTO_CHAIN = process.env.CRYPTO_CHAIN

const provider = new ethers.JsonRpcProvider(process.env.POLYGON_CHAIN)

const OUT_FILE = './balances.json'

function utcTimestamp() {
    const [date, time] = new Date().toISOString().split('T')
    const [y, m, d] = date.split('-')
    return `${d}.${m}.${y} ${time.slice(0, 8)} UTC`
}

async function balanceOf(_address, _decimals) {
    const isNative = _address.toLowerCase() === CRYPTO_CHAIN.toLowerCase()

    const raw = isNative
        ? await provider.getBalance(WALLET_ADDRESS)
        : await new ethers.Contract(_address, ERC20ABI, provider).balanceOf(WALLET_ADDRESS)

    return { isNative, amount: Number(ethers.formatUnits(raw, _decimals)) }
}

async function main() {
    const assets = await getAssets()

    const out = []
    for (const a of assets) {
        if (!a.contract) continue // moedas fiduciárias do assets.json não têm contrato on-chain

        const { isNative, amount } = await balanceOf(a.contract, a.decimals)
        console.log(WALLET_ADDRESS + " has " + amount + " (" + a.ticker + ") ")
        if (amount === 0) continue 

        out.push({
            contract: a.contract,
            ticker: a.ticker,
            amount,
            type: isNative ? 'gas' : 'token',
        })
    }

    fs.writeFileSync(OUT_FILE, JSON.stringify({
        address: WALLET_ADDRESS,
        timestamp: utcTimestamp(),
        assets: out,
    }, null, 2))
}

main()
