<?php
// admin/upload_image.php
require 'auth.php';
require '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directory in which to save the uploaded file
            $uploadFileDir = '../uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                // Assuming the uploads directory is accessible via URL
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
                             $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                $url = $protocol . $host . '/uploads/' . $newFileName;

                echo json_encode(['location' => $url]);
            } else {
                echo json_encode(['error' => 'There was an error moving the uploaded file.']);
            }
        } else {
            echo json_encode(['error' => 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions)]);
        }
    } else {
        echo json_encode(['error' => 'No file uploaded or there was an upload error.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
