  <header>

  <link rel="stylesheet" href="https://www.plata.ie/en/style-header.css" media="screen">
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
            <table class="dropdown">
              <td class="menu-bar">
                  
                <table for="Project" >
                    <tr>
                    <th><a class="dropdown-span">Menu<span><svg xmlns="http://www.w3.org/2000/svg" width="14.2" height="11.5" fill="currentColor" class="bi bi-chevron-down rotate-on-hover" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg></span></th>
                    </tr> 
                </table>
                  
                <table class="dropdown-content">
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://plata.ie/plataforma/painel/menu.php">Main Root</a></th>
                  </tr>
                <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="" target="_blank">X</a></th>
                    </tr>
                <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="" target="_blank">X</a></th>
                </tr>
                <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="">X</a></th>
                </tr>
                </table>
                
              </td>
            </table>
          </th>
          <th class="align-text-dropdown">
            <table class="dropdown">
              <td class="menu-bar">
                  
                <table for="Products">
                    <tr>
                    <th><a class="dropdown-span">Edit <span><svg xmlns="http://www.w3.org/2000/svg" width="14.2" height="11.5" fill="currentColor" class="bi bi-chevron-down rotate-on-hover" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg></span></th>
                    </tr> 
                </table>
                
                <table class="dropdown-content">
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://plata.ie/plataforma/painel/insert.php">Add New Record</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><form action="https://plata.ie/plataforma/painel/new_status.php" method="post" target="_blank"><button type="submit" name="status">Update Status</button></form></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://github.com/typofx/plata-plataforma/blob/main/found.txt" target="_blank">Check New Ones</a></th> 
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://typofx.gitbook.io/" target="_blank">X</a></th>
                  </tr>
                </table>
                
                
              </td>
            </table>
            
          </th>
          <th class="align-text-dropdown">
            <table class="dropdown">
              <td class="menu-bar">
                  
                <table for="Contact">
                    <tr>
                    <th><a class="dropdown-span">Control Panel<span><svg xmlns="http://www.w3.org/2000/svg" width="14.2" height="11.5" fill="currentColor" class="bi bi-chevron-down rotate-on-hover" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg></span></th>
                    </tr> 
                </table>
                
                <table class="dropdown-content">
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="#AnchorMeetTheTeam">Edit User</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://plata.ie/plataforma/painel/roadmap" target="_blank">Edit Roadmap</a></th>
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://www.plata.ie/en/reportbug">Edit Team</a></th> 
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://www.plata.ie/reg">Token Info</a></th> 
                  </tr>
                  <tr>
                    <th class="th-dropdown"><a class="dropdown-span" href="https://gator2116.hostgator.com:2083/cpsess0365615392/awstats.pl?config=plata.ie&ssl=1&lang=en" target="_blank">Site Stats</a></th> 
                  </tr>
                  
                  
                </table>
              </td>
            </table>
          </th>
          <th class="align-column">
            <span><a class="typo-fx dropdown-span" href="" target="_blank">X</a></span>
          </th>
          <th class="align-column-calc"><a href="https://www.plata.ie/en/mobile/calc/" target="_blank"><img id="header-icon-calc" class="img-menu" src="https://www.plata.ie/images/header-icon-calc.svg"></a></th>
          <th class="align-column-trolley"><a href="https://www.plata.ie/en/mobile/select/"><img id="header-icon-trolley" class="img-menu" src="https://www.plata.ie/images/header-desktop-icon-trolley.svg"></a></th>
            
          <th class="align-column-config">
          <table class="dropdown" style="height: 100%;width: 100%;">
            <tr>
              <td>
                <span>
                  <a class="dropdown-span">
                    <img height="28px" id="header-icon-eng" class="img-menu" src="https://www.plata.ie/images/header-icon-eng.svg">
                  </a>
                </span>
                <table class="dropdown-content" id="language-dropdown">
                  <tr>
                    <th class="th-dropdown-config-m">
                      <span onclick="showHideLanguageDropdown()"><a class="dropdown-span"> Language (EN) </a></span>
                      <table class="sub-dropdown" id="sub-dropdown" style="display: none;">
                        <tr>
                          <td class="th-dropdown-config"><a class="dropdown-span" href="https://www.plata.ie/pt/"> Português </a></td>
                        </tr>
                        <tr>
                          <td class="th-dropdown-config"><a class="dropdown-span" href="https://www.plata.ie/en/"> English </a></td>
                        </tr>
                        <tr>
                          <td class="th-dropdown-config"><a class="dropdown-span" href="https://www.plata.ie/es/"> Español </a></td>
                        </tr>
                      </table>
                    </th>
                  </tr>
                  <tr>
                    <th class="th-dropdown-config-m"> <a class="dropdown-span" href="#"> Theme (Light) </a>
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
