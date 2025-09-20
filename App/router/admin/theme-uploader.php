<?php

$router->post('/admin/theme/upload', function () {
  set_time_limit(0);  // Ensure long uploads/extraction don't time out
  ob_implicit_flush(true);
  ob_end_flush();  // Make sure output is sent immediately

  if (!isset($_FILES['zipfile'])) {
    echo 'No file uploaded.';
    exit;
  }
  if (!isAdmin())
    die('hell');

  $uploadDir = __DIR__ . '/../../theme/';
  if (!is_dir($uploadDir))
    mkdir($uploadDir, 0777, true);

  // Move uploaded file
  $zipFile = $_FILES['zipfile'];
  $filename = basename($zipFile['name']);
  $targetPath = $uploadDir . $filename;

  if (!move_uploaded_file($zipFile['tmp_name'], $targetPath)) {
    echo 'Failed to upload file.';
    exit;
  }

  // Directory named after ZIP file (without .zip)
  $extractDir = $uploadDir . pathinfo($filename, PATHINFO_FILENAME) . '/';
  if (!is_dir($extractDir))
    mkdir($extractDir, 0777, true);

  // Open ZIP
  $zip = new ZipArchive;
  if ($zip->open($targetPath) === TRUE) {
    $totalFiles = $zip->numFiles;

    for ($i = 0; $i < $totalFiles; $i++) {
      $zip->extractTo($extractDir, $zip->getNameIndex($i));

      // Send real-time progress
      echo 'Extracted: ' . $zip->getNameIndex($i) . ' (' . ($i + 1) . "/$totalFiles)<br>";
      flush();
      usleep(100000);  // optional: slow down for visible feedback
    }
    $zip->close();
    echo 'Extraction completed!';
  } else {
    echo 'Failed to open ZIP file.';
  }
});
