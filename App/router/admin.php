<?php

/**
 * -----------------
 * Admin Routes
 * -----------------
 */

use App\Core\Render;
use App\Core\RenderAdmin;
use App\Core\Router;
use App\Database\Entities\Settings;
use App\Database\Entities\User;
use App\Database\Entities\Resources;

global $entityManager;

$settingsRepo = $entityManager->getRepository(Settings::class);

$renderer = new Render($settingsRepo, $entityManager);

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
}



$router->get('/admin', function () use ($renderer, $entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$user->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }

    $renderer->renderAdmin('index', ['user' => $user]);
});

$router->get('/admin/theme', function () use ($renderer, $entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$user->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }

    $renderer->renderAdmin('themes', []);
});

$router->post('/admin/theme', function () use ($renderer, $entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$user->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }

    $action = htmlspecialchars($_POST['action'] ?? '');
    $themeSlug = htmlspecialchars($_POST['slug'] ?? '');
    $themePath = htmlspecialchars($_POST['path'] ?? '');

    if ($action === 'activate') {
        $settingsRepo = $entityManager->getRepository(Settings::class);
        $settingsRepo->setSetting('theme', $themeSlug);
    }

    $renderer->renderAdmin('themes', [
        'msg' => "Action '{$action}' performed successfully for theme '{$themeSlug}'"
    ]);
});

require_once "admin/User.php";
require_once "admin/Setting.php";
require_once "admin/theme-explorer.php";
require_once "admin/theme-uploader.php";
require_once "admin/menuSidebar.php";
require_once __DIR__ . "/../Admin/pterodactyl.php";