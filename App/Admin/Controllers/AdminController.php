<?php
namespace App\Admin\Controllers;

use App\Core\ServiceContainer;
use App\Helper\Theme;

abstract class AdminController {
    protected ServiceContainer $container;

    public function __construct() {
        $this->container = ServiceContainer::getInstance();
        Theme::setLayout('layouts/admin');
    }

    protected function render(string $view, array $data = []): void {
        if (!isset($data['user'])) {
            $data['user'] = $this->getCurrentUser();
        }
        Theme::render($view, $data);
    }

    protected function getCurrentUser() {
        return $this->container->getUserRepository()->find($_SESSION['user_id']);
    }

    protected function redirectWithMessage(string $path, string $message, string $type = 'info'): void {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
        header('Location: ' . Theme::url($path));
        exit;
    }
}
