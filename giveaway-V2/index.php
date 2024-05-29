<?php
// Lista de User-Agents conhecidos de bots
$botUserAgents = [
  'Googlebot',
  'Bingbot',
  'Slurp',
  'DuckDuckBot',
  'Baiduspider',
  'YandexBot',
  'Sogou',
  'Exabot',
  'facebot',
  'ia_archiver'
];

// Verifica o User-Agent da solicitação
$userAgent = $_SERVER['HTTP_USER_AGENT'];
foreach ($botUserAgents as $bot) {
  if (stripos($userAgent, $bot) !== false) {
    // Bloqueia o acesso e envia um código de status 403 (Forbidden)
    header('HTTP/1.1 403 Forbidden');
    exit('Acesso proibido para bots.');
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload vote</title>
</head>

<body>

  <h1>CoinMarketCap Dexscan UpVote</h1>
  <form id="voteForm" action="submit_vote.php" method="post" enctype="multipart/form-data">
    <label for="evm_wallet">EVM Wallet:</label><br>
    <input type="text" id="evm_wallet" name="evm_wallet" required><br><br>

    <label for="vote_image">Vote Image:</label><br>
    <input type="file" id="fileInput" name="vote_image" accept="image/*" required><br><br>

    <button type="button" id="pasteButton">Colar Print</button>


    <img id="preview" src="" alt="Preview" style="display: block; margin-top: 20px; max-width: 300px;"><br><br>

    <label for="vote_number">Vote Number:</label><br>
    <input type="number" id="vote_number" name="vote_number" required><br><br>

    <input type="submit" value="Submit Vote">
  </form>

  <script>
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
        console.error('Falha ao ler o conteúdo da área de transferência: ', err);
      }
    });

    document.getElementById('uploadButton').addEventListener('click', () => {
      document.getElementById('fileInput').click();
    });

    document.getElementById('fileInput').addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const img = document.getElementById('preview');
        img.src = URL.createObjectURL(file);
      } else {
        alert('Por favor, selecione uma imagem válida.');
      }
    });
  </script>

</body>

</html>