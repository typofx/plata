<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copy code</title>
</head>
<body>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
  
            const urlParams = new URLSearchParams(window.location.search);
            const codigo = urlParams.get('code');
            
         
            navigator.clipboard.writeText(codigo).then(function() {
                console.log('Copied: ' + codigo);
                alert('Copied: ' + codigo);
      
              
            }, function(err) {
                console.error('Error: ', err);
                alert('Error');
            });
        });
    </script>
</body>
</html>
