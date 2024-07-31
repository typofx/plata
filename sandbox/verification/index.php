<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plata.ie | Official Verification Channel</title>
    <!--
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        p {
            font-size: 18px;
            color: #555;
        }
    </style>
    -->
</head>

<body>
    <h2>Plata.ie | Official Verification Channel</h2>
    <form method="post">
        <input type="text" id="username" name="username" placeholder="Enter username">
        <input type="submit" name="search" value="Search">
    </form>

    <?php
    if (isset($_POST['search'])) {
        $searchedName = $_POST['username'];

        // Remove the @ if present
        if (strpos($searchedName, '@') === 0) {
            $searchedName = substr($searchedName, 1);
        }

        // List of available names and their information
        $information = array(
            "AdrielDias" => "AdrielDias is part of Typo FX Team, position: Web3 DEV",
            "AdamSoares" => "AdamSoares is part of Typo FX Team, position: CEO"
        );

        // Check if the searched name is in the list and return the corresponding information
        if (array_key_exists($searchedName, $information)) {
            echo "<p>@" . $information[$searchedName] . "</p>";
        } else {
            echo "<p>Name not found</p>";
        }
    }
    ?>
</body>

</html>