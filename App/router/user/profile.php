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

    $error = "";

    $user = $entityManager->find(User::class, $_SESSION['user_id']);

    if($user->verifyPassword($_POST["password"])) 
    {
         $user->setName($_POST["name"]??$user->getName());
         $user->setUsername($_POST["username"]??$user->getUsername());
         $user->setFirstname($_POST["firstname"]??$user->getFirstname());
         $user->setLastname($_POST["lastname"]??$user->getLastname());
         $user->setEmail($_POST["email"]??$user->getEmail());
    } else {
        $error = "Invalid password";
    }
    
    $renderer->render('profile', ['user_data' => $user, 'error' => $error]);
});