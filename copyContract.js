//<input type="text" value="Hello World" id="myInput">
//<button onclick="copyContract()">Copy text</button>


function copyContract() {
  /* Get the text field */
//  var copyText = "0x0049d29Bf24AEda8dC8a23cc1fe4176B92f16F7e";
  /* Copy the text inside the text field */
  navigator.clipboard.writeText("0x0049d29Bf24AEda8dC8a23cc1fe4176B92f16F7e");
  /* Alert the copied text */
  alert("$TCU Contract Address Copied! 0x0049d29Bf24AEda8dC8a23cc1fe4176B92f16F7e");
}
