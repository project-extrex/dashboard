<?php

use App\Core\Render;
use App\Database\Entities\Settings;
use App\Database\Entities\User;

global $entityManager;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$settingsRepo = $entityManager->getRepository(Settings::class);
$renderer     = new Render($settingsRepo, $entityManager);

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

function RenderSetting() {
    global $entityManager, $renderer, $settingsRepo;

    if (!isAdmin()) {
        exit;
    }

    $settings = $settingsRepo->findAll();
    $renderer->renderAdmin("settings", [
        "settings" => $settings,
        "repo"     => $settingsRepo
    ]);
}

$router->get("/admin/setting", fn() => RenderSetting());
$router->post("/admin/setting", fn() => RenderSetting());

require_once __DIR__ . "/Uploader.php";