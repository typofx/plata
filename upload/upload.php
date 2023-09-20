<?php

if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagem'];

   
    if($file['size'] <= 2097152) { 
       
        $allowedTypes = array('image/png', 'image/jpeg', 'image/jpg');
        if(in_array($file['type'], $allowedTypes)) {

            list($width, $height) = getimagesize($file['tmp_name']);
            if($width <= 1000 && $height <= 1000) {
               
                $fileName = uniqid() . '-' . $file['name'];

               
                $uploadDir = 'uploads/';
                if(!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $destination = $uploadDir . $fileName;

                if(move_uploaded_file($file['tmp_name'], $destination)) {
                    echo "The image was sent successfully.";
                } else {
                    echo "Error uploading the file.";
                }
            } else {
                echo "The image exceeds the maximum size allowed (1000x1000 pixels).";
            }
        } else {
            echo "Only PNG and JPG images are allowed.";
        }
    } else {
        echo "The file is very large (maximum 2MB).";
    }
} else {
    echo "Error sending the file.";
}
?>
