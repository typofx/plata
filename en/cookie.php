<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <title>Cookie Consent Modal</title>
    <style>body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    z-index: 1000;
}

.modal-content {
    text-align: center;
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 900;
}

.btn {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.button-container {
    margin-top: 20px;
}
</style>
</head>
<body>
<div id="cookieModal" class="modal">
        <div class="modal-content">
            <h2>Utilizamos cookies</h2>
            <p>Este site utiliza cookies para garantir a melhor experiência ao usuário.</p>
            <div class="button-container">
                <button id="acceptCookies" class="btn">Aceitar</button>
            </div>
        </div>
    </div>
    <div id="overlay" class="overlay"></div>
    <script>document.addEventListener("DOMContentLoaded", function () {
    const cookieModal = document.getElementById("cookieModal");
    const overlay = document.getElementById("overlay");
    const acceptCookiesButton = document.getElementById("acceptCookies");

    // Check if user has previously accepted cookies
    const hasAcceptedCookies = document.cookie.includes("acceptedCookies=true");

    if (!hasAcceptedCookies) {
        cookieModal.style.display = "block";
        overlay.style.display = "block";
    }

    acceptCookiesButton.addEventListener("click", function () {
        cookieModal.style.display = "none";
        overlay.style.display = "none";

        // Set a cookie to remember user's choice for 30 days
        const expirationDate = new Date();
        expirationDate.setDate(expirationDate.getDate() + 30);
        document.cookie = "acceptedCookies=true; expires=" + expirationDate.toUTCString();
    });
});

</script>
</body>
</html>
