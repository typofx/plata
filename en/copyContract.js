// Copy Hash Icon Contract

//<button onclick = "copyContract()">Copy text</button>

//<button onclick = "copyContractMobile()">Copy text</button>

// Meet The Team // Thiago Grey 15 Julio 5

//<a class="u-social-url" target="_blank" name="DELETE"><span class="u-custom-item u-icon u-icon-circle u-social-facebook u-social-icon u-text-grey-15 u-icon-17"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112.2 112.2" style="undefined"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-ff2a"></use></svg><svg x="0px" y="0px" viewBox="0 0 112.2 112.2" id="svg-ff2a" class="u-svg-content"><path d="M75.5,28.8H65.4c-1.5,0-4,0.9-4,4.3v9.4h13.9l-1.5,15.8H61.4v45.1H42.8V58.3h-8.8V42.4h8.8V32.2 c0-7.4,3.4-18.8,18.8-18.8h13.8v15.4H75.5z"></path></svg></span>

//</a>

function copyContract() {
  var copyText = "0xc298812164bd558268f51cc6e3b8b5daaf0b6341";
  var modal = document.getElementById("myModal");
  var messageLine1 = document.getElementById("modal-messageLine1");
  var messageLine2 = document.getElementById("modal-messageLine2");


  modal.style.display = "block";
  modal.style.opacity = 0;


  var modalFadeIn = setInterval(function() {
    if (modal.style.opacity < 1) {
      modal.style.opacity = parseFloat(modal.style.opacity) + 0.1;
    } else {
      clearInterval(modalFadeIn);
    }
  }, 50);


  navigator.clipboard.writeText(copyText);


  messageLine1.textContent = "Plata Token Contract Address\n 0xc29...b6341 Copied!";



  setTimeout(function() {
    var modalFadeOut = setInterval(function() {
      if (modal.style.opacity > 0) {
        modal.style.opacity = parseFloat(modal.style.opacity) - 0.1;
      } else {
        clearInterval(modalFadeOut);
        modal.style.display = "none";
      }
    }, 50);
  }, 1000);
}


  function copyContractMobile() {
    let textoCopiado = document.getElementById("texto");
    textoCopiado.select();
    textoCopiado.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("$PLT Plata Contract Address Copied!" + textoCopiado.value);
}

  

 

  

  



