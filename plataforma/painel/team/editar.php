<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
$isGuest = ($userLevel === "guest");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .cropper-modal {
            display: none;
            position: fixed;
            opacity: 1 !important;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .cropper-modal.active {
            display: flex;
        }

        .cropper-content {

            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 80%;
            width: 600px;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }
    </style>
    <title>Edit Team Member</title>

</head>

<body>
    <h2>Edit Team Member</h2>

    <?php

    include "conexao.php";
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads';
    $error = "";

    if (isset($_GET['id']) && $_GET['id']) {

        $id = base64_decode($_GET['id']);

        // Fetch the last modified date and user UUID
        $sql = "SELECT uuid, last_modified, last_modified_user FROM granna80_bdlinks.team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastModified = $row['last_modified'];
            $last_modifiedUser = $row['last_modified_user'];
            $userUuidDb = $row['uuid'];

            // Default: allow editing for non-guests or guests who own the profile
            $canEdit = ($userLevel !== "guest") || ($userLevel === "guest" && $userUuidDb == $userUuid);

            // Additional check for guests: 14 days restriction
            if ($isGuest && $canEdit && $lastModified) {
                $lastModifiedDate = new DateTime($lastModified);
                $currentDate = new DateTime();
                $interval = $currentDate->diff($lastModifiedDate);
                $daysSinceLastEdit = $interval->days;

                if ($daysSinceLastEdit < 30) {
                    $canEdit = false;
                    $error = "You can only edit your profile every 1 month. Please try again after " . (30 - $daysSinceLastEdit) . " days. <br> Last modified by <b>" . $last_modifiedUser . "</b>" . " in " . $row['last_modified'];
                }
            }
        } else {
            $error = "No team member found with provided ID.";
            $canEdit = false;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $canEdit) {
            if (isset($_POST['cropped_image_1']) && !empty($_POST['cropped_image_1'])) {
                $cropped_image_data = $_POST['cropped_image_1'];
                $image_data = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $cropped_image_data));
                
             
                $image_name = "profile_picture_" . $id . ".png";
                $image_path = $file_path . '/' . $image_name;
                
              
                file_put_contents($image_path, $image_data);
                $profilePicture = 'uploads/' . $image_name;
            } elseif (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
              
                $file_ext = strtolower(pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION));
                $allowed_extensions = ["jpeg", "jpg", "png"];
                
                if (in_array($file_ext, $allowed_extensions)) {
                   
                    $image_name = "profile_picture_" . $id . "." . $file_ext;
                    $image_path = $file_path . '/' . $image_name;
                    
                   
                    move_uploaded_file($_FILES['profilePicture']['tmp_name'], $image_path);
                    $profilePicture = 'uploads/' . $image_name;
                } else {
                    $error = "Invalid file extension. Please use JPEG or PNG.";
                }
            }
            

            if (empty($error)) {
                $position = htmlspecialchars($_POST["position"]);
                $name = htmlspecialchars($_POST["name"]);
                $socialMedia = htmlspecialchars($_POST["socialMedia"]);
                $socialMedia1 = htmlspecialchars($_POST["socialMedia1"]);
                $socialMedia2 = htmlspecialchars($_POST["socialMedia2"]);
                $socialMedia3 = htmlspecialchars($_POST["socialMedia3"]);
                $socialMedia4 = htmlspecialchars($_POST["socialMedia4"]);
                $socialMedia5 = htmlspecialchars($_POST["socialMedia5"]);
                $socialMedia6 = htmlspecialchars($_POST["socialMedia6"]);
                $socialMedia7 = htmlspecialchars($_POST["socialMedia7"]);
                $socialMedia8 = htmlspecialchars($_POST["socialMedia8"]);
                $socialMedia9 = htmlspecialchars($_POST["socialMedia9"]);
                $active = isset($_POST["active"]) ? 1 : 0;
                $last_modified_user = $userName;

                if (isset($profilePicture)) {
                    $sql = "UPDATE granna80_bdlinks.team SET teamProfilePicture=?, teamPosition=?, teamName=?, teamSocialMedia0=?, teamSocialMedia1=?, teamSocialMedia2=?, teamSocialMedia3=?, teamSocialMedia4=?, teamSocialMedia5=?, teamSocialMedia6=?, teamSocialMedia7=?, teamSocialMedia8=?, teamSocialMedia9=?, active=?, last_modified=NOW(), last_modified_user=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssssssssssi", $profilePicture, $position, $name, $socialMedia, $socialMedia1, $socialMedia2, $socialMedia3, $socialMedia4, $socialMedia5, $socialMedia6, $socialMedia7, $socialMedia8, $socialMedia9, $active, $last_modified_user,  $id);
                } else {
                    $sql = "UPDATE granna80_bdlinks.team SET teamPosition=?, teamName=?, teamSocialMedia0=?, teamSocialMedia1=?, teamSocialMedia2=?, teamSocialMedia3=?, teamSocialMedia4=?, teamSocialMedia5=?, teamSocialMedia6=?, teamSocialMedia7=?, teamSocialMedia8=?, teamSocialMedia9=?, active=?, last_modified=NOW(), last_modified_user=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssssssssssi", $position, $name, $socialMedia, $socialMedia1, $socialMedia2, $socialMedia3, $socialMedia4, $socialMedia5, $socialMedia6, $socialMedia7, $socialMedia8, $socialMedia9, $active, $last_modified_user, $id);
                }

                if ($stmt->execute()) {
                    echo "Team member data updated successfully!";
                    echo "<script type='text/javascript'>window.location.href = 'index.php';</script>";

                } else {
                    $error = "Error updating data: " . $conn->error;
                }
            }
        }


        $sql = "SELECT * FROM granna80_bdlinks.team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    ?>

            <label for="last"> <?php

                                // Converta a data para string para garantir a comparação correta
                                $lastModified = strval($row['last_modified']);

                                // Verifica se o usuário e a data estão vazios ou se a data é a padrão zerada
                                if (
                                    empty($row['last_modified_user']) &&
                                    (empty($lastModified) || $lastModified === '0000-00-00 00:00:00')
                                ) {
                                    echo 'No modifications yet';
                                } else {
                                    // Determina o texto para o usuário modificado
                                    $modifiedUser = !empty($row['last_modified_user']) ? $row['last_modified_user'] : 'No modifications yet';

                                    // Determina o texto para a data modificada
                                    $modifiedDate = !empty($lastModified) && $lastModified !== '0000-00-00 00:00:00' ? $lastModified : 'No modifications yet';

                                    echo 'Last modified by: <b>' . $modifiedUser . '</b> in ' . $modifiedDate;
                                }

                                ?></label><br><br>

            <form id="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET['id'] ?>" method="post" enctype="multipart/form-data">






                <div class="form-group">
                    <label for="profilePicture">Profile Picture:</label><br>
                    <?php if (!empty($row['teamProfilePicture'])) { ?>
                        <img id="image-1" src="<?php echo htmlspecialchars('/images/' . $row['teamProfilePicture']); ?>" alt="Current Profile Picture" width="150"><br>
                        <?php if ($canEdit) { ?>
                            <button type="button" onclick="editCurrentImage(1)">Edit Current Image</button>
                        <?php } ?>
                    <?php } ?>
                    <input type="hidden" id="cropped-image-1" name="cropped_image_1">

                    <!-- Conditionally disable the file input based on canEdit -->
                    <input type="file" id="profilePicture" name="profilePicture" onchange="previewAndCrop(this, 1)" <?php if (!$canEdit) {
                                                                                                                        echo 'disabled';
                                                                                                                    } ?>><br><br>
                </div>



                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['teamName']); ?>" <?php if (!$canEdit) {
                                                                                                                        echo 'disabled';
                                                                                                                    } ?> required><br>

                <label for="position">Position:</label><br>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($row['teamPosition']); ?>" <?php if (!$canEdit) {
                                                                                                                                    echo 'disabled';
                                                                                                                                } ?> required><br>

                <label for="socialMedia">Whatsapp:</label><br>
                <input type="text" id="socialMedia" name="socialMedia" value="<?php echo htmlspecialchars($row['teamSocialMedia0']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia1">Instagram:</label><br>
                <input type="text" id="socialMedia1" name="socialMedia1" value="<?php echo htmlspecialchars($row['teamSocialMedia1']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia2">Telegram:</label><br>
                <input type="text" id="socialMedia2" name="socialMedia2" value="<?php echo htmlspecialchars($row['teamSocialMedia2']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia3">Facebook:</label><br>
                <input type="text" id="socialMedia3" name="socialMedia3" value="<?php echo htmlspecialchars($row['teamSocialMedia3']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia4">Github:</label><br>
                <input type="text" id="socialMedia4" name="socialMedia4" value="<?php echo htmlspecialchars($row['teamSocialMedia4']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia5">Email:</label><br>
                <input type="text" id="socialMedia5" name="socialMedia5" value="<?php echo htmlspecialchars($row['teamSocialMedia5']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia6">Twitter:</label><br>
                <input type="text" id="socialMedia6" name="socialMedia6" value="<?php echo htmlspecialchars($row['teamSocialMedia6']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia7">LinkedIn:</label><br>
                <input type="text" id="socialMedia7" name="socialMedia7" value="<?php echo htmlspecialchars($row['teamSocialMedia7']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia8">Twitch:</label><br>
                <input type="text" id="socialMedia8" name="socialMedia8" value="<?php echo htmlspecialchars($row['teamSocialMedia8']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="socialMedia9">Medium:</label><br>
                <input type="text" id="socialMedia9" name="socialMedia9" value="<?php echo htmlspecialchars($row['teamSocialMedia9']); ?>" <?php if (!$canEdit) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><br>

                <label for="active">Currently work here:</label><br>
                <input type="checkbox" id="active" name="active" <?php echo $row['active'] == 1 ? 'checked' : ''; ?> <?php if (!$canEdit) {
                                                                                                                            echo 'disabled';
                                                                                                                        } ?>><br>

                <?php if ($canEdit) { ?>
                    <a href="index.php">Back</a>
                    <input type="submit" value="Save">

                <?php }  ?>
            </form>

            <!-- Cropper Modal -->
            <div class="cropper-modal" id="cropperModal">
                <div class="cropper-content">
                    <img id="cropperImage" style="max-width: 100%; height: auto;">
                    <br><br>
                    <button onclick="saveCrop()">Crop and Save</button>
                    <button onclick="saveCropG()">Crop</button>
                    <button onclick="closeCropper()">Cancel</button>
                    <button type="button" onclick="rotateImage(90)">Rotate 90°</button>
                </div>
            </div>

            <script>
                let cropper;
                let cropperModal = document.getElementById('cropperModal');
                let cropperImage = document.getElementById('cropperImage');
                let currentImageId;
                let currentIndex;

                // Function to open the cropper
                function openCropper(imageId, index) {
                    currentImageId = imageId;
                    currentIndex = index;
                    const imgElement = document.getElementById(imageId);

                    cropperImage.src = imgElement.src;
                    cropperModal.classList.add('active');
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: NaN,
                        viewMode: 0,
                        dragMode: 'move',
                        autoCropArea: 1,
                        cropBoxResizable: true,
                        zoomable: true,
                        scalable: true,
                    });
                }

                // Function to save the cropped image
                function saveCrop() {
                    if (!cropper) return;

                    const croppedCanvas = cropper.getCroppedCanvas({
                        fillColor: '#fff',
                    });
                    const croppedDataUrl = croppedCanvas.toDataURL();

                    const hiddenInput = document.getElementById(`cropped-image-${currentIndex}`);
                    const imgElement = document.getElementById(currentImageId);
                    hiddenInput.value = croppedDataUrl;
                    imgElement.src = croppedDataUrl;

                    cropper.destroy();
                    cropper = null;
                    cropperModal.classList.remove('active');

                    const form = document.getElementById('form1');
                    form.submit();
                }

                function saveCropG() {
                    if (!cropper) return;

                    const croppedCanvas = cropper.getCroppedCanvas({
                        fillColor: '#fff',
                    });
                    const croppedDataUrl = croppedCanvas.toDataURL();

                    const hiddenInput = document.getElementById(`cropped-image-${currentIndex}`);
                    const imgElement = document.getElementById(currentImageId);
                    hiddenInput.value = croppedDataUrl;
                    imgElement.src = croppedDataUrl;

                    cropper.destroy();
                    cropper = null;
                    cropperModal.classList.remove('active');
                }

                function closeCropper() {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    cropperModal.classList.remove('active');
                }

                function previewAndCrop(input, index) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgElement = document.getElementById(`image-${index}`);
                            imgElement.src = e.target.result;
                            openCropper(`image-${index}`, index);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function rotateImage(degrees) {
                    if (cropper) {
                        cropper.rotate(degrees);
                    }
                }

                function editCurrentImage(index) {
                    const imgElement = document.getElementById(`image-${index}`);
                    openCropper(`image-${index}`, index);
                }
            </script>
    <?php
        } else {
            $error = "No team member found with provided ID.";
        }
    }


    if (!empty($error)) {
        echo "<p>$error</p>";
        echo "<a href='index.php'>[ Back ]</a>";
    }

    $conn->close();
    ?>
</body>

</html>