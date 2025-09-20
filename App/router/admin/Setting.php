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