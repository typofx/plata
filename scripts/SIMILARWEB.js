   function generateAccessLink() {
        var linkInput = document.getElementById("link");
        var accessLink = document.getElementById("accessLink");

        var linkValue = linkInput.value.trim(); // Remove leading/trailing whitespace
        var url = new URL(linkValue);
        var formattedLink = url.hostname; // Extract the hostname (TLD)

        var fullAccessLink = "https://www.similarweb.com/website/" + formattedLink;

        accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>SIMILARWEB</a>";
    }

    document.addEventListener("DOMContentLoaded", function() {
        var linkInput = document.getElementById("link");
        linkInput.addEventListener("input", generateAccessLink);
    });
