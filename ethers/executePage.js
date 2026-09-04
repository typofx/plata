async function executePage(url) {
    const res = await fetch(url)
    if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`)

    const output = await res.text()
    console.log(`Executed ${url}:\n`, output)
    return output
}