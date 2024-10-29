<?php
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>

    <style>
     
        .form-container label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

    

      

 

      
    </style>
</head>

<body>
    <div class="container">
        <h2>User Registration</h2>
        <div class="form-container">
            <form method="post" action="registration.php" onsubmit="return validarForm()">
                <label for="name">Name:</label>
                <input type="text" name="name" required>

                <label for="last_name">Surname:</label>
                <input type="text" name="last_name" required>

                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="birth">Date of birth:</label>
                <input type="date" name="birth" required>

                <label for="ddi">DDI:</label>
                <input type="text" name="ddi" id="ddi">

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone">

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required onkeyup="validarSenha()">

                <label for="confirm_password">Confirm password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required onkeyup="validarSenha()">

                <label for="token">Enter Token:</label>
                <input type="text" name="token" id="token" required onkeyup="validarToken()">

                <span id="senha_status"></span> 

                <br><br><input type="submit" value="Register"><br><br>
                <a href='../index.php'>Back</a>
            </form>
        </div>

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