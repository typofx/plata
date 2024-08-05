<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
</head>

<body>
    <h2>Add Member data</h2>
    <form action="insert.php" method="post" enctype="multipart/form-data" onsubmit="return validarForm()">
        <label for="profilePicture">Profile Picture:</label><br>
        <input type="file" id="profilePicture" name="profilePicture" required><br>

        

        <label for="position">Position:</label><br>
        <input type="text" id="position" name="position" required><br>

        <label for="socialMedia">Whatsapp:</label><br>
        <input type="text" id="socialMedia" name="socialMedia"><br>

        <label for="socialMedia1">Instagram:</label><br>
        <input type="text" id="socialMedia1" name="socialMedia1"><br>

        <label for="socialMedia2">Telegram:</label><br>
        <input type="text" id="socialMedia2" name="socialMedia2"><br>

        <label for="socialMedia3">Facebook:</label><br>
        <input type="text" id="socialMedia3" name="socialMedia3"><br>

        <label for="socialMedia4">Github:</label><br>
        <input type="text" id="socialMedia4" name="socialMedia4"><br>

        <label for="socialMedia5">Social Email:</label><br>
        <input type="text" id="socialMedia5" name="socialMedia5"><br>

        <label for="socialMedia6">Twitter:</label><br>
        <input type="text" id="socialMedia6" name="socialMedia6"><br>

        <label for="socialMedia7">LinkedIn:</label><br>
        <input type="text" id="socialMedia7" name="socialMedia7"><br>

        <label for="socialMedia8">Twitch:</label><br>
        <input type="text" id="socialMedia8" name="socialMedia8"><br>

        <label for="socialMedia9">Medium:</label><br>
        <input type="text" id="socialMedia9" name="socialMedia9"><br>


        <h2>Add Plataforma data</h2>


        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" name="last_name" required><br>

        <label for="email">User Email:</label><br>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required onkeyup="validarSenha()"><br>

        <label for="confirm_password">Confirm password:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required onkeyup="validarSenha()"><br>


        <label for="access_level">Access Level:</label><br>
        <select id="access_level" name="level" required><br>
            <option value="">Select access level</option>
            <option value="guest">Guest</option>
            <option value="admin">Admin</option>
            <option value="root">Root</option>
            <option value="block">Block</option>

        </select><br>


        <label for="token">Enter Token:</label><br>
        <input type="text" name="token" id="token" required onkeyup="validarToken()"><br>

        <span id="senha_status"></span><br>







        <a href="index.php">Back</a>
        <input type="submit" value="Submit">
    </form>

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