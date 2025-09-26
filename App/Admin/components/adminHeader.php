<?php

use App\Core\AdminSidebar;

AdminSidebar::addMenu([
    'slug' => 'dashboard', 
    'title' => 'Dashboard', 
    'icon' => 'fa-tachometer-alt', 
    "submenus" => [
        "dashboard" => [
            "title" => "Dashboard",
            "url" => "/admin/",
        ],
        "Visit Site" => [
            "title" => "Visit Site",
            "url" => "/",
            "slug" => "home",
        ],
        "visit User Dashboard" => [
            "title" => "User Dashboard",
            "url" => "/dashboard"
        ]
        ],
    "position" => 0,
]);
AdminSidebar::addMenu([
    'slug' => 'theme', 
    'title' => 'appearance', 
    'icon' => 'fa-solid fa-palette', 
    //'url' => '/admin/theme',
    "position" => 1,
    "submenus" => [
        "theme" => [
            "title" => "Theme",
            "url" => "/admin/theme",
            "slug" => "theme"
        ],
        "Theme explorer" => [
            "title" => "Theme Explorer",
            "url" => "/admin/theme-explorer",
            "slug" => "theme-explorer"
        ]
    ]
]);
AdminSidebar::addMenu([
    'slug' => 'users', 
    'title' => 'Users', 
    'icon' => 'fa-user', 
    'url' => '/admin/users',
    "position" => 2,
]);
AdminSidebar::addMenu([
    'slug' => "setting",
    "title" => "Settings",
    "icon" => "fas fa-gear",
    "url" => "/admin/setting",
    "position" => 3,
]);

AdminSidebar::addMenu([
    "slug" => "products",
    "title" => "products"
]);

// Set active menu based on current page
$currentPage = $_GET['page'] ?? rtrim(trim($_SERVER["REQUEST_URI"], "/admin/"), "/") ?? 'dashboard';
$currentTab = $_GET['tab'] ?? '';
AdminSidebar::setActive($currentPage, $currentTab);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrex Admin  <?= isset($title) ? " - " . $title : "" ?> </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="https://avatars.githubusercontent.com/u/230524666?s=200&v=4" />
</head>
<body>
    <?php echo AdminSidebar::renderSidebar();

    if (isset($adminPath)) {
        include $adminPath;
    } else {
        echo AdminSidebar::renderPage();
    }
    ?>

<script>

const url = new URL(window.location.href);

url.searchParams.delete("error");
url.searchParams.delete("msg");

window.history.replaceState({}, document.title, url);

</script>
</body>
</html>