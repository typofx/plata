const RPC = process.env.POLYGON_RPC
const PLTUSD_URL = 'https://www.typofx.ie/.scr/library/json/plata.token/USD.json'

// quickSwap v2 plt/dai pool, it holds both tokens like a wallet.
const LP = '0xbc2ae8655e6847e83553c23e8cf7e310527991a3'
const PLT = '0xc298812164bd558268f51cc6e3b8b5daaf0b6341'
const DAI = '0x8f3Cf7ad23Cd3CaDbD9735AFf958023239c6A063'

const balanceOf = '0x70a08231' + LP.slice(2).padStart(64, '0')
const decimals = '0x313ce567'

async function readTokens() {
  const calls = [[PLT, balanceOf], [PLT, decimals], [DAI, balanceOf], [DAI, decimals]]
  
  const res = await fetch(RPC, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(calls.map(([to, data], id) => ({ jsonrpc: '2.0', id, method: 'eth_call', params: [{ to, data }, 'latest'] }))),
  })
  
  const out = (await res.json()).sort((a, b) => a.id - b.id)
  if (out.some(r => !r.result)) throw new Error('RPC error: ' + JSON.stringify(out))
  const [pltBal, pltDec, daiBal, daiDec] = out.map(r => BigInt(r.result))
  return {
    qtPLT: Number(pltBal) / 10 ** Number(pltDec),
    qtDAI: Number(daiBal) / 10 ** Number(daiDec),
  }
  
}

async function main() {
  const PLTUSD = Number((await (await fetch(PLTUSD_URL)).json()).pc)
  const { qtPLT, qtDAI } = await readTokens()
  const DAIUSD = PLTUSD * qtPLT / qtDAI
  console.log(`PLTUSD ${PLTUSD.toFixed(8)}`)
  console.log(`QTPLT  ${qtPLT}`)
  console.log(`QTDAI  ${qtDAI}`)
  console.log(`DAIUSD ${DAIUSD.toFixed(8)}`)
}

main()
