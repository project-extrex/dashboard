<?php
// Fix script for dashboard structure

function createDirectory($path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}

// 1. Fix directory structure
$directories = [
    'App/Admin/Controllers',
    'App/Admin/views/layouts',
    'App/Admin/Middleware',
    'App/Core/Auth',
    'App/Core/Database',
    'App/Core/Plugin',
    'public/admin/assets/css',
    'public/admin/assets/js',
    'public/admin/assets/images',
];

foreach ($directories as $dir) {
    createDirectory(__DIR__ . '/' . $dir);
}

// 2. Move admin files from lowercase to uppercase
$adminFiles = glob(__DIR__ . '/App/admin/*');
foreach ($adminFiles as $file) {
    $newPath = str_replace('/App/admin/', '/App/Admin/', $file);
    if (file_exists($file) && !file_exists($newPath)) {
        rename($file, $newPath);
    }
}

// 3. Move admin views from theme to Admin
$themeAdminViews = __DIR__ . '/theme/extrax/views/admin';
if (is_dir($themeAdminViews)) {
    $files = glob($themeAdminViews . '/*');
    foreach ($files as $file) {
        $basename = basename($file);
        $newPath = __DIR__ . '/App/Admin/views/' . $basename;
        if (!file_exists($newPath)) {
            copy($file, $newPath);
        }
    }
}

// 4. Create necessary files
$requiredFiles = [
    'public/admin/assets/css/admin.css' => '/* Admin styles */',
    'public/admin/assets/js/admin.js' => '// Admin scripts',
    'App/Admin/.htaccess' => 'Deny from all',
    'public/admin/.htaccess' => 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.php [QSA,L]'
];

foreach ($requiredFiles as $file => $content) {
    $path = __DIR__ . '/' . $file;
    if (!file_exists($path)) {
        file_put_contents($path, $content);
    }
}

echo "Structure fixed successfully!\n";
