const { getAssets } = require('./remoteData')
const { balanceOf, executePage } =  require('./funcHelpers')


const CRYPTO_CHAIN = process.env.CRYPTO_CHAIN

const PLTUSD_URL = 'https://www.typofx.ie/.scr/library/json/plata.token/USD.json'

const PRICED_TOKEN = '0xc298812164bd558268f51cc6e3b8b5daaf0b6341'
const LIQ_POOL_ADDRESS = '0xae9740fb6ad3ff506934f6ca60454765e7ff44c3'

async function main() {
    
    const TOKEN0_USD = Number((await (await fetch(PLTUSD_URL)).json()).pc)
    let TOKEN1_PAIR, TOKEN1_CONTRACT = ''
    let TOKEN1_USD = 0 
    let token0liquidity = 0
    const lpcontracts = await getAssets()
    
    const out = []
    
    for (const a of lpcontracts[LIQ_POOL_ADDRESS]['assets']) {

        const { isNative, amount } = await balanceOf(LIQ_POOL_ADDRESS,a.contract, a.decimals,CRYPTO_CHAIN)

        if (a.contract === PRICED_TOKEN) {
        out.push({
            contract: a.contract,
            ticker: a.ticker,
            price: TOKEN0_USD,
            balance : amount,
        })

        token0liquidity = amount * TOKEN0_USD

        } else
        {
        out.push({
            contract: a.contract,
            ticker: a.ticker,
            balance : amount,
        })

        TOKEN1_CONTRACT = a.contract
        TOKEN1_USD = token0liquidity / amount
        TOKEN1_PAIR = a.ticker + 'USD'

        }

    }

console.log('PLTUSD : ' + TOKEN0_USD + ' ')
console.log(TOKEN1_PAIR + ' : ' + TOKEN1_USD + ' ')

await executePage ('https://www.typofx.ie/sirka/alexscarano/asset.pc/?save='
        + TOKEN1_CONTRACT
        + '@' + 'USD'
        + '@' + TOKEN1_USD )
}

main()
