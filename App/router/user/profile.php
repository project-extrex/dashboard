<?php

use App\Core\Render;
use App\Core\RenderAdmin;
use App\Core\Router;
use App\Database\Entities\Settings;
use App\Database\Entities\User;
use App\Database\Entities\Resources;

global $entityManager;

$settingsRepo = $entityManager->getRepository(Settings::class);

$renderer = new Render($settingsRepo, $entityManager);

$router->get("/profile", function () use ($renderer, $entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    $renderer->render('profile', ['user_data' => $user]);
});


$router->post("/profile", function () use ($renderer, $entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    $renderer->render('profile', ['user_data' => $user]);
});