<?php
session_start(); // Start the session

// Check if the user is authenticated
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // User is not authenticated, redirect back to the login page
    header("Location: ../../painel");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
  <style>/* styles.css */

/* Center the container horizontally and vertically */
.container {
    width: 300px; /* Adjust the width as needed */
    margin: 0 auto;
    text-align: center;
    margin-top: 50px; /* Adjust the top margin as needed */
}

/* Apply styles to form elements */
label {
    display: block;
    text-align: left;
    font-weight: bold;
    margin-top: 10px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Style the password status message */
#senha_status {
    font-weight: bold;
}

/* Style the token status message (Add this in your HTML if needed) */
#token_status {
    font-weight: bold;
    margin-top: 10px;
    color: green; /* Default color for valid token status */
}
</style>
    
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <form method="post" action="cadastro.php" onsubmit="return validarForm()">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required onkeyup="validarSenha()"><br><br>

            <label for="confirm_password">Confirm password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required onkeyup="validarSenha()"><br><br>

            <label for="token">Enter Token:</label>
            <input type="text" name="token" id="token" required onkeyup="validarToken()"><br><br>

            <span id="senha_status"></span><br>

            <input type="submit" value="Registration">
        </form>
    </div>

    <script>
        function validarSenha() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;
            var senha_status = document.getElementById("senha_status");

            if (password === confirm_password) {
                senha_status.innerHTML = "passwords match";
                senha_status.style.color = "green";
            } else {
                senha_status.innerHTML = "Passwords do not match";
                senha_status.style.color = "red";
            }
        }

        function validarToken() {
            var token = document.getElementById("token").value;
            var token_status = document.getElementById("token_status");

            if (token === "token_especifico") {
                token_status.innerHTML = "valid token";
                token_status.style.color = "green";
            } else {
                token_status.innerHTML = "invalid token";
                token_status.style.color = "red";
            }
        }

        function validarForm() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            if (password !== confirm_password) {
                alert("Passwords do not match");
                return false; 
            }
            return true; 
        }
    </script>
</body>
</html>
