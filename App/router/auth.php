<?php

/*
 * Authentication Routes *
 */

use App\Core\Render;
use App\Core\RenderAdmin;
use App\Core\Router;
use App\Database\Entities\Resources;
use App\Database\Entities\Settings;
use App\Database\Entities\User;

global $entityManager;

$settingsRepo = $entityManager->getRepository(Settings::class);

$renderer = new Render($settingsRepo, $entityManager);

$router->get('/login', function () use ($renderer) {
    if (isset($_SESSION['user_id'])) {
        header('Location: /dashboard');
        exit;
    }
    $renderer->view('login', ['login_path' => '/login']);
});

$router->post('/login', function () use ($renderer, $entityManager) {
    $input = wantsJson()
        ? json_decode(file_get_contents('php://input'), true)
        : $_POST;

    $email = trim($input['email'] ?? '');
    $passwordInput = trim($input['password']);

    $userRepo = $entityManager->getRepository(User::class);
    $user = $userRepo->findOneBy(['email' => $email]);

    //$user->setPassword($passwordInput);

    if (!$user) {
        $errorMsg = 'Invalid email or password';
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => $errorMsg], 401)
            : $renderer->view('login', ['login_path' => '/login', 'error' => $errorMsg]);
    }

    if (
        $user->verifyPassword($passwordInput)
    ) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->getId();

        return wantsJson()
            ? jsonResponse(['success' => true, 'redirect' => '/dashboard'])
            : header('Location: /dashboard');
    } else {
        $errorMsg = 'Invalid email or password';
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => $errorMsg], 401)
            : $renderer->view('login', ['login_path' => '/login', 'error' => $errorMsg]);
    }
});

$router->get('/logout', function () {
    session_destroy();
    header('Location: /login');
    exit;
});

$router->get('/register', function () use ($renderer) {
    if (isset($_SESSION['user_id'])) {
        header('Location: /dashboard');
        exit;
    }
    $renderer->view('register', []);
});

$router->post('/register', function () use ($entityManager, $renderer, $settingsRepo) {
    $input = wantsJson() ? json_decode(file_get_contents('php://input'), true) : $_POST;

    $username = trim($input['username'] ?? '');
    $firstname = trim($input['firstname'] ?? '');
    $lastname = trim($input['lastname'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    $confirmpass = $input['confirm_password'] ?? '';

    if (!$username || !$firstname || !$lastname || !$email || !$password) {
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => 'All fields are required'], 400)
            : $renderer->view('register', ['error' => 'All fields are required.']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => 'Invalid email address'], 400)
            : $renderer->view('register', ['error' => 'Invalid email address.']);
    }

    if ($password !== $confirmpass) {
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => 'Passwords do not match'], 400)
            : $renderer->view('register', ['error' => 'Passwords do not match.']);
    }

    if (strlen($password) < 8) {
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => 'Password must be at least 8 characters'], 400)
            : $renderer->view('register', ['error' => 'Password must be at least 8 characters.']);
    }

    $userRepo = $entityManager->getRepository(User::class);

    if ($userRepo->findOneBy(['email' => $email])) {
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => 'Email already registered'], 400)
            : $renderer->view('register', ['error' => 'Email already registered.']);
    }

    if ($userRepo->findOneBy(['username' => $username])) {
        return wantsJson()
            ? jsonResponse(['success' => false, 'error' => 'Username already taken'], 400)
            : $renderer->view('register', ['error' => 'Username already taken.']);
    }

    $user = new User($settingsRepo);
    $user->setUsername($username);
    $user->setFirstName($firstname);
    $user->setLastName($lastname);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setAdmin(false);
    $user->setName($username);
    $user->setPtrlid(1);


    $entityManager->persist($user);
    $entityManager->flush();

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user->getId();

    return wantsJson()
        ? jsonResponse(['success' => true, 'redirect' => '/dashboard'])
        : header('Location: /dashboard');
});
