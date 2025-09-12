<?php

// Absolute path to the public/content directory
$uploadDir = realpath(__DIR__ . '/../../../public/content');

if (!$uploadDir) {
    // Directory doesn't exist, try to create it
    $uploadDir = __DIR__ . '/../../public/content';
    if (!mkdir($uploadDir, 0777, true)) {
        die('Failed to create upload directory: ' . $uploadDir);
    }
    $uploadDir = realpath($uploadDir);
}

$router->post('/admin/upload', function () use ($uploadDir) {
    if (!isAdmin()) {
        http_response_code(403);
        exit('Forbidden');
    }

    if (!isset($_FILES['uploaded_file'])) {
        http_response_code(400);
        exit('No file uploaded');
    }

    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);
    $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Block PHP files
    $blockedTypes = ['php', 'php3', 'php4', 'php5', 'phtml', 'phar'];
    if (in_array($fileType, $blockedTypes)) {
        http_response_code(400);
        exit('PHP files are not allowed');
    }

    // Target file path
    $target_file = $uploadDir . '/' . $filename;

    if(file_exists($target_file)) { 
      // echo "a file with the same name exist";
      exit("file already exist"); 
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        echo "File uploaded successfully: $target_file"; // Shows full path
    } else {
        $error = error_get_last();
        echo 'Failed to upload file. Debug: ' . ($error['message'] ?? 'unknown error');
    }
});