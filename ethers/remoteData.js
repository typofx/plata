require('dotenv').config()

const PROCESSING_URL = process.env.PROCESSING_URL
const ASSETS_URL = 'https://typofx.ie/plataforma/panel/asset/assets.json'

async function fetchJson(url) {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`Failed to fetch ${url}: ${res.status} ${res.statusText}`)
    return res.json()
}

const getProcessing = () => fetchJson(PROCESSING_URL)
const getAssets = () => fetchJson(ASSETS_URL)

module.exports = { getProcessing, getAssets }
