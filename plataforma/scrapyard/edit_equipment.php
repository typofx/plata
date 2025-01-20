<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';


function sanitizeInput($data)
{
    return htmlspecialchars(trim($data));
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die('Invalid ID');
}

$equipment_query = $conn->query("SELECT * FROM granna80_bdlinks.scrapyard WHERE ID = $id");
$equipment = $equipment_query->fetch_assoc();

if (!$equipment) {
    die('Equipment not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conditions = sanitizeInput($_POST['conditions'] ?? null);
    $column_4 = sanitizeInput($_POST['column_4'] ?? null);
    $equipment_name = sanitizeInput($_POST['equipment'] ?? null);

    $brand_id = intval($_POST['brand_id'] ?? 0);
    $model_id = intval($_POST['model_id'] ?? 0);
    $config = sanitizeInput($_POST['config'] ?? null);
    $code = sanitizeInput($_POST['code'] ?? null);
    $description = sanitizeInput($_POST['description'] ?? null);
    $price = sanitizeInput($_POST['price'] ?? null);
    $ire = sanitizeInput($_POST['ire'] ?? null);
    $eur = sanitizeInput($_POST['eur'] ?? null);
    $returns = sanitizeInput($_POST['returns'] ?? null);

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/equipaments/';
    $imagePaths = [];


    if (!empty($_POST['cropped_images'])) {
        foreach ($_POST['cropped_images'] as $index => $croppedImage) {
            $data = explode(',', $croppedImage);
            $imageData = base64_decode($data[1]);

            $fileName = uniqid('cropped_') . '.png';
            $filePath = $uploadDir . $fileName;

            if (file_put_contents($filePath, $imageData)) {
                $imagePaths[] = $fileName;
            } else {
                $imagePaths[] = $equipment["image" . ($index + 1)] ?? null;
            }
        }
    } else {

        for ($i = 0; $i < 5; $i++) {
            if (!empty($_FILES['images']['tmp_name'][$i])) {
                $fileName = basename($_FILES['images']['name'][$i]);
                $fileSize = $_FILES['images']['size'][$i];
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

 
                if (in_array($fileType, ['png', 'jpg', 'jpeg']) && $fileSize <= 10 * 1024 * 1024) {
                    $newFileName = uniqid() . '.' . $fileType;
                    $filePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $filePath)) {
                        $imagePaths[] = $newFileName;
                    } else {
                        $imagePaths[] = $equipment["image" . ($i + 1)] ?? null;
                    }
                } else {
                    $imagePaths[] = $equipment["image" . ($i + 1)] ?? null;
                }
            } else {
                $imagePaths[] = $equipment["image" . ($i + 1)] ?? null; 
            }
        }
    }

    $eshop_ids = array_keys($_POST['eshops'] ?? []);
    $product_codes = $_POST['product_codes'] ?? [];
    $eshop_data = [];

    foreach ($eshop_ids as $eshop_id) {
        $eshop_product_code = sanitizeInput($product_codes[$eshop_id] ?? '');
        $eshop_data[] = "$eshop_id:$eshop_product_code";
    }

    $eshop_data_string = implode(',', $eshop_data);

    $brand_name = '';
    $model_name = '';

    if ($brand_id > 0) {
        $brand_result = $conn->query("SELECT brand_name FROM granna80_bdlinks.scrapyard_brands WHERE brand_id = $brand_id");
        $brand_name = $brand_result->fetch_assoc()['brand_name'] ?? '';
    }

    if ($model_id > 0) {
        $model_result = $conn->query("SELECT model_name FROM granna80_bdlinks.scrapyard_models WHERE model_id = $model_id");
        $model_name = $model_result->fetch_assoc()['model_name'] ?? '';
    }

    $query = "UPDATE granna80_bdlinks.scrapyard 
              SET Conditions = ?, Column_4 = ?, Equipment = ?, Brand = ?, Model = ?, Config = ?, Code = ?, Description = ?, Price = ?, IRE = ?, EUR = ?, Returns = ?, brand_id = ?, model_id = ?, eshop_data = ?, image1 = ?, image2 = ?, image3 = ?, image4 = ?, image5 = ?
              WHERE ID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssssssssssssi",
            $conditions,
            $column_4,
            $equipment_name,
            $brand_name,
            $model_name,
            $config,
            $code,
            $description,
            $price,
            $ire,
            $eur,
            $returns,
            $brand_id,
            $model_id,
            $eshop_data_string,
            $imagePaths[0],
            $imagePaths[1],
            $imagePaths[2],
            $imagePaths[3],
            $imagePaths[4],
            $id
        );

        if ($stmt->execute()) {
            echo "Equipment updated successfully!";
            echo "<script>window.location.href='edit_equipment.php?id=$id';</script>";
        } else {
            echo "Error updating the equipment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }
}

$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");
$models = $conn->query("SELECT model_id, model_name FROM granna80_bdlinks.scrapyard_models ORDER BY model_name");
$eshops = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_eshops ORDER BY name");
$equipments = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_equipment ORDER BY name");

$current_eshop_data = [];
if (!empty($equipment['eshop_data'])) {
    $eshop_entries = explode(',', $equipment['eshop_data']);
    foreach ($eshop_entries as $entry) {
        list($eshop_id, $product_code) = explode(':', $entry);
        $current_eshop_data[$eshop_id] = $product_code;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        .image-container {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .cropper-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0.3s, opacity 0.3s;
        }

        .cropper-modal.active {
            visibility: visible;
            opacity: 1;
        }

        .cropper-content {
            position: absolute;
            width: 200px;
            height: 200px;

            padding: 20px;
            border-radius: 5px;
            text-align: center;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }



        #images-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;

        }

        .image-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 150px;
   
            height: 160px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .image-container img {
            width: 130px;

            height: 100px;
      
            border-radius: 5px;
            border: 1px solid #ccc;
            object-fit: cover;
           
        }

        .upload-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            width: 100%;
        }

        .upload-controls input[type="file"] {
            font-size: 12px;
            text-align: center;
            width: 100%;
        }

        .crop-button {
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-align: center;
        }

        .crop-button:hover {
            background-color: #0056b3;
        }
    </style>


    <title>Edit Equipment</title>
</head>



<body>
    <h1>Edit Equipment</h1>
    <form id="form1" method="POST" enctype="multipart/form-data">

        <label for="uploadImages">Upload Images (max 5 images, 10MB each):</label>
        <div id="images-container" class="images-grid">
            <?php for ($i = 0; $i < 5; $i++): ?>
                <div class="image-container" data-index="<?php echo $i; ?>">
                    <img
                        id="image-<?php echo $i; ?>"
                        src="<?php echo !empty($equipment["image" . ($i + 1)])
                                    ? '/images/uploads-scrapyard/equipaments/' . $equipment["image" . ($i + 1)]
                                    : 'no-image-icon.png'; ?>"
                        alt="Equipment Image <?php echo $i + 1; ?>"
                        class="editable-image">
                    <div class="upload-controls">
                        <input
                            type="file"
                            name="images[]"
                            accept="image/*"
                            onchange="previewAndCrop(this, <?php echo $i; ?>)">
                        <input
                            type="hidden"
                            name="cropped_images[<?php echo $i; ?>]"
                            id="cropped-image-<?php echo $i; ?>"
                            value="">
                        <button type="button" class="crop-button" onclick="openCropper('image-<?php echo $i; ?>', <?php echo $i; ?>)">
                            Crop
                        </button>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <input type="hidden" name="cropped_images[]" id="cropped-images">

        <br><br><label for="conditions">Condition:</label>
        <select id="conditions" name="conditions">
            <option value="Used" <?= $equipment['Conditions'] === 'Used' ? 'selected' : '' ?>>Used</option>
            <option value="Used Working" <?= $equipment['Conditions'] === 'Used Working' ? 'selected' : '' ?>>Used Working</option>
            <option value="Broken" <?= $equipment['Conditions'] === 'Broken' ? 'selected' : '' ?>>Broken</option>
        </select><br><br>

        <!-- OEM -->
        <label for="column_4">OEM:</label>
        <select id="column_4" name="column_4" required>
            <option value="" disabled <?= empty($equipment['Column_4']) ? 'selected' : '' ?>>-- Select --</option>
            <option value="yes" <?= $equipment['Column_4'] === 'yes' ? 'selected' : '' ?>>YES</option>
            <option value="no" <?= $equipment['Column_4'] === 'no' ? 'selected' : '' ?>>NO</option>
        </select>
        <br><br>



        <label for="equipment">Select Equipment:</label><br>
        <select name="equipment" id="equipment" required>
            <option value="">-- Select Equipment --</option>
            <?php while ($equip_item = $equipments->fetch_assoc()): ?>
      
                <option value="<?= $equip_item['id'] ?>" <?= $equip_item['id'] == $equipment['Equipment'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($equip_item['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>


        <br><br> <label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" required>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>" <?= $brand['brand_id'] == $equipment['brand_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brand['brand_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>


        <label for="model_id">Model:</label>
        <select id="model_id" name="model_id" required>
            <?php while ($model = $models->fetch_assoc()): ?>
                <option value="<?= $model['model_id'] ?>" <?= $model['model_id'] == $equipment['model_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($model['model_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <!-- E-shops -->
        <label>Select E-shops:</label><br>
        <?php while ($eshop = $eshops->fetch_assoc()): ?>
            <input type="checkbox" name="eshops[<?= $eshop['id'] ?>]" <?= isset($current_eshop_data[$eshop['id']]) ? 'checked' : '' ?>>
            <?= htmlspecialchars($eshop['name']) ?>
            <label> - Product Code:</label>
            <input type="text" name="product_codes[<?= $eshop['id'] ?>]" value="<?= htmlspecialchars($current_eshop_data[$eshop['id']] ?? '') ?>" placeholder="Product Code"><br>
        <?php endwhile; ?>


        <br><label for="config">Configuration:</label>
        <input type="text" id="config" name="config" value="<?= htmlspecialchars($equipment['Config']) ?>"><br><br>


        <label for="code">Code:</label>
        <input type="text" id="code" name="code" value="<?= htmlspecialchars($equipment['Code']) ?>"><br><br>


        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?= htmlspecialchars($equipment['Description']) ?>"><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($equipment['Price']) ?>"><br><br>

        <!-- IRE -->
        <label for="ire">IRE:</label>
        <input type="text" id="ire" name="ire" value="<?= htmlspecialchars($equipment['IRE']) ?>"><br><br>

        <!-- EUR -->
        <label for="eur">EUR:</label>
        <input type="text" id="eur" name="eur" value="<?= htmlspecialchars($equipment['EUR']) ?>"><br><br>

        <!-- Returns -->
        <label for="returns">Returns:</label>
        <input type="text" id="returns" name="returns" value="<?= htmlspecialchars($equipment['Returns']) ?>"><br><br>


        <button type="submit">Update Equipment</button>
        <a href="index.php">[ Back ]</a>
    </form>

    <!-- Cropper Modal -->
    <div class="cropper-modal" id="cropperModal">
        <div class="cropper-content">

            <img id="cropperImage" style="max-width: 100%; height: auto;">
            <br><br>
            <button onclick="saveCrop()">Crop and Save</button>
            <button onclick="saveCropG()">Crop</button>
            <button onclick="closeCropper()">Cancel</button>
        </div>
    </div>


    <script>
        let croppers = [];

        function previewImage(input, index) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(`preview${index}`);
                    preview.src = e.target.result;
                    preview.style.display = 'block';

               
                    if (croppers[index]) {
                        croppers[index].destroy();
                    }
                    ccroppers[index] = new Cropper(preview, {
                        aspectRatio: 1, 

                    });

                };
                reader.readAsDataURL(file);
            }
        }

        function getCroppedImages() {
            const croppedData = [];
            croppers.forEach((cropper, index) => {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        fillColor: '#fff', 
                    });
                    croppedData.push(canvas.toDataURL());
                    const cropCanvas = document.getElementById(`cropCanvas${index}`);
                    cropCanvas.style.display = 'block';
                    cropCanvas.width = canvas.width;
                    cropCanvas.height = canvas.height;
                    const ctx = cropCanvas.getContext('2d');
                    ctx.drawImage(canvas, 0, 0);
                }
            });
            return croppedData;
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const croppedImages = getCroppedImages();

            croppedImages.forEach((dataURL, index) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `cropped_images[${index}]`;
                hiddenInput.value = dataURL;
                this.appendChild(hiddenInput);
            });
        });
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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

            // Get cropped image as base64 string
            const croppedCanvas = cropper.getCroppedCanvas({
                fillColor: '#fff', 
            });
            const croppedDataUrl = croppedCanvas.toDataURL();

            // Update the hidden input and the preview image
            const hiddenInput = document.getElementById(`cropped-image-${currentIndex}`);
            const imgElement = document.getElementById(currentImageId);
            hiddenInput.value = croppedDataUrl;
            imgElement.src = croppedDataUrl;

            // Destroy the cropper and close the modal
            cropper.destroy();
            cropper = null;
            cropperModal.classList.remove('active');

            // Submit the form
            const form = document.getElementById('form1');
            form.submit();


        }

        function saveCropG() {
            if (!cropper) return;

            // Get cropped image as base64 string
            const croppedCanvas = cropper.getCroppedCanvas({
                fillColor: '#fff', 
            });
            const croppedDataUrl = croppedCanvas.toDataURL();

            // Update the hidden input and the preview image
            const hiddenInput = document.getElementById(`cropped-image-${currentIndex}`);
            const imgElement = document.getElementById(currentImageId);
            hiddenInput.value = croppedDataUrl;
            imgElement.src = croppedDataUrl;

            // Destroy the cropper and close the modal
            cropper.destroy();
            cropper = null;
            cropperModal.classList.remove('active');

 


        }
        // Function to close the cropper without saving
        function closeCropper() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropperModal.classList.remove('active');
        }

        // Function to preview the selected image and open the cropper
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
    </script>

</body>

</html>