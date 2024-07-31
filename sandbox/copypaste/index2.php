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
        margin-top: 1rem;
        padding: 0.5rem 1rem;
        font-size: 1rem;
      }
    </style>
  </head>
  <body>
    <h1>How to paste images</h1>
    <p>Hit <kbd>⌘</kbd> + <kbd>v</kbd> (for macOS) or <kbd>ctrl</kbd> + <kbd>v</kbd> (for other operating systems) to paste images anywhere in this page.</p>
    <button id="pasteButton">Paste Image</button>
    
    <input type="file" accept="image/*" id="fileInput" style="display: none;" />

    <script>
      document.getElementById('pasteButton').addEventListener('click', () => {
        document.getElementById('fileInput').click();
      });

      document.getElementById('fileInput').addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (file) {
          const blob = await readFileAsBlob(file);
          appendImage(blob);
        }
      });

      const readFileAsBlob = (file) => {
        return new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.onload = () => {
            resolve(reader.result);
          };
          reader.onerror = reject;
          reader.readAsDataURL(file);
        });
      };

      const appendImage = (blob) => {
        const img = document.createElement('img');
        img.src = blob;
        document.body.append(img);
      };
    </script>
  </body>
</html>
