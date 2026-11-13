const { getAssets } = require('./remoteData')
const { balanceOf, executePage } = require('./funcHelpers')

const CRYPTO_CHAIN = process.env.CRYPTO_CHAIN

const SAVE_URL = 'https://www.typofx.ie/sirka/alexscarano/asset.pc/?save='

const PRICED_TOKEN = {
    contract: '0xc298812164bd558268f51cc6e3b8b5daaf0b6341',
    ticker: 'PLT',
    fiat: {
        USD: 'https://www.typofx.ie/.scr/library/json/plata.token/USD.json',
        BRL: 'https://www.typofx.ie/.scr/library/json/plata.token/BRL.json',
        EUR: 'https://www.typofx.ie/.scr/library/json/plata.token/EUR.json'
    }
}

async function main() {

    const pricedTokenPrice = {}
    for (const fiat in PRICED_TOKEN.fiat) {
        pricedTokenPrice[fiat] = Number((await (await fetch(PRICED_TOKEN.fiat[fiat])).json()).pc)
        console.log(PRICED_TOKEN.ticker + fiat + ' : ' + pricedTokenPrice[fiat])
    }

    const pools = await getAssets()

    for (const [pool, { assets }] of Object.entries(pools)) {
        const pricedToken = assets.find(a => a.contract === PRICED_TOKEN.contract)
        const token = assets.find(a => a.contract !== PRICED_TOKEN.contract)
        if (!pricedToken || !token) continue

        try {
            const pricedTokenBalance = (await balanceOf(pool, pricedToken.contract, pricedToken.decimals, CRYPTO_CHAIN)).amount
            const tokenBalance = (await balanceOf(pool, token.contract, token.decimals, CRYPTO_CHAIN)).amount

            for (const fiat in pricedTokenPrice) {
                const tokenPrice = pricedTokenBalance * pricedTokenPrice[fiat] / tokenBalance
                console.log(token.ticker + fiat + ' : ' + tokenPrice)
                await executePage(SAVE_URL + token.contract + '@' + fiat + '@' + tokenPrice)
            }
        } catch (e) {
            console.log(token.ticker + ' : ' + e.message)
        }
    }
}

main()
