<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload vote</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
  <script src="https://kit.fontawesome.com/0f8eed42e7.js" crossorigin="anonymous"></script>
  <!-- reCAPTCHA Enterprise Script -->
  <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>

  <script>
function get_action() {
  // Check if reCAPTCHA is checked
  if (document.getElementById('recaptchaChecked').value === '1') {
    console.log("reCAPTCHA checked.");
    return true; // Allow form submission
  } else {
    console.log("reCAPTCHA not checked.");
    alert("Please check the reCAPTCHA.");
    return false; // Prevent form submission
  }
}

// Function to update reCAPTCHA state when checked
function recaptchaChecked() {
  document.getElementById('recaptchaChecked').value = '1';
}

    
  </script>
</head>

<body>

  <h1>CoinMarketCap Dexscan UpVote</h1>
  <input type="checkbox" id="noImageCheckbox" onchange="toggleForm()"> PrtScn or Proof Image not available<br><br>
  <div id="voteFormWrapper">
    <form id="voteForm" action="submit_vote.php" method="post" enctype="multipart/form-data" onsubmit="return get_action();">
      <label for="evm_wallet">EVM Wallet:</label><br>
      <input type="text" id="evm_wallet" name="evm_wallet" required onchange="isValidEtherWallet('evm_wallet')"><br><br>

      <label for="vote_image">Vote Image:</label><br>
      <input type="file" id="fileInput" name="vote_image" accept="image/*" required><br><br>

      <button type="button" id="pasteButton">Paste Print</button>
      <label for="vote_number_alternative">Vote Number:</label><br>
      <input type="hidden" id="vote_number_alternative" value="0" name="vote_number" required><br><br>

      <img id="preview" src="" alt="Preview" style="display: block; margin-top: 20px; max-width: 300px;"><br><br>
      <!-- Add the data-sitekey attribute with the site key for reCAPTCHA Enterprise -->
      <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
      <div id="recaptcha-container" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey="KEY"></div><br>
      <input type="submit" value="Submit Vote">
    </form>
  </div>

  <div id="alternativeForm" style="display: none;">
    <!-- Alternative Form -->
    <form id="alternativeFormContent" action="submit_vote.php" method="post" enctype="multipart/form-data" onsubmit="return get_action();">
      <label for="evm_wallet_alternative">EVM Wallet:</label><br>
      <input type="text" id="evm_wallet_alternative" name="evm_wallet" required onchange="isValidEtherWallet('evm_wallet_alternative')"><br><br>

      <label for="vote_number_alternative">Vote Number:</label><br>
      <input type="number" id="vote_number_alternative" name="vote_number" required><br><br>

      <div id="recaptcha-container-alt" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey="KEY"></div><br>
      <input type="submit" value="Submit">
    </form>
  </div>

  <script>
    function toggleForm() {
      const alternativeForm = document.getElementById('alternativeForm');
      const voteFormWrapper = document.getElementById('voteFormWrapper');
      if (document.getElementById('noImageCheckbox').checked) {
        voteFormWrapper.style.display = 'none';
        alternativeForm.style.display = 'block';
      } else {
        voteFormWrapper.style.display = 'block';
        alternativeForm.style.display = 'none';
      }
    }

    document.getElementById('pasteButton').addEventListener('click', async () => {
      try {
        const clipboardItems = await navigator.clipboard.read();
        for (const clipboardItem of clipboardItems) {
          if (clipboardItem.types.some(type => type.startsWith('image/'))) {
            const blob = await clipboardItem.getType(clipboardItem.types.find(type => type.startsWith('image/')));
            const file = new File([blob], 'screenshot.png', {
              type: blob.type
            });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('fileInput').files = dataTransfer.files;

            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(file);
          }
        }
      } catch (err) {
        console.error('Failed to read clipboard content: ', err);
      }
    });

    function isValidEtherWallet(inputId) {
      let address = document.getElementById(inputId).value;
      let result = Web3.utils.isAddress(address);
      if (!result) {
        document.getElementById(inputId).value = "";
        console.log("Invalid EVM Wallet address");
      }
    }
  </script>
</body>

</html>
