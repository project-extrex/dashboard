<?php
namespace App\Core\Auth;

use App\Core\ServiceContainer;
use App\Helper\Theme;

class AuthMiddleware {
    public function __invoke(callable $next): callable {
        return function () use ($next) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                header('Location: ' . Theme::url('auth/login'));
                exit;
            }

            $userRepository = ServiceContainer::getInstance()->getUserRepository();
            $user = $userRepository->find($userId);

            if (!$user || !$user->isAdmin()) {
                header('Location: ' . Theme::url('auth/login'));
                exit;
            }

            return $next();
        };
    }
}
