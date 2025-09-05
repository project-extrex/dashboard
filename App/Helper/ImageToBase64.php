<?php

function ConvertBase64Image($image_path) {
if (file_exists($image_path)) {
    // Get image data
    $image_data = file_get_contents($image_path);
    
    // Get MIME type using finfo_file (requires the fileinfo extension)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $image_path);
    finfo_close($finfo);

    // Encode the image data
    $base64_string = base64_encode($image_data);

    // Create the full Data URI string
    $data_uri = "data:{$mime_type};base64,{$base64_string}";
    
    // Use the data URI
    return $data_uri;
} else {
    return "Image file not found.";
}
}
?>
