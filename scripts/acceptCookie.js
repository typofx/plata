
    document.addEventListener("DOMContentLoaded", function() {
      const cookieModal = document.getElementById("cookieModal");
      const overlay = document.getElementById("cookieOverlay");
      const acceptCookiesButton = document.getElementById("acceptCookies");
      const declineCookiesButton = document.getElementById("declineCookies");

      // Check if user has previously accepted cookies
      const hasAcceptedCookies = document.cookie.includes("acceptedCookies=true");

      if (!hasAcceptedCookies) {
        cookieModal.style.display = "block";
        overlay.style.display = "block";
      }

      acceptCookiesButton.addEventListener("click", function() {
        setCookie(true);
      });

      declineCookiesButton.addEventListener("click", function() {
        disableAllCookies();
        setCookie(false);
      });

      function setCookie(accepted) {
        cookieModal.style.display = "none";
        overlay.style.display = "none";

        const expirationDate = new Date();
        expirationDate.setDate(expirationDate.getDate() + 30);

        if (accepted) {
          document.cookie = "acceptedCookies=true; expires=" + expirationDate.toUTCString();
        } else {
          document.cookie = "acceptedCookies=false; expires=" + expirationDate.toUTCString();
        }
      }

      function disableAllCookies() {
        const cookies = document.cookie.split("; ");
        for (let i = 0; i < cookies.length; i++) {
          const cookie = cookies[i];
          const eqPos = cookie.indexOf("=");
          const cookieName = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
          document.cookie = `${cookieName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
        }
      }
    });
