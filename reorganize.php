<?php
// Reorganize structure and fix missing dependencies

// 1. Move admin files to correct location
$adminSourceDir = __DIR__ . '/App/admin';
$adminTargetDir = __DIR__ . '/App/Admin';

// Create admin directories if they don't exist
@mkdir($adminTargetDir . '/Controllers', 0777, true);
@mkdir($adminTargetDir . '/views/layouts', 0777, true);
@mkdir($adminTargetDir . '/Middleware', 0777, true);

// Move admin files
if (is_dir($adminSourceDir)) {
    rename($adminSourceDir . '/index.php', $adminTargetDir . '/Controllers/DashboardController.php');
    rename($adminSourceDir . '/themes.php', $adminTargetDir . '/Controllers/ThemeController.php');
    rename($adminSourceDir . '/users.php', $adminTargetDir . '/Controllers/UserController.php');
    
    // Move components if they exist
    if (is_dir($adminSourceDir . '/components')) {
        rename($adminSourceDir . '/components', $adminTargetDir . '/views/components');
    }
    
    // Remove old admin directory
    @rmdir($adminSourceDir);
}

// 2. Create necessary directories for assets
@mkdir(__DIR__ . '/public/admin/assets/css', 0777, true);
@mkdir(__DIR__ . '/public/admin/assets/js', 0777, true);
@mkdir(__DIR__ . '/public/admin/assets/images', 0777, true);

// 3. Create .htaccess for security
file_put_contents(__DIR__ . '/App/Admin/.htaccess', "Deny from all\n");

// 4. Create index.php in public folder
file_put_contents(__DIR__ . '/public/index.php', '<?php
require_once __DIR__ . "/../bootstrap.php";
require_once __DIR__ . "/../App/router/main.php";
');
