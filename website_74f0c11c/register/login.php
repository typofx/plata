<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

</head>
<body>

<div class="form-container">
    <form method="post" action="login_process.php">
        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label for="token">Enter Token:</label><br>
        <input type="text" name="token" id="token" required><br><br>

        <input type="submit" value="Login">
        <a href='../index.php'>Back</a>
    </form>
</div>

</body>
</html>
