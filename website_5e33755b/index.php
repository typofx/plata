<!DOCTYPE html>
<html>
<script>

// Verificar o User-Agent do navegador
var userAgent = navigator.userAgent;

// Expressões regulares para identificar dispositivos móveis
var mobileRegex = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i;

// Verificar se o User-Agent corresponde a um dispositivo móvel
if (mobileRegex.test(userAgent)) {
  // Redirecionar para outra página se for um dispositivo móvel
  window.location.href = "mobile";
} else {
  // Fique na página atual se for um computador
  console.log("Desktop-mode.");
}


</script>
<head>
    <title>Plataforma - Plata Token Listed Places</title>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/../languages/languages.php';?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/../es/desktop-header.php"; ?>







<style>
    body {
            background-color: #e5e5e5;
    }
</style>




<?php include 'listar.php'; ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/../es/desktop-footer.php';?>



</body>
</html>
