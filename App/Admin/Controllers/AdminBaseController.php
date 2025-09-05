<?php
namespace App\Admin\Controllers;

use App\Core\ServiceContainer;
use App\Helper\Theme;

abstract class AdminBaseController {
    protected ServiceContainer $container;
    protected array $data = [];

    public function __construct() {
        $this->container = ServiceContainer::getInstance();
        $this->checkAdminAccess();
    }

    protected function checkAdminAccess(): void {
        $user = $this->getCurrentUser();
        if (!$user || !$user->isAdmin()) {
            $this->redirect('auth/login');
        }
    }

    protected function getCurrentUser() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        $userRepo = $this->container->getUserRepository();
        return $userRepo->find($userId);
    }

    protected function render(string $view, array $data = []): void {
        $this->data = array_merge($this->data, $data, [
            'user' => $this->getCurrentUser(),
            'currentMenu' => $this->getCurrentMenu()
        ]);

        Theme::setLayout('admin');
        Theme::render($view, $this->data);
    }

    protected function redirect(string $path): void {
        header('Location: ' . Theme::url($path));
        exit;
    }

    protected function redirectWithMessage(string $path, string $message, string $type = 'info'): void {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
        $this->redirect($path);
    }

    protected function getCurrentMenu(): string {
        $path = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($path, '/admin/plugins') !== false) return 'plugins';
        if (strpos($path, '/admin/themes') !== false) return 'themes';
        if (strpos($path, '/admin/users') !== false) return 'users';
        if (strpos($path, '/admin/settings') !== false) return 'settings';
        return 'dashboard';
    }

    protected function validateCSRF(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
            $this->redirectWithMessage('admin', 'Invalid request', 'error');
        }
    }

    protected function generateCSRFToken(): string {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
}
