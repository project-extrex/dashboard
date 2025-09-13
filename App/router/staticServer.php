<?php

/*
 * Static Server *
 */

$router->get('/assets/{file:.+}', function ($vars) use ($settingsRepo) {
    $file = $vars['file'];

    $theme = $settingsRepo->getSetting('theme');
    $baseDir = realpath(__DIR__ . '/../../theme/' . $theme . '/assets');  // static is outside public
    $target = realpath($baseDir . '/' . $file);

    // Prevent directory traversal
    if ($target === false || strpos($target, $baseDir) !== 0 || !is_file($target)) {
        http_response_code(404);
        return 'File not found';
    }

    // Correct MIME type
    $ext = pathinfo($target, PATHINFO_EXTENSION);
    switch (strtolower($ext)) {
        case 'css':
            $mime = 'text/css';
            break;
        case 'js':
            $mime = 'application/javascript';
            break;
        case 'png':
            $mime = 'image/png';
            break;
        case 'jpg':
        case 'jpeg':
            $mime = 'image/jpeg';
            break;
        case 'gif':
            $mime = 'image/gif';
            break;
        case 'svg':
            $mime = 'image/svg+xml';
            break;
        default:
            $mime = 'application/octet-stream';  // fallback
    }

    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($target));
    readfile($target);
    exit;
    header('Content-Length: ' . filesize($target));

    // Stream file and exit
    readfile($target);
    exit;
});

$router->get('/admin/assets/{file:.+}', function ($vars) {
    $file = $vars['file'];

    $baseDir = realpath(__DIR__ . '/../Admin/assets');  // static is outside public
    $target = realpath($baseDir . '/' . $file);

    // Prevent directory traversal
    if ($target === false || strpos($target, $baseDir) !== 0 || !is_file($target)) {
        http_response_code(404);
        return 'File not found';
    }

    // Correct MIME type
    $ext = pathinfo($target, PATHINFO_EXTENSION);
    switch (strtolower($ext)) {
        case 'css':
            $mime = 'text/css';
            break;
        case 'js':
            $mime = 'application/javascript';
            break;
        case 'png':
            $mime = 'image/png';
            break;
        case 'jpg':
        case 'jpeg':
            $mime = 'image/jpeg';
            break;
        case 'gif':
            $mime = 'image/gif';
            break;
        case 'svg':
            $mime = 'image/svg+xml';
            break;
        default:
            $mime = 'application/octet-stream';  // fallback
    }
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($target));

    // Stream file and exit
    readfile($target);
    exit;
});

$router->get('/content/{file:.+}', function ($vars) {
    $file = $vars['file'];

    $baseDir = realpath(__DIR__ . '/../../public/content');
    $target = realpath($baseDir . '/' . $file);

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
