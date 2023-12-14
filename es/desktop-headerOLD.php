  <header>

  <link rel="stylesheet" href="style-header.css" media="screen">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


  <script>
  document.addEventListener("DOMContentLoaded", function () {
    var languageLink = document.querySelector("#language-dropdown a.dropdown-span");
    var subDropdown = document.getElementById("sub-dropdown");
    var aspectLink = document.querySelector("#language-dropdown a.aspect-link");

    languageLink.addEventListener("mouseover", function () {
      subDropdown.style.display = "block";
    });

    subDropdown.addEventListener("mouseleave", function () {
      subDropdown.style.display = "none";
    });

    aspectLink.addEventListener("mouseover", function () {
      subDropdown.style.display = "none";
    });

    aspectLink.addEventListener("mouseout", function (event) {
      if (!subDropdown.contains(event.relatedTarget)) {
        subDropdown.style.display = "none";
      }
    });
  });
</script>






    <nav>
      <table class="menu-container"> 
        <tr>
          <th class="table-space"><br></th>
          <th class="logo-align"><a href="https://www.plata.ie/"><img class="img-logo" src="https://www.plata.ie/images/PlataFont1.svg"></a></th>
          <th class="table-space-between"><br></th>
          <th class="align-text-dropdown">
            <table class="dropdown zero" style="text-align: center; width:100%;">
              <td class="menu-bar">
                  
                <table for="Project" class="zero" style="text-align: center; width:100%;">
                    <tr>
                    <th><a class="dropdown-span">Proyecto <span><svg xmlns="http://www.w3.org/2000/svg" width="14.2" height="11.5" fill="currentColor" class="bi bi-chevron-down rotate-on-hover" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg></span></th>
                    </tr> 
                </table>
                  
                <table class="dropdown-content">
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="#AnchorRoadmap">Hoja de ruta</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="#AnchorTokenDistribution">Distribución de Tokens</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://www.instagram.com/p/CkCE1n2q8mL/" target="_blank">Sketch Design</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://typofx.gitbook.io/" target="_blank">Litepaper</a></th>
                  </tr>
                </table>
              </td>
            </table>
          </th>
          <th class="align-text-dropdown">
            <table class="dropdown zero">
              <td class="menu-bar">
                  
                <table for="Products" class="zero">
                    <tr>
                    <th><a class="dropdown-span">Productos <span><svg xmlns="http://www.w3.org/2000/svg" width="14.2" height="11.5" fill="currentColor" class="bi bi-chevron-down rotate-on-hover" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg></span></th>
                    </tr> 
                </table>
                
                <table class="dropdown-content">
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://www.plata.ie/en/select/">Comprar Plata</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://plata.sellfy.store/" target="_blank">Merchant</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://opensea.io/collection/platatoken/" target="_blank">OpenSea (NFT)</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://www.plata.ie/listingplatform/">Lugares de Listado</a></th>
                  </tr>
                </table>
              </td>
            </table>
          </th>
          <th class="align-text-dropdown">
            <table class="dropdown zero">
              <td class="menu-bar">
                  
                <table for="Contact" class="zero">
                    <tr>
                    <th><a class="dropdown-span">Contacto <span><svg xmlns="http://www.w3.org/2000/svg" width="14.2" height="11.5" fill="currentColor" class="bi bi-chevron-down rotate-on-hover" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg></span></th>
                    </tr> 
                </table>
                
                <table class="dropdown-content">
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="#AnchorMeetTheTeam">Conocer al Equipo</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://linktr.ee/typofx/" target="_blank">Social Media</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://www.plata.ie/en/reportbug">Report Bug</a></th>
                  </tr>
                </table>
              </td>
            </table>
          </th>
          <th class="align-column">
            <table style="width: 100%; height: 28px; border-right: 1px solid black;">
              <th>
                  <span style="height:28px; margin-right:15px;"><a class="typo-fx dropdown-span" href="#">Typo FX</a></span> 
              </th>
            </table>
          </th>
          <th class="align-column-calc" style="padding-left:10px"><a href="https://www.plata.ie/en/mobile/calc/" target="_blank"><img id="header-icon-calc" class="padding-calc" src="https://www.plata.ie/images/header-icon-calc.svg"></a></th>
          <th class="align-column-trolley"><a href="https://www.plata.ie/en/mobile/select/"><img id="header-icon-trolley" class="padding-trolley" src="https://www.plata.ie/images/header-desktop-icon-trolley.svg"></a></th>
            
          <th class="align-column-config">
          <table class=" zero" style="text-align: center; height:100%; width:100%; padding-top:20px" >
            <tr>
              <td class="dropdown" style="text-align: center; height:100%; width:100%;">
                <span>
                  <a class="dropdown-span">
                    <img height="28px" id="header-icon-eng" class="padding-eng" src="https://www.plata.ie/images/header-icon-eng.svg">
                  </a>
                </span>
                <table class="dropdown-content zero" id="language-dropdown">
                  <tr>
                    <th class="th-dropdown-config-m">
                      <span onclick="showHideLanguageDropdown()"><a class="dropdown-span">Idioma (ES)</a></span>
                      <table class="sub-dropdown" id="sub-dropdown" style="display: none;">
                        <tr>
                          <td class="th-dropdown-config"><a class="dropdown-span" href="#">Português (PT)</a></td>
                        </tr>
                        <tr>
                          <td class="th-dropdown-config"><a class="dropdown-span" href="https://www.plata.ie/en/">English (EN)</a></td>
                        </tr>
                        <tr>
                          <td class="th-dropdown-config"><a class="dropdown-span" href="https://www.plata.ie/sn/">Espanõl (ES)</a></td>
                        </tr>
                      </table>
                    </th>
                  </tr>
                  <tr>
                    <th class="th-dropdown-config">
                    <a class="dropdown-span aspect-link" href="#">Aspecto (Claro)</a>
                    </th>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </th>
            
            
          <th class="table-space"><br></th>
        </tr>
      </table>
    </nav>
  </header>
