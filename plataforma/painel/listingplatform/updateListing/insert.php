<?php
// Include the file to check if the user is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';

// Get the logged-in user's email
$userNameUser = $userEmail;

// Display the logged-in user's email
echo "Logged-in user: " . htmlspecialchars($userNameUser) . "<br>";

// Get the current date and time in UTC
$currentDateTime = (new DateTime('now', new DateTimeZone('Etc/UTC')))->format('Y-m-d H:i:s');

// Include the database connection file
include 'conexao.php';

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if an image file is uploaded
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logo']['tmp_name'];
        $fileName = $_FILES['logo']['name'];
        $fileSize = $_FILES['logo']['size'];
        $fileType = $_FILES['logo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Maximum allowed file size (5MB)
        $maxFileSize = 5 * 1024 * 1024;
        if ($fileSize > $maxFileSize) {
            echo "Error: File size exceeds 5MB.";
            exit();
        }

        // Check if the file is an image
        $allowedFileExtensions = array('jpg', 'jpeg', 'png', 'gif', 'ico');
        if (in_array($fileExtension, $allowedFileExtensions)) {
            // Move the file to the desired directory
            $uploadFileDir = '/home2/granna80/public_html/website_d7042c63/images/icolog/';
            $destPath = $uploadFileDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // File moved successfully
                $logoFileName = $fileName;
            } else {
                echo "Error moving the file.";
                exit();
            }
        } else {
            echo "Error: Only JPG, JPEG, PNG, GIF, and ICO files are allowed.";
            exit();
        }
    } else {
        // Use a default image if no file is uploaded
        $defaultImagePath = '/home2/granna80/public_html/website_d7042c63/images/icolog/default.png';
        if (file_exists($defaultImagePath)) {
            $logoFileName = 'default.png';
        } else {
            echo "Error: Default image not found.";
            exit();
        }
    }

    // Get the values from the form fields
    $platform = $_POST['platform_name'] ?? '';
    $type = $_POST['type'] ?? '';
    $access = $_POST['access'] ?? '';
    $country = $_POST['country'] ?? '';
    $link = $_POST['link'] ?? '';
    $obs1 = $_POST['obs1'] ?? '';
    $obs2 = $_POST['obs2'] ?? '';
    $telegram = $_POST['telegram'] ?? '';
    $email = $_POST['zemail'] ?? '';

    // Check if $userNameUser is set before inserting into the database
    if (empty($userNameUser)) {
        die("Error: User email is not set or is empty.");
    }

    // Prepare and execute the SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.links (Platform, Link, Type, Access, Country, Obs1, Obs2, Telegram, Email, logo, InsertDateTime, insertBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ssssssssssss", $platform, $link, $type, $access, $country, $obs1, $obs2, $telegram, $email, $logoFileName, $currentDateTime, $userNameUser);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully.";
    } else {
        echo "Error executing the SQL statement: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Record</title>
    <!-- Bootstrap CSS for better styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container .btn {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Add New Record</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="logo" class="form-label">Logo (max 5MB):</label>
                <input type="file" class="form-control" name="logo" id="logo" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="platform" class="form-label">Platform Name:</label>
                <input type="text" class="form-control" name="platform_name" required>
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">Link:</label>
                <input type="text" class="form-control" id="link" name="link" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type:</label>
                <select class="form-select" name="type" id="type">
                    <option value="Index">Index</option>
                    <option value="Bot">Bot</option>
                    <option value="Tool">Tool</option>
                    <option value="Dex">Dex</option>
                    <option value="Audity">Audity</option>
                    <option value="Nft">Nft</option>
                    <option value="Audity/KYC">Audity/KYC</option>
                    <option value="Cex">Cex</option>
                    <option value="Newspaper">Newspaper</option>
                    <option value="Kyc">Kyc</option>
                    <option value="Voting">Voting</option>
                    <option value="Funding">Funding</option>
                    <option value="Chart">Chart</option>
                    <option value="Wallet">Wallet</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="access" class="form-label">Access:</label>
                    <input type="text" class="form-control" id="access" name="access" required>
                </div>
                <div class="col">
                    <label for="accessLink" class="form-label">View Analysis:</label>
                    <span id="accessLink" class="form-text"></span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="country" class="form-label">Country:</label>
                    <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <div class="col">
                    <label for="accessCountry" class="form-label">Country Information:</label>
                    <span id="accessCountry" class="form-text"></span>
                </div>
            </div>

            <div class="mb-3">
                <label for="obs1" class="form-label">Obs1:</label>
                <input type="text" class="form-control" name="obs1" onblur="validateInput(this)">
            </div>

            <div class="mb-3">
                <label for="obs2" class="form-label">Obs2:</label>
                <input type="text" class="form-control" name="obs2" onblur="validateInput(this)">
            </div>

            <div class="mb-3">
                <label for="telegram" class="form-label">Telegram:</label>
                <input type="text" class="form-control" name="telegram">
            </div>

            <div class="mb-3">
                <label for="zemail" class="form-label">Email:</label>
                <input type="email" class="form-control" name="zemail">
            </div>

            <div class="mb-3">
                <label for="user_edit" class="form-label">Inserted By:</label>
                <input type="text" class="form-control" name="user_edit" value="<?php echo htmlspecialchars($userNameUser); ?>" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Add New</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <!-- Bootstrap JS for better interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generateAccessLink() {
            var linkInput = document.getElementById("link");
            var accessLink = document.getElementById("accessLink");

            var linkValue = linkInput.value.trim();
            var url = new URL(linkValue);
            var formattedLink = url.hostname;

            var fullAccessLink = "https://www.similarweb.com/website/" + formattedLink;

            accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>SIMILARWEB</a>";
        }

        function generateCountryLink() {
            var linkInput = document.getElementById("link");
            var accessLink = document.getElementById("accessCountry");

            var linkValue = linkInput.value.trim();
            var url = new URL(linkValue);
            var formattedLink = url.hostname;

            var fullAccessLink = "https://who.is/whois/" + formattedLink;

            accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>WHO.IS</a>";
        }

        document.addEventListener("DOMContentLoaded", function() {
            var linkInput = document.getElementById("link");
            linkInput.addEventListener("input", generateAccessLink);
            linkInput.addEventListener("input", generateCountryLink);
        });

        function validateInput(input) {
            var regex = /^[a-zA-Z0-9]*$/;
            if (!regex.test(input.value)) {
                alert("Please enter only alphanumeric characters.");
                input.value = '';
            }
        }
    </script>
</body>

</html>