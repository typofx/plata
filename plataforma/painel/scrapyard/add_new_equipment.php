<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

// Function to sanitize input data
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data));
}

// Directory to store uploaded images
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/equipaments/';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $conditions = sanitizeInput($_POST['conditions'] ?? null);
    $column_4 = sanitizeInput($_POST['column_4'] ?? null);
    $equipment = sanitizeInput($_POST['equipment'] ?? null);

    $brand_id = intval($_POST['brand_id'] ?? 0);
    $model_id = intval($_POST['model_id'] ?? 0);
    $config = sanitizeInput($_POST['config'] ?? null);
    $code = sanitizeInput($_POST['code'] ?? null);
    $description = sanitizeInput($_POST['description'] ?? null);
    $price = sanitizeInput($_POST['price'] ?? null);
    $ire = sanitizeInput($_POST['ire'] ?? null);
    $eur = sanitizeInput($_POST['eur'] ?? null);
    $returns = sanitizeInput($_POST['returns'] ?? null);
    $status = sanitizeInput($_POST['status'] ?? 'Active');
    $sold_date_input = sanitizeInput($_POST['sold_date'] ?? null);
    $sold_date_for_db = null; // Padrão é NULL

    $qtd = intval($_POST['qtd'] ?? 1);
    $location = sanitizeInput($_POST['location'] ?? null);


    if (!empty($sold_date_input) && $status === 'Sold') {
        $date_obj = DateTime::createFromFormat('d/m/Y', $sold_date_input);
        if ($date_obj) {
            $sold_date_for_db = $date_obj->format('Y-m-d');
        }
    }

    $eshop_ids = $_POST['eshops'] ?? [];
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


    $utc_now = new DateTime('now', new DateTimeZone('UTC'));
    $last_updated_utc = $utc_now->format('Y-m-d H:i:s');
    // Handle image uploads and cropped images
    $imagePaths = [];

    // Check for cropped images
    if (isset($_POST['cropped_images']) && is_array($_POST['cropped_images'])) {
        foreach ($_POST['cropped_images'] as $index => $base64Image) {
            if (!empty($base64Image)) {
                // Decode the Base64 string
                $imageData = explode(',', $base64Image);
                if (count($imageData) == 2) {
                    $imageBase64 = $imageData[1];
                    $imageDecoded = base64_decode($imageBase64);

                    // Save the decoded image
                    $newFileName = uniqid() . '.png';
                    $filePath = $uploadDir . $newFileName;

                    if (file_put_contents($filePath, $imageDecoded)) {
                        $imagePaths[] = $newFileName;
                    } else {
                        $imagePaths[] = null;
                    }
                }
            } else {
                $imagePaths[] = null;
            }
        }
    }

    // Handle traditional file uploads
    for ($i = count($imagePaths); $i < 5; $i++) {
        if (!empty($_FILES['images']['tmp_name'][$i])) {
            $fileName = basename($_FILES['images']['name'][$i]);
            $fileSize = $_FILES['images']['size'][$i];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file type and size
            if (in_array($fileType, ['png', 'jpg', 'jpeg']) && $fileSize <= 10 * 1024 * 1024) {
                $newFileName = uniqid() . '.' . $fileType;
                $filePath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $filePath)) {
                    $imagePaths[] = $newFileName;
                } else {
                    $imagePaths[] = null; // Add null for missing files
                }
            } else {
                $imagePaths[] = null;
            }
        } else {
            $imagePaths[] = null;
        }
    }

    // Fill empty slots in case fewer than 5 images are uploaded
    while (count($imagePaths) < 5) {
        $imagePaths[] = null;
    }

    // Insert data into scrapyard table
    $query = "INSERT INTO granna80_bdlinks.scrapyard 
            (Conditions, Column_4, Equipment, Brand, Model, Config, Code, Description, Price, IRE, EUR, Returns, brand_id, model_id, eshop_data, image1, image2, image3, image4, image5, last_edited_by, last_updated, status, sold_date, QTD, location) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssssssssssssssssss",
            $conditions,
            $column_4,
            $equipment,
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
            $userEmail,
            $last_updated_utc,
            $status,
            $sold_date_for_db,
            $qtd,
            $location
        );

        if ($stmt->execute()) {
            echo "Equipment added successfully!";
        } else {
            echo "Error adding the equipment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }
}

// Fetch brands, models, e-shops, and equipment
$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");
$models = $conn->query("SELECT model_id, model_name FROM granna80_bdlinks.scrapyard_models ORDER BY model_name");
$eshops = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_eshops ORDER BY name");
$equipaments = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_equipment ORDER BY name");
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <title>Add New Product</title>
    <style>
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 9999;
            /* Sit on top */
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);

            width: 20%;

        }

        .modal-overlay {
            display: none;

            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);

            z-index: 9998;

        }


        .modal-content {
            position: relative;
            z-index: 9999;

            margin: auto;
            padding: 20px;

            text-align: center;

        }

        .cropper-modal {
            background-color: transparent;
            opacity: 1;
        }


        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-content button {
            margin: 5px;
            padding: 8px 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        .modal-content button:last-child {
            background-color: #dc3545;
        }

        .modal-content button:last-child:hover {
            background-color: #b21f2d;
        }

        /* Container for all image cards */
        .image-cards-container {
            display: flex;

            gap: 20px;
            /* Spacing between cards */
        }

        /* Each individual image card */
        .image-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 150px;
            background-color: #ddd;
            /* Fixed width for each card */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Card style for image preview */
        .card {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100px;
            /* Fixed width for image preview */
            height: 100px;
            /* Fixed height for image preview */
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        .card img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Optional: Add a hover effect on cards */
        .card:hover {
            border-color: #3b82f6;
            background-color: #e0f3ff;
        }

        /* Optional: Center the upload labels */
        label {
            text-align: center;
            margin-bottom: 8px;
        }



        .upload-controls input[type="file"] {
            font-size: 12px;
            text-align: center;
            width: 100%;
        }
    </style>

</head>

<body>
    <h1>Add New Product</h1>
    <form method="POST" enctype="multipart/form-data">

        <div class="image-cards-container">
            <div class="image-card">
                <label for="image1">Upload Image 1:</label><br>
                <div class="upload-controls">
                    <input type="file" name="images[]" id="image1" accept="image/png, image/jpeg, image/jpg" onchange="previewAndCrop(this, 1)">
                </div>
                <div class="card">
                    <img id="preview1" alt="Preview 1" style="display: none;">
                    <input type="hidden" id="cropped-image-1" name="cropped_images[0]">
                </div>
            </div>

            <div class="image-card">

                <label for="image2">Upload Image 2:</label><br>
                <div class="upload-controls">
                    <input type="file" name="images[]" id="image2" accept="image/png, image/jpeg, image/jpg" onchange="previewAndCrop(this, 2)">
                </div>
                <div class="card">
                    <img id="preview2" alt="Preview 2" style="display: none;">
                    <input type="hidden" id="cropped-image-2" name="cropped_images[1]">
                </div>
            </div>

            <div class="image-card">
                <label for="image3">Upload Image 3:</label><br>
                <div class="upload-controls">
                    <input type="file" name="images[]" id="image3" accept="image/png, image/jpeg, image/jpg" onchange="previewAndCrop(this, 3)">
                </div>
                <div class="card">
                    <img id="preview3" alt="Preview 3" style="display: none;">
                    <input type="hidden" id="cropped-image-3" name="cropped_images[2]">
                </div>
            </div>

            <div class="image-card">

                <label for="image4">Upload Image 4:</label><br>
                <div class="upload-controls">
                    <input type="file" name="images[]" id="image4" accept="image/png, image/jpeg, image/jpg" onchange="previewAndCrop(this, 4)">
                </div>
                <div class="card">
                    <img id="preview4" alt="Preview 4" style="display: none;">
                    <input type="hidden" id="cropped-image-4" name="cropped_images[3]">
                </div>
            </div>

            <div class="image-card">
                <label for="image5">Upload Image 5:</label><br>
                <div class="upload-controls">
                    <input type="file" name="images[]" id="image5" accept="image/png, image/jpeg, image/jpg" onchange="previewAndCrop(this, 5)">
                </div>
                <div class="card">
                    <img id="preview5" alt="Preview 5" style="display: none;">
                    <input type="hidden" id="cropped-image-5" name="cropped_images[4]">
                </div>
            </div>
        </div>


        <br>



        <label for="conditions">Condition:</label>
        <select id="conditions" name="conditions">
            <option value="">Select Condition</option>
            <option value="New">New</option>
            <option value="New other">New other</option>
            <option value="Seller refurbished">Seller refurbished</option>
            <option value="Used">Used</option>
            <option value="For parts or not working">For parts or not working</option>

        </select>
        <br><br>



        <label for="column_4">OEM:</label>
        <select id="column_4" name="column_4">
            <option value="yes">YES</option>
            <option value="no">NO</option>
        </select><br><br>


        <label for="equipment">Select equipment:</label><br>
        <div style="align-items: center; gap: 10px;">
            <select id="equipment" name="equipment" style="flex-grow: 1;">
                <option value="">-- Select an equipment --</option>
                <?php while ($equipament = $equipaments->fetch_assoc()): ?>
                    <option value="<?= $equipament['id'] ?>" data-name="<?= htmlspecialchars($equipament['name']) ?>">
                        <?= htmlspecialchars($equipament['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <a href="register_equipament.php?from=add_new" style="white-space: nowrap;">[+ Add New Equipment]</a>
        </div>



        <br><br><label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" required>
            <option value="">Select Brand</option>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="model_id">Model:</label>
        <select id="model_id" name="model_id" required>
            <option value="">Select Model</option>
            <?php while ($model = $models->fetch_assoc()): ?>
                <option value="<?= $model['model_id'] ?>"><?= htmlspecialchars($model['model_name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>



        <label>Select E-shops:</label><br>
        <?php while ($eshop = $eshops->fetch_assoc()): ?>
            <input type="checkbox" name="eshops[]" value="<?= $eshop['id'] ?>">
            <?= htmlspecialchars($eshop['name']) ?>
            <input type="text" name="product_codes[<?= $eshop['id'] ?>]" placeholder="Product Code <?= $eshop['id'] ?>"><br>
        <?php endwhile; ?>




        <br><label for="config">Configuration:</label>
        <input type="text" id="config" name="config"><br><br>

        <label for="code">Code:</label>
        <input type="text" id="code" name="code"><br><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description"><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price"><br><br>

        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
            <div style="flex-basis: 100px;">
                <label for="qtd">QTD:</label><br>
                <input type="number" id="qtd" name="qtd" value="1" style="width: 80px;">
            </div>
            <div style="flex-basis: 150px;">
                <label>Total:</label><br>
                <strong id="total-display" style="font-size: 1.2em; ">0.00 EUR</strong>
            </div>
        </div>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" placeholder="Enter location"><br><br>

        <label for="ire">IRE:</label>
        <input type="text" id="ire" name="ire"><br><br>

        <label for="eur">EUR:</label>
        <input type="text" id="eur" name="eur"><br><br>

        <label for="returns">Returns:</label>
        <input type="text" id="returns" name="returns"><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Active" selected>Active</option>
            <option value="Sold">Sold</option>
            <option value="Test">Test</option>
        </select>
        <br><br>

        <div id="sold_date_container" style="display: none;">
            <label for="sold_date">Sold Date (d/m/Y):</label><br>
            <input type="text" id="sold_date" name="sold_date" placeholder="dd/mm/yyyy">
            <br><br>
        </div>

        <button type="submit">Add Equipment</button>
        <a href="index.php">[ Back ]</a>
    </form>

    <!-- Cropper Modal -->
    <div class="modal-overlay"></div>
    <div id="cropperModal" class="modal">

        <div class="modal-content">

            <span class="close" onclick="closeCropper()">&times;</span>
            <img id="cropperImage" style="max-width: 100%; height: auto; margin-bottom: 10px;" />
            <button onclick="saveCrop()">Save</button>
            <button onclick="closeCropper()">Cancel</button>
            <button type="button" onclick="rotateImage(90)">Rotate 90°</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        let currentImageId;
        let currentIndex;
        const cropperModal = document.getElementById('cropperModal');
        const cropperImage = document.getElementById('cropperImage');

        function previewAndCrop(input, index) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(`preview${index}`);
                    preview.src = e.target.result;
                    preview.style.display = 'block';


                    openCropper(preview.id, index);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openCropper(imageId, index) {
            const modal = document.getElementById("cropperModal");
            const overlay = document.querySelector(".modal-overlay");

            modal.style.display = "block";
            overlay.style.display = "block";
            currentImageId = imageId;
            currentIndex = index;

            const imgElement = document.getElementById(imageId);
            cropperImage.src = imgElement.src;
            cropperModal.style.display = 'block';


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

        function saveCrop() {
            if (!cropper) return;

            const croppedCanvas = cropper.getCroppedCanvas({
                fillColor: '#fff',
            });
            const croppedDataUrl = croppedCanvas.toDataURL();


            const preview = document.getElementById(currentImageId);
            const hiddenInput = document.getElementById(`cropped-image-${currentIndex}`);
            preview.src = croppedDataUrl;
            hiddenInput.value = croppedDataUrl;


            closeCropper();
        }

        function closeCropper() {
            const modal = document.getElementById("cropperModal");
            const overlay = document.querySelector(".modal-overlay");

            modal.style.display = "none";
            overlay.style.display = "none";
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropperModal.style.display = 'none';
        }

        function rotateImage(degrees) {
            if (cropper) {
                cropper.rotate(degrees);
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const soldDateContainer = document.getElementById('sold_date_container');

            function toggleSoldDate() {
                if (statusSelect.value === 'Sold') {
                    soldDateContainer.style.display = 'block';
                } else {
                    soldDateContainer.style.display = 'none';
                }
            }


            if (statusSelect) {
                statusSelect.addEventListener('change', toggleSoldDate);
            }

            const qtdInput = document.getElementById('qtd');
            const priceInput = document.getElementById('price');
            const totalDisplay = document.getElementById('total-display');

            function calculateTotal() {
            
                const quantity = parseInt(qtdInput.value, 10) || 1;
          
                const price = parseFloat(priceInput.value) || 0;

                const total = quantity * price;

             
                totalDisplay.textContent = total.toFixed(2) + ' EUR';
            }

            qtdInput.addEventListener('input', calculateTotal);
            priceInput.addEventListener('input', calculateTotal);

  
            calculateTotal();


        });
    </script>

</body>

</html>