<!DOCTYPE html>
<html>

<script>

function changeAppearance() {
	const elem = document.getElementById("info");

	if (elem.innerText == "on") elem.innerText = "off";
		else elem.innerText = "on";
}

</script>

<body>

<h1 id="info">off</h1>

<button type="button" onclick="changeAppearance()">Button</button>
 
</body>
</html>
