<?php

if (!isset($_GET['image']) || empty($_GET['image'])) {
    die('No image specified.');
}


$imagePath = $_GET['image'];


$imagePath = str_replace(['..', './'], '', $imagePath);


$uploadDir = '/images/uploads-scrapyard/equipaments/';


$fullImagePath = $_SERVER['DOCUMENT_ROOT'] . $uploadDir . $imagePath;
if (!file_exists($fullImagePath)) {
    die('Image not found.');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        img {
            max-width: 90%;
            max-height: 80vh;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        a {
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.2s ease-in-out;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <img src="<?= $uploadDir . htmlspecialchars($imagePath) ?>" alt="Image">
    <a href="javascript:window.close();">Close Window</a>
</body>
</html>
