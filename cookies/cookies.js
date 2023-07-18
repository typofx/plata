function hideTable() {
    var table = document.getElementById("cookiesTable");
    var body = document.getElementsByTagName("body")[0];
  
    body.classList.add("dark-background");
  
    table.style.display = "none";
  
    setTimeout(function() {
        body.classList.remove("dark-background");
    }, 1000);
}
