<?php

use App\Core\AdminSidebar;

/*
function isAdmin(): bool {
    global $entityManager;
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$user || !$user->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }
    return true;
}*/

/*
function renderMenuPage() {
  global $renderer;
  if(!isAdmin()) exit(); 
  $renderer->renderAdmin("menu-pages", [
    "postUri" => $_SERVER['REQUEST_URI'],
  ]);
}

$router->get("/admin/menu", 'renderMenuPage');
$router->post("/admin/menu", 'renderMenuPage');
*/

// Admin main menu pages
$router->get("/admin/{slug}", function($vars) use ($renderer) {
    if (!isAdmin()) {
        exit;
    }

    $slug = $vars["slug"];
    $menu = \App\Core\AdminSidebar::getMenu($slug);

    if (!$menu) {
        http_response_code(404);
        echo "Admin page not found";
        return;
    }

    $callback = $menu["callback"] ?? null;
    if (!is_callable($callback)) {
        http_response_code(500);
        echo "Menu callback not callable";
        return;
    }

    //$content = call_user_func($callback, $vars);

    $renderer->renderAdmin("menu-pages", [
        "callback"   => $callback,
        "vars"    => $vars,
        "title" => $menu["title"]
    ]);
});


// Admin submenu pages
$router->get("/admin/{slug:.+}/{subslug:.+}", function($vars) use ($renderer) {
    if (!isAdmin()) {
        exit;
    }

    $slug    = $vars["slug"];
    $subslug = $vars["subslug"];

    $menu = \App\Core\AdminSidebar::getMenu($slug);

    if (!$menu || !isset($menu["submenus"][$subslug])) {
        http_response_code(404);
        echo "Admin subpage not found";
        return;
    }

    $submenu  = $menu["submenus"][$subslug];
    $callback = $submenu["callback"] ?? null;

    if (!is_callable($callback)) {
        http_response_code(500);
        echo "Submenu callback not callable";
        return;
    }

    //$content = call_user_func($callback, $vars);

    $renderer->renderAdmin("menu-pages", [
        "callback"   => $callback,
        "vars"    => $vars,
        "title" => $submenu["title"]
    ]);
});


// Admin main menu pages
$router->post("/admin/{slug:.+}", function($vars) use ($renderer) {
    if (!isAdmin()) {
        exit;
    }

    $slug = $vars["slug"];
    $menu = \App\Core\AdminSidebar::getMenu($slug);

    if (!$menu) {
        http_response_code(404);
        echo "Admin page not found";
        return;
    }

    $callback = $menu["callback"] ?? null;
    if (!is_callable($callback)) {
        http_response_code(500);
        echo "Menu callback not callable";
        return;
    }

    //$content = call_user_func($callback, $vars);

    $renderer->renderAdmin("menu-pages", [
        "callback"   => $callback,
        "vars"    => $vars,
        "title" => $menu["title"]
    ]);
});


// Admin submenu pages
$router->post("/admin/{slug:.+}/{subslug:.+}", function($vars) use ($renderer) {
    if (!isAdmin()) {
        exit;
    }

    $slug    = $vars["slug"];
    $subslug = $vars["subslug"];

    $menu = \App\Core\AdminSidebar::getMenu($slug);

    if (!$menu || !isset($menu["submenus"][$subslug])) {
        http_response_code(404);
        echo "Admin subpage not found";
        return;
    }

    $submenu  = $menu["submenus"][$subslug];
    $callback = $submenu["callback"] ?? null;

    if (!is_callable($callback)) {
        http_response_code(500);
        echo "Submenu callback not callable";
        return;
    }

    //$content = call_user_func($callback, $vars);

    $renderer->renderAdmin("menu-pages", [
        "callback"   => $callback,
        "vars"    => $vars,
        "title" => $submenu["title"]
    ]);
});