// Function to calculate the score as a percentage from 0 to 100%
function calculateScore() {
    var marketCap = document.getElementById("marketcap").value;
    var liquidity = document.getElementById("liquidity").value;
    var fullyDilutedMkc = document.getElementById("fullydilutedmkc").value;
    var circulatingSupply = document.getElementById("circulatingsupply").value;
    var maxSupply = document.getElementById("maxsupply").value;
    var totalSupply = document.getElementById("totalsupply").value;
    var price = document.getElementById("price").value;
    var graph = document.getElementById("graph").value;
    var holders = document.getElementById("holders").value;
    var tokenLogo = document.getElementById("tokenlogo").value;
    var socialMedia = document.getElementById("socialmedia").value;
    var metamaskButton = document.getElementById("metamaskbutton").value;

    // Check if all fields are set to "Unavailable"
    if (
        marketCap === "Z" &&
        liquidity === "Z" &&
        fullyDilutedMkc === "Z" &&
        circulatingSupply === "Z" &&
        maxSupply === "Z" &&
        totalSupply === "Z" &&
        price === "Z" &&
        graph === "Z" &&
        holders === "Z" &&
        tokenLogo === "Z" &&
        socialMedia === "Z" &&
        metamaskButton === "Z"
    ) {
        document.getElementById("score").value = 0; // All fields are "Unavailable"
        return;
    }

    // Calculate the sum of positive values
    var sumOfPositiveValues = (
        (marketCap === "K" ? 1 : 0) +
        (liquidity === "K" ? 1 : 0) +
        (fullyDilutedMkc === "K" ? 1 : 0) +
        (circulatingSupply === "K" ? 1 : 0) +
        (maxSupply === "K" ? 1 : 0) +
        (totalSupply === "K" ? 1 : 0) +
        (price === "K" ? 1 : 0) +
        (graph === "K" ? 1 : 0) +
        (holders === "K" ? 1 : 0) +
        (tokenLogo === "K" ? 1 : 0) +
        (socialMedia === "K" ? 1 : 0) +
        (metamaskButton === "K" ? 1 : 0)
    );

    // Check for "Wrong" fields and subtract 1 for each
    var wrongFields = (
        (marketCap === "W" ? 1 : 0) +
        (liquidity === "W" ? 1 : 0) +
        (fullyDilutedMkc === "W" ? 1 : 0) +
        (circulatingSupply === "W" ? 1 : 0) +
        (maxSupply === "W" ? 1 : 0) +
        (totalSupply === "W" ? 1 : 0) +
        (price === "W" ? 1 : 0) +
        (graph === "W" ? 1 : 0) +
        (holders === "W" ? 1 : 0) +
        (tokenLogo === "W" ? 1 : 0) +
        (socialMedia === "W" ? 1 : 0) +
        (metamaskButton === "W" ? 1 : 0)
    );

    // Calculate the score as a percentage from 0 to 100%
    var score = (sumOfPositiveValues - wrongFields) / sumOfPositiveValues * 100;

    // Update the score field in real-time
    document.getElementById("score").value = score.toFixed(2); // Set the value with two decimal places
}

// Add a change event listener to all select fields
var formFields = document.querySelectorAll("select"); // Select all select fields
formFields.forEach(function(field) {
    field.addEventListener("change", calculateScore);
});

// Call the function initially to calculate and display the initial score
calculateScore();
