// <button onclick = "copyContract()">Text</button>

function copyContract() {
  var copyText = "0xc58A1559b566863668A8C7316da00faC01202300";
  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText);
  /* Alert the copied text */
  alert("$PLT Plata Contract Hash Address Copied! " copyText);
}
