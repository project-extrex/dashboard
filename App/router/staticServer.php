<?php


/******************
 *  Static Server *
 *****************/

 $router->get('/assets/{file:.+}', function($vars) use ($settingsRepo) {
    $file = $vars['file'];

    $theme = $settingsRepo->getSetting("theme");
    $baseDir = realpath(__DIR__ . '/../../theme/' . $theme . "/assets"); // static is outside public
    $target  = realpath($baseDir . '/' . $file);

    // Prevent directory traversal
    if ($target === false || strpos($target, $baseDir) !== 0 || !is_file($target)) {
        http_response_code(404);
        return 'File not found';
    }

    // Correct MIME type
    $mime = mime_content_type($target);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($target));

    // Stream file and exit
    readfile($target);
    exit;
});

$router->get('/admin/assets/{file:.+}', function($vars) {
    $file = $vars['file'];

    $baseDir = realpath(__DIR__ . "/../Admin/assets"); // static is outside public
    $target  = realpath($baseDir . '/' . $file);

    // Prevent directory traversal
    if ($target === false || strpos($target, $baseDir) !== 0 || !is_file($target)) {
        http_response_code(404);
        return 'File not found';
    }

    // Correct MIME type
    $mime = mime_content_type($target);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($target));

    // Stream file and exit
    readfile($target);
    exit;
});

$router->get('/content/{file:.+}', function($vars) {
    $file = $vars['file'];

    $baseDir = realpath(__DIR__ . "/../../public/content"); 
    $target  = realpath($baseDir . '/' . $file);

    // Prevent directory traversal
    if ($target === false || strpos($target, $baseDir) !== 0 || !is_file($target)) {
        http_response_code(404);
        return 'File not found';
    }

    // Correct MIME type
    $mime = mime_content_type($target);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($target));

    // Stream file and exit
    readfile($target);
    exit;
});
