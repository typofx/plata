const PROCESSING_URL = 'https://www.typofx.ie/sirka/murilofurtado/payments/processing.json'
const ASSETS_URL = 'https://typofx.ie/plataforma/panel/lp.contracts/lp.v2.json'



async function fetchJson(url) {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`Failed to fetch ${url}: ${res.status} ${res.statusText}`)
    return res.json()
}

const getProcessing = () => fetchJson(PROCESSING_URL)
const getAssets = () => fetchJson(ASSETS_URL)

module.exports = { getProcessing, getAssets }

