  <script>
                function generateAccessLink() {
                        var linkInput = document.getElementById("link");
                        var accessLink = document.getElementById("accessLink");

                        var linkValue = linkInput.value;
                        var formattedLink = linkValue.replace(/^https:\/\/www\./i, "");

                        var fullAccessLink = "https://www.similarweb.com/website/" + formattedLink;


                        accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>Access Link</a>";
                }

                document.addEventListener("DOMContentLoaded", function() {
                        var linkInput = document.getElementById("link");
                        linkInput.addEventListener("input", generateAccessLink);
                });
        </script>
