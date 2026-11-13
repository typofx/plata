const { getAssets } = require('./remoteData')
const { tokenInfo } = require('./funcHelpers')

async function main() {

    const pools = await getAssets()
    const contracts = new Set(Object.values(pools).flatMap(p => p.assets.map(a => a.contract)))

    for (const c of contracts) {
        const { name, ticker, decimals } = await tokenInfo(c)
        console.log(c + ' : ' + name + ' | ' + ticker + ' | ' + decimals)
    }
}

main()
