<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>
  

</head>

<body>
    <div class="container">
        <h2>User Registration</h2>
        <form method="post" action="cadastro.php" onsubmit="return validarForm()">

            <label for="name">Name: </label>
            <input type="text" name="name" required><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required onkeyup="validarSenha()"><br><br>

            <label for="confirm_password">Confirm password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required onkeyup="validarSenha()"><br><br>


            <label for="access_level">Access Level:</label>
            <select id="access_level" name="level" required>
                <option value="">Select access level</option>
                <option value="guest">Guest</option>
                <option value="admin">Admin</option>
                <option value="root">Root</option>

            </select><br><br>


            <label for="token">Enter Token:</label>
            <input type="text" name="token" id="token" required onkeyup="validarToken()"><br><br>

            <span id="senha_status"></span><br>

            <input type="submit" value="Registration">
            <a href='index.php'>Back</a>
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