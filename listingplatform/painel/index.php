<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
        }

        img {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
        }

        label, input {
            margin-bottom: 10px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <img src="https://plata.ie/images/platatoken1kpx.png" alt="Logo">

    <h1>Login</h1>
    <form action="login_process.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="token">Enter Token:</label>
        <input type="text" name="token" id="token" size="6" required autocomplete="off">
        
        <button type="submit">Login and Authenticate</button>
    </form>
</body>
</html>
