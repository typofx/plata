const { ethers } = require('ethers')
const ERC20ABI = require('./abi.json')
const fs = require('fs')

require('dotenv').config()

const WALLET_ADDRESS = process.env.WALLET_ADDRESS
const WALLET_SECRET = process.env.WALLET_SECRET
const CRYPTO_CHAIN = process.env.CRYPTO_CHAIN
const PROCESSING_URL = process.env.PROCESSING_URL
const ASSETS_URL = 'https://typofx.ie/plataforma/panel/asset/assets.json'

const provider = new ethers.JsonRpcProvider(process.env.POLYGON_CHAIN)

const wallet = new ethers.Wallet(WALLET_SECRET)
const connectedWallet = wallet.connect(provider)

const LOG_FILE = './transferedPaid.json'

const getProcessing = () => fetchJson(PROCESSING_URL)
const getAssets = () => fetchJson(ASSETS_URL)

async function fetchJson(url) {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`Failed to fetch ${url}: ${res.status} ${res.statusText}`)
    return res.json()
}

async function getTransfersFromProcessing() {
    const [processing, assets] = await Promise.all([getProcessing(), getAssets()])

    return Object.values(processing).map(entry => {
        const asset = assets.find(a => a.contract.toLowerCase() === entry['Token Address'].toLowerCase())
        if (!asset) throw new Error('Asset not found for contract ' + entry['Token Address'])

        return {
            symbol: asset.ticker,
            decimals: asset.decimals,
            address: asset.contract,
            to: entry['Address To'],
            amount: parseFloat(String(entry['Amount']).replace(/,/g, '')),
            tfx_id: entry['TFX ID'],
            identify: entry['Identify']
        }
    })
}

function logTransfer(tx_hash, _address, _amount, _to, _tfx_id, _identify) {
    const log = fs.existsSync(LOG_FILE) ? JSON.parse(fs.readFileSync(LOG_FILE, 'utf8')) : []

    log.push({
        timestamp: new Date().toISOString(),
        tx_hash,
        token_address: _address,
        amount: _amount,
        to_address: _to,
        tfx_id: _tfx_id,
        identify: _identify
    })

    fs.writeFileSync(LOG_FILE, JSON.stringify(log, null, 2))
}

async function transfer(_symbol, _decimals, _address, _to, _amount, _tfx_id, _identify) {
    const isNative = _address.toLowerCase() === CRYPTO_CHAIN.toLowerCase()

    let txResponse
    if (isNative) {
        const value = ethers.parseUnits(_amount.toFixed(_decimals), _decimals)
        txResponse = await connectedWallet.sendTransaction({ to: _to, value })
    } else {
        const tokenContract = new ethers.Contract(_address, ERC20ABI, provider)
        const amount = ethers.parseUnits(_amount.toFixed(_decimals), _decimals)
        txResponse = await tokenContract.connect(connectedWallet).transfer(_to, amount)
    }

    console.log(WALLET_ADDRESS + " Sent " + _amount + " (" + _symbol + ") to " + _to)
    logTransfer(txResponse.hash, _address, _amount, _to, _tfx_id, _identify)
}


async function main() {
    const transfers = await getTransfersFromProcessing()
    for (const t of transfers) {
        await transfer(t.symbol, t.decimals, t.address, t.to, t.amount, t.tfx_id, t.identify)
    }
}

main()
