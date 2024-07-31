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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload vote</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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

  <style>
    /* This rule is very important, please don't ignore this */
    .container {
      margin: 20px auto;
      max-width: 640px;
    }

    img {
      max-width: 30%;
    }

    .cropper-view-box,
    .cropper-face {
      border-radius: 0%;
    }

    /* The css styles for `outline` do not follow `border-radius` on iOS/Safari (#979). */
    .cropper-view-box {
      outline: 0;
      box-shadow: 0 0 0 1px #39f;
    }
  </style>

</head>

<body>

  <h1>CoinMarketCap Dexscan UpVote</h1>
  <input type="checkbox" id="noImageCheckbox" onchange="toggleForm()"> PrtScn or Proof Image not available<br><br>
  <div id="voteFormWrapper">
    <form id="voteForm" action="submit_vote.php" method="post" enctype="multipart/form-data" onsubmit="return get_action();">
      <label for="evm_wallet">EVM Wallet:</label><br>
      <input type="text" id="evm_wallet" name="evm_wallet" required onchange="isValidEtherWallet('evm_wallet')"><br><br>

      <div class="row">
        <div class="col-lg-6">
          <label for="vote_image">Vote Image:</label><br>
          <button type="button" class="btn btn-primary" onclick="selectImage()">Selecionar Imagem</button>
          <button type="button" id="pasteButton">Paste Print</button>
          <button type="button" class="btn btn-success" id="crop_button">Crop</button>
          <input type="file" id="browse_image" style="display: none;">
          <input type="file" name="vote_image" id="vote_image" style="display: none;" required>
          <div id="display_image_div">
            <img name="display_image_data" id="display_image_data" src="dummy-image.png" alt="Picture" style="width: 300px; height: 200px;">
          </div>
          <input type="hidden" name="cropped_image_data" id="cropped_image_data">
        </div>
        <div class="col-lg-6">
          <label>Preview</label>
          <div id="cropped_image_result">
            <img style="width: 350px;" src="dummy-image.png" />
          </div>
          <br>
        </div>
      </div>

      <label for="vote_number_alternative">Vote Number:</label><br>
      <input type="hidden" id="vote_number_alternative" value="0" name="vote_number" required><br><br>

      <!-- Add the data-sitekey attribute with the site key for reCAPTCHA Enterprise -->
      <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
      <div id="recaptcha-container" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey="6LeJjfIpAAAAAJ775U7mZXTKEMWS_j9yi3hzEn8l"></div><br>
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
      <div id="recaptcha-container-alt" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey="6LeJjfIpAAAAAJ775U7mZXTKEMWS_j9yi3hzEn8l"></div><br>
      <input type="submit" value="Submit">
    </form>
  </div>
  <br>
  <?php ob_start();
  include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
  ob_end_clean(); ?>
  <?php echo "PLTMATIC: $MATICPLT"; ?>
  <br>
 

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

    function isValidEtherWallet(inputId) {
      let address = document.getElementById(inputId).value;
      let result = Web3.utils.isAddress(address);
      if (!result) {
        document.getElementById(inputId).value = "";
        console.log("Invalid EVM Wallet address");
      }
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script>
    function handleImage(url) {
      $('#display_image_div').html('');
      $("#display_image_div").html('<img name="display_image_data" id="display_image_data" src="' + url + '" alt="Uploaded Picture">');

      var image = document.getElementById('display_image_data');
      var button = document.getElementById('crop_button');
      var result = document.getElementById('cropped_image_result');
      var croppable = false;
      var cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 0,
        ready: function() {
          croppable = true;
        },
      });

      button.onclick = function() {
        var croppedCanvas;
        var roundedCanvas;
        var roundedImage;

        if (!croppable) {
          return;
        }

        // Crop
        croppedCanvas = cropper.getCroppedCanvas();

        // Show
        croppedImage = document.createElement('img');
        croppedImage.src = croppedCanvas.toDataURL();
        result.innerHTML = '';
        result.appendChild(croppedImage);

        // Atualiza a imagem original com a imagem cortada
        $('#display_image_data').attr('src', croppedImage.src);

        // Atualiza o input de tipo file com a imagem cortada
        croppedCanvas.toBlob(function(blob) {
          var file = new File([blob], "cropped_image.png", {
            type: "image/png"
          });
          var dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          document.getElementById('vote_image').files = dataTransfer.files;
        });
      };
    }

    function getRoundedCanvas(sourceCanvas) {
      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      var width = sourceCanvas.width;
      var height = sourceCanvas.height;

      canvas.width = width;
      canvas.height = height;
      context.imageSmoothingEnabled = true;
      context.drawImage(sourceCanvas, 0, 0, width, height);
      context.globalCompositeOperation = 'destination-in';
      context.beginPath();
      context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
      context.fill();
      return canvas;
    }

    document.getElementById('pasteButton').addEventListener('click', async () => {
      if (!navigator.clipboard || !navigator.clipboard.read) {
        alert("A funcionalidade de colar imagem não é suportada neste navegador.");
        return;
      }

      try {
        const clipboardItems = await navigator.clipboard.read();
        for (const clipboardItem of clipboardItems) {
          if (clipboardItem.types.some(type => type.startsWith('image/'))) {
            const blob = await clipboardItem.getType(clipboardItem.types.find(type => type.startsWith('image/')));
            const url = URL.createObjectURL(blob);
            handleImage(url);

            // Atualiza o input de tipo file com a imagem colada
            const file = new File([blob], "pasted_image.png", {
              type: blob.type
            });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('vote_image').files = dataTransfer.files;
          }
        }
      } catch (err) {
        console.error('Falha ao ler o conteúdo da área de transferência: ', err);
        alert('Não foi possível acessar o conteúdo da área de transferência.');
      }
    });

    $("body").on("change", "#browse_image", function(e) {
      var files = e.target.files;
      if (files && files.length > 0) {
        var file = files[0];
        var url = URL.createObjectURL(file);
        handleImage(url);

        // Atualiza o input de tipo file com a imagem carregada
        document.getElementById('vote_image').files = e.target.files;
      }
    });

    function selectImage() {
      $('#browse_image').click(); // Simula o clique no input file para abrir o explorador de arquivos
    }
  </script>
</body>

</html>
