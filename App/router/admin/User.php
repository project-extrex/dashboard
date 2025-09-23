<?php

use App\Core\Render;
use App\Core\RenderAdmin;
use App\Database\Entities\Resources;
use App\Database\Entities\Settings;
use App\Database\Entities\User;

global $entityManager;

$settingsRepo = $entityManager->getRepository(Settings::class);

$renderer = new Render($settingsRepo, $entityManager);

$router->get('/admin/users', function () use ($renderer, $entityManager) {
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
    $renderer->renderAdmin('users', ['users' => $users, "me" => $user]);
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

$router->post("/admin/users", function() use ($entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $admin = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$admin || !$admin->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }

    $userRepo = $entityManager->getRepository(User::class);
    $settingsRepo = $entityManager->getRepository(Settings::class);


    //

    $name = $_POST["name"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $ptrlId = $_POST["ptrlid"];
    $password = $_POST["password"];
    $admin = $_POST["admin"];

    if ($userRepo->findOneBy(['email' => $email])) {
        header("Location: /admin/users?error=Email+Already+in-use");
        exit;
    }

    if ($userRepo->findOneBy(['username' => $username])) {
        header("Location: /admin/users?error=Username+already+taken");
        exit;
    }

    $user = new User($settingsRepo);
    $user->setUsername($username);
    $user->setFirstName($firstname);
    $user->setLastName($lastname);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setAdmin($admin);
    $user->setName($username);
    $user->setPtrlid($ptrlId);

    $resources = $user->getResources();
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


    $entityManager->persist($user);
    $entityManager->flush();

    header("Location: /admin/users?msg=successfully+created+user");
    exit;

});

$router->post('/admin/users/{id:\d+}/delete', function ($vars) use ($entityManager) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $admin = $entityManager->find(User::class, $_SESSION['user_id']);
    if (!$admin || !$admin->isAdmin()) {
        header('Location: /dashboard?error=unauthorized');
        exit;
    }

    $user = $entityManager->find(User::class, (int) $vars['id']);
    if (!$user) {
        header('Location: /admin/users?error=User+not+found');
        exit;
    }

    // prevent deleting yourself
    if ($user->getId() === $admin->getId()) {
        header('Location: /admin/users?error=Cannot+delete+yourself');
        exit;
    }

    // If the user has resources, remove them first (Doctrine may cascade, but safer explicitly)
    $resources = $user->getResources();
    if ($resources) {
        $entityManager->remove($resources);
    }

    $entityManager->remove($user);
    $entityManager->flush();

    header('Location: /admin/users?msg=User+deleted+successfully');
    exit;
});