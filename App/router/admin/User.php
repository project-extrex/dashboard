<?php

use App\Core\Render;
use App\Core\RenderAdmin;
use App\Database\Entities\Resources;
use App\Database\Entities\Settings;
use App\Database\Entities\User;

global $entityManager;

$settingsRepo = $entityManager->getRepository(Settings::class);

$renderer = new Render($settingsRepo, $entityManager);

$router->get('/admin/users/', function () use ($renderer, $entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    $user = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$user->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }
    $usersRepo = $entityManager->getRepository(User::class);
    $users = $usersRepo->findAll();
    $renderer->renderAdmin('users', ['users' => $users]);
});

$router->post('/admin/users/{id:\d+}', function ($vars) use ($entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $admin = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$admin || !$admin->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }

    $user_ = $entityManager->find(User::class, (int) $vars['id']);
    if (!$user_) {
        header('Location: /admin/users/?error=notfound');
        exit;
    }

    $user_->setName($_POST['name'] ?? $user_->getName());
    $user_->setUsername($_POST['username'] ?? $user_->getUsername());
    $user_->setFirstname($_POST['firstname'] ?? $user_->getFirstname());
    $user_->setLastname($_POST['lastname'] ?? $user_->getLastname());
    $user_->setEmail($_POST['email'] ?? $user_->getEmail());

    if (isset($_POST['admin'])) {
        $user_->setAdmin(filter_var($_POST['admin'], FILTER_VALIDATE_BOOLEAN));
    }

    if (!empty($_POST['password'])) {
        $user_->setPassword($_POST['password']);
    }

    $resources = $user_->getResources();
    if ($resources) {
        $resources->setCoins($_POST['coins'] ?? $resources->getCoins());
        $resources->setSlots($_POST['slots'] ?? $resources->getSlots());
        $resources->setMemory($_POST['memory'] ?? $resources->getMemory());
        $resources->setDisk($_POST['disk'] ?? $resources->getDisk());
        $resources->setCpu($_POST['cpu'] ?? $resources->getCpu());
        $resources->setDbs($_POST['dbs'] ?? $resources->getDbs());
        $resources->setBackups($_POST['backups'] ?? $resources->getBackups());
        $resources->setAllocations($_POST['allocations'] ?? $resources->getAllocations());

        $entityManager->persist($resources);
    }

    $entityManager->persist($user_);
    $entityManager->flush();

    header('Location: /admin/users/');
    exit;
});