<style>
    
    :root {
  color-scheme: dark light;
}

html {
  box-sizing: border-box;
}

*,
*:before,
*:after {
  box-sizing: inherit;
}

body {
  margin: 1rem;
  font-family: system-ui, sans-serif;
}

button {
  display: block;
}
    
</style>

<script>

    document.addEventListener('paste', async (e) => {
  e.preventDefault();
  const clipboardItems = typeof navigator?.clipboard?.read === 'function' ? await navigator.clipboard.read() : e.clipboardData.files;

  for (const clipboardItem of clipboardItems) {
    let blob;
    if (clipboardItem.type?.startsWith('image/')) {
      // For files from `e.clipboardData.files`.
      blob = clipboardItem
      // Do something with the blob.
      appendImage(blob);
    } else {
      // For files from `navigator.clipboard.read()`.
      const imageTypes = clipboardItem.types?.filter(type => type.startsWith('image/'))
      for (const imageType of imageTypes) {
        blob = await clipboardItem.getType(imageType);
        // Do something with the blob.
        appendImage(blob);
      }
    }
  }
});

const appendImage = (blob) => {
  const img = document.createElement('img');
  img.src = URL.createObjectURL(blob);
  document.body.append(img);
};
    
</script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="icon"
      href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎉</text></svg>"
    />
    <title>How to paste images</title>
  </head>
  <body>
    <h1>How to paste images</h1>
    <p>Hit <kbd>⌘</kbd> + <kbd>v</kbd> (for macOS) or <kbd>ctrl</kbd> + <kbd>v</kbd>
      (for other operating systems) to paste images anywhere in this page.
    </p>
  </body>
</html>