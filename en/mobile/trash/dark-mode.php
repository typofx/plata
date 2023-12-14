<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  background-color: white;
  color: black;
}

.dark-mode {
  background-color: black;
  color: white;
}

.dark-mode .plata-font {
  background-color: black;
  color: white;
}

</style>
</head>
<body>

<h2>Toggle Dark/Light Mode</h2>
<p>Click the button to toggle between dark and light mode for this page.</p>

<button onclick="myFunction()">Toggle dark mode</button>

<script>
function myFunction() {
   var element = document.body;
   element.classList.toggle("dark-mode");
}
</script>

</body>
</html>

