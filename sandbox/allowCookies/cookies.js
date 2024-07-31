function hideTable() {
    let table = document.getElementById("cookiesTable");
    let body = document.getElementsByTagName("body")[0];
  
    body.classList.add("dark-background");
  
    table.style.display = "none";
  
    setTimeout(function() {
        body.classList.remove("dark-background");
    }, 100);
}
