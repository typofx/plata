<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload via Clipboard e Seleção de Arquivo</title>
</head>
<body>

<input type="file" id="fileInput" accept="image/*" style="display: none;">
<button id="pasteButton">Colar Print</button>
<button id="uploadButton">Selecionar Captura de Tela</button>
<img id="preview" src="" alt="Preview" style="display: block; margin-top: 20px; max-width: 300px;">

<script>
document.getElementById('pasteButton').addEventListener('click', async () => {
  try {
    const clipboardItems = await navigator.clipboard.read();
    for (const clipboardItem of clipboardItems) {
      if (clipboardItem.types.includes('image/png')) {
        const blob = await clipboardItem.getType('image/png');
        const file = new File([blob], 'screenshot.png', { type: 'image/png' });

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
