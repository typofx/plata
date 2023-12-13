<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/dist/css/alertify.min.css" />
  <title>Formulário</title>
</head>

<style>
    .btn-purple {
      background-color: purple;
      border-color: purple;
    }
  </style>

<body>

<?php include '/home2/granna80/public_html/en/mobile/price.php';  ?>
<?php
// ... Your existing PHP code ...

// Assuming we want to display the market capitalization in USD
$selectedMarketcap = $PLTmarketcapUSD;

// Assuming we want to display the currency value for USD
$selectedCurrency = "(USD)";

// Assuming we want to display the pair value for USD
$selectedPair = $PLTUSD;

// ... Your existing PHP code ...


?>



<div class="container mt-5">
    <div class="row">
      <!-- Primeira coluna -->
      <div class="col-md-6">
        <form>
         
        <div class="mb-3">
        <label for="homepage" class="form-label">Homepage</label>
        <div class="input-group">
          <input type="text" class="form-control" id="homepage" value="https://www.plata.ie" readonly>
          <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('homepage')">Copy</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="telegram" class="form-label">Telegram</label>
        <div class="input-group">
          <input type="text" class="form-control" id="telegram" value="https://t.me/typofx" readonly>
          <button class="btn btn-primary btn-purple btn-purple" type="button" onclick="copyToClipboard('telegram')">Copy</button>
        </div>
      </div>

      <div class="mb-3">
  <label for="whatsapp" class="form-label">Whatsapp</label>
  <div class="input-group">
    <input type="text" class="form-control" id="whatsapp" value="https://chat.whatsapp.com/EWnfnZ2zyA28opVHSg4ryO" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('whatsapp')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="twitter" class="form-label">Twitter</label>
  <div class="input-group">
    <input type="text" class="form-control" id="twitter" value="https://twitter.com/TheTypoFX" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('twitter')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="github" class="form-label">Github/Source Code</label>
  <div class="input-group">
    <input type="text" class="form-control" id="github" value="https://github.com/typofx/plata" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('github')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="facebook" class="form-label">Facebook</label>
  <div class="input-group">
    <input type="text" class="form-control" id="facebook" value="https://www.facebook.com/typoFX/" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('facebook')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="instagram" class="form-label">Instagram</label>
  <div class="input-group">
    <input type="text" class="form-control" id="instagram" value="https://www.instagram.com/typofx/" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('instagram')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="medium" class="form-label">Medium</label>
  <div class="input-group">
    <input type="text" class="form-control" id="medium" value="https://medium.com/@typofx" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('medium')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="email" class="form-label">Email</label>
  <div class="input-group">
    <input type="text" class="form-control" id="email" value="actm@plata.ie" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('email')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="linkedin" class="form-label">Linkedin</label>
  <div class="input-group">
    <input type="text" class="form-control" id="linkedin" value="https://www.linkedin.com/company/89200189/" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('linkedin')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="youtube" class="form-label">YouTube</label>
  <div class="input-group">
    <input type="text" class="form-control" id="youtube" value="https://www.youtube.com/@typofx8796" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('youtube')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="litepaper" class="form-label">Litepaper/Whitepaper</label>
  <div class="input-group">
    <input type="text" class="form-control" id="litepaper" value="https://typofx.gitbook.io/usdplt-token-for-actm/" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('litepaper')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="nft" class="form-label">NFT/OpenSea</label>
  <div class="input-group">
    <input type="text" class="form-control" id="nft" value="https://opensea.io/collection/platatoken" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('nft')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="about" class="form-label">About/Description</label>
  <div class="input-group">
    <input type="text" class="form-control" id="about" value="$PLT Token for ACTM, Automated Crypto Teller Machine Project. Cryptocurrency to cash and vice-versa from a kiosk. Emulating all tranquillity and standards the traditional ATM can offer to users. Being able to swap them for fiat money using a reliable dapp running on the ACTM." readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('about')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="kyc" class="form-label">KYC</label>
  <div class="input-group">
    <input type="text" class="form-control" id="kyc" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('kyc')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="audit01" class="form-label">Audit 01</label>
  <div class="input-group">
    <input type="text" class="form-control" id="audit01" value="https://www.cyberscope.io/cyberscan?network=MATIC&address=0xc298812164bd558268f51cc6e3b8b5daaf0b6341" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('audit01')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="audit02" class="form-label">Audit 02</label>
  <div class="input-group">
    <input type="text" class="form-control" id="audit02" value="https://gopluslabs.io/token-security/137/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('audit02')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="pooCoin" class="form-label">PooCoin</label>
  <div class="input-group">
    <input type="text" class="form-control" id="pooCoin" value="https://polygon.poocoin.app/tokens/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('pooCoin')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="dexTools" class="form-label">DexTools</label>
  <div class="input-group">
    <input type="text" class="form-control" id="dexTools" value="https://www.dextools.io/app/en/polygon/pair-explorer/0x84236e57026739b36832641a99c13c29ddd29526" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('dexTools')">Copy</button>
  </div>
</div>

        </form>
      </div>

      <!-- Segunda coluna -->
      <div class="col-md-6">
        <form>
         



<div class="mb-3">
  <label for="sushi" class="form-label">Sushi</label>
  <div class="input-group">
    <input type="text" class="form-control" id="sushi" value="https://www.sushi.com/swap?fromChainId=137&fromCurrency=NATIVE&toChainId=137&toCurrency=0xC298812164BD558268f51cc6E3B8b5daAf0b6341&amount=1" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('sushi')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="coinGecko" class="form-label">CoinGecko Terminal</label>
  <div class="input-group">
    <input type="text" class="form-control" id="coinGecko" value="https://www.geckoterminal.com/polygon_pos/pools/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('coinGecko')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="uniswap" class="form-label">Uniswap</label>
  <div class="input-group">
    <input type="text" class="form-control" id="uniswap" value="https://app.uniswap.org/#/tokens/polygon/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('uniswap')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="quickswap" class="form-label">Quickswap</label>
  <div class="input-group">
    <input type="text" class="form-control" id="quickswap" value="https://quickswap.exchange/#/swap?swapIndex=0&currency0=ETH&currency1=0xC298812164BD558268f51cc6E3B8b5daAf0b6341" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('quickswap')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="contract" class="form-label">Contract</label>
  <div class="input-group">
    <input type="text" class="form-control" id="contract" value="0xC298812164BD558268f51cc6E3B8b5daAf0b6341" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('contract')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="chain" class="form-label">Chain, Network, Blockchain</label>
  <div class="input-group">
    <input type="text" class="form-control" id="chain" value="Polygon - 137 (MATIC)" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('chain')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="chain" class="form-label">Source Code</label>
  <div class="input-group">
    <input type="text" class="form-control" id="sourcecode" value="https://polygonscan.com/address/0xc298812164bd558268f51cc6e3b8b5daaf0b6341#code" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('sourcecode')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="launched" class="form-label">Launched</label>
  <div class="input-group">
    <input type="text" class="form-control" id="launched" value="Sep-09-2022 09:22:52 PM +UTC" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('launched')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="name" class="form-label">Name</label>
  <div class="input-group">
    <input type="text" class="form-control" id="name" value="Plata Token" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('name')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="symbol" class="form-label">Symbol</label>
  <div class="input-group">
    <input type="text" class="form-control" id="symbol" value="PLT" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('symbol')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="decimals" class="form-label">Decimals</label>
  <div class="input-group">
    <input type="text" class="form-control" id="decimals" value="4" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('decimals')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="maximumSupply" class="form-label">Maximum Supply</label>
  <div class="input-group">
    <input type="text" class="form-control" id="maximumSupply" value="22,299,000,992" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('maximumSupply')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="totalSupply" class="form-label">Total Supply</label>
  <div class="input-group">
    <input type="text" class="form-control" id="totalSupply" value="22,299,000,992" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('totalSupply')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="circulatingSupply" class="form-label">Circulating Supply</label>
  <div class="input-group">
    <input type="text" class="form-control" id="circulatingSupply" value="11,299,000,992" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('circulatingSupply')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="circulatingSupply" class="form-label">Circulating Supply (Link)</label>
  <div class="input-group">
    <input type="text" class="form-control" id="circulatingSupplyLink" value="https://polygonscan.com/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341#balances" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('circulatingSupplyLink')">Copy</button>
  </div>
</div>


<div class="mb-3">
  <label for="totalBurned" class="form-label">Total Burned</label>
  <div class="input-group">
    <input type="text" class="form-control" id="totalBurned" value="11,000,000,000" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('totalBurned')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="price" class="form-label">Price</label>
  <div class="input-group">
    <input type="text" class="form-control" id="price" value="<?= "USD " .$selectedPair ?>" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('price')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="marketCap" class="form-label">Market Cap</label>
  <div class="input-group">
    <input type="text" class="form-control" id="marketCap" value="<?= $selectedMarketcap ?>" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('marketCap')">Copy</button>
  </div>
</div>


<div class="mb-3">
  <label for="image400" class="form-label">Token Image 1000px</label>
  <div class="input-group">
    <input type="text" class="form-control" id="image1000" value="https://plata.ie/images/platatoken1kpx.png" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('image1000')">Copy</button>
  </div>
</div>


<div class="mb-3">
  <label for="image400" class="form-label">Token Image 400px</label>
  <div class="input-group">
    <input type="text" class="form-control" id="image400" value="https://plata.ie/images/platatoken400px.png" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('image400')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="image200" class="form-label">Token Image 200px</label>
  <div class="input-group">
    <input type="text" class="form-control" id="image200" value="https://plata.ie/images/platatoken200px.png" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('image200')">Copy</button>
  </div>
</div>

<div class="mb-3">
  <label for="banner440x160" class="form-label">Banner 440px x 160px</label>
  <div class="input-group">
    <input type="text" class="form-control" id="banner440x160" readonly>
    <button class="btn btn-primary btn-purple" type="button" onclick="copyToClipboard('banner440x160')">Copy</button>
  </div>
</div>
        </form>
      </div>
    </div>
  </div>
      
  <!DOCTYPE html>
<html lang="en">

<head>
    <!-- Seu código do cabeçalho aqui -->
</head>

<body>
    <!-- Seu formulário e outros elementos aqui -->

   
</body>

</html>



  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function copyToClipboard(fieldId) {
    const field = document.getElementById(fieldId);
    field.select();
    document.execCommand("copy");
    showAlert();
  }

  function showAlert() {
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Copied to clipboard',
      showConfirmButton: false,
      timer: 1500
    });
  }
</script>





</body>

</html>


