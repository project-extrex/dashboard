<?php
namespace App\Core\Auth;

use App\Database\Entities\User;
use App\Database\Repositories\UserRepository;
use App\Helper\Theme;

class AuthController {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = \App\Core\ServiceContainer::getInstance()->getUserRepository();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showLoginForm(array $data = []): void {
        Theme::setLayout('layouts/auth');
        Theme::render('auth/login', array_merge([
            'title' => 'Login',
            'pageClass' => 'login-page'
        ], $data));
    }

    public function showRegisterForm(array $data = []): void {
        Theme::setLayout('layouts/auth');
        Theme::render('auth/register', array_merge([
            'title' => 'Register',
            'pageClass' => 'register-page'
        ], $data));
    }

    public function login(array $data): ?User {
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $remember = isset($data['remember']);

        $user = $this->userRepository->findByUsername($username);
        if ($user && password_verify($password, $user->getPassword())) {
            $_SESSION['user_id'] = $user->getId();
            
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                // Store the remember me token (you'd want to store this securely in the database)
                setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/', '', true, true);
            }
            
            return $user;
        }
        
        return null;
    }

    public function register(array $data): array {
        $errors = [];
        
        // Validate input
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } elseif ($this->userRepository->findByUsername($data['username'])) {
            $errors['username'] = 'Username already exists';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long';
        }

        if ($data['password'] !== ($data['password_confirm'] ?? '')) {
            $errors['password_confirm'] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Create user
        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setEmail($data['email']);
        
        $user = $this->userRepository->save($user);
        
        if ($user) {
            $_SESSION['user_id'] = $user->getId();
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'errors' => ['general' => 'Failed to create account']];
    }

    public function logout(): void {
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }

    public function getCurrentUser(): ?User {
        if (!isset($_SESSION['user_id'])) {
            // Check for remember me token
            $token = $_COOKIE['remember_token'] ?? null;
            if ($token) {
                // Validate token and get user (you'd want to implement this securely)
                // For now, we'll just return null
                return null;
            }
            return null;
        }
        return $this->userRepository->find($_SESSION['user_id']);
    }

    public function requireAuth(): void {
        if (!$this->getCurrentUser()) {
            header('Location: ' . Theme::url('auth/login'));
            exit;
        }
    }
}

