function generateCountryLink() {
        var linkInput = document.getElementById("link");
        var accessLink = document.getElementById("accessCountry");

        var linkValue = linkInput.value.trim(); // Remove leading/trailing whitespace
        var url = new URL(linkValue);
        var formattedLink = url.hostname; // Extract the hostname (TLD)

        var fullAccessLink = "https://who.is/whois/" + formattedLink;

        accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>WHO.IS</a>";
    }

    document.addEventListener("DOMContentLoaded", function() {
        var linkInput = document.getElementById("link");
        linkInput.addEventListener("input", generateCountryLink);
    });
