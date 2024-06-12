<?php
  session_start();
  if (isset($_SESSION['error'])) {
    echo "<div style='color: red;'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
  }
  if (isset($_SESSION['success'])) {
    echo "<div style='color: green;'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
  }
  ?>

<?php ob_start();
  include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
  ob_end_clean(); ?>
  
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

<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="https://www.plata.ie/css/card-style.css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<html lang="">

<head>
</head>


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
    
    <div id="boxApp" align="center">
        <div id="box" class="box">
            <h3>CoinMarketCap Dexscan UpVote</h3>
            <div>
                
                <form id="voteForm" action="submit_vote.php" method="post" enctype="multipart/form-data" onsubmit="return get_action();">
                    <div id="voteFormWrapper">
                    <div class="div-label"><label for="vote_image">Vote Image:</label></div>
                    <input type="file" id="fileInput" name="vote_image" accept="image/*" required><br><br>
                    
                    <button type="button" class="buttonBuyNow" id="pasteButton">Paste Print</button>
                    
                    
                    
                    <img id="preview" src="" alt="Preview" style="display: block; margin-top: 20px; max-width: 350px;"><br><br>
                    </div>
                    
                    <input type="checkbox" id="noImageCheckbox" onchange="toggleForm()" class="checkmark"> PrtScn(Proof Image) not attached
                    
                    <hr width="95%" class="hrline" />
                    <div class="div-label"><label for="webWallet">EVM Wallet (Polygon Chain 137):</label></div>
                    <div><input type="text" id="evm_wallet" name="evm_wallet" required onchange="isValidEtherWallet('evm_wallet')"></div>
                    

                    <div id="alternativeForm" style="display: none;">
                    <!-- Alternative Form -->
                        <form id="alternativeFormContent" action="submit_vote.php" method="post" enctype="multipart/form-data" onsubmit="return get_action();">
                            <input type="text" id="evm_wallet_alternative" name="evm_wallet" required onchange="isValidEtherWallet('evm_wallet_alternative')"><br><br>
                            <label for="vote_number_alternative">Vote Number:</label><br>
                            <input type="hidden" id="vote_number_alternative" value="0" name="vote_number" required><br><br>
                                 
                            <div><button type="submit" class="buttonBuyNow" value="Submit Vote">Submit Vote</button></div>
                        </form>
                    </div>
                    
                    <hr width="95%" class="hrline" />
                    <div class="div-label"><label for="PLTvalue">Prize for this task:</label></div>
                    <?php echo "PLTMATIC: $MATICPLT";   ?>

                    <hr width="95%" class="hrline" />
                    <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
               
                    <hr width="95%" class="hrline" />
                    <div><button type="submit" class="buttonBuyNow" value="Submit Vote">Submit Vote</button></div>
                    <hr width="95%" class="hrline" />
                    <a href="https://www.plata.ie/en/select/?cancel=true">Cancel</a>
                    <br><br><br>
                </form>
                
                  <div id="voteFormWrapper">

  </div>



            </div>
        </div>
        <br>
        <center><a id="dappVersion">PlataUpVote Version 0.0.1 (Beta)</a></center><br>

</body>

</html>