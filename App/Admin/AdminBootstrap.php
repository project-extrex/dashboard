<?php
namespace App\Admin;

use App\Core\ServiceContainer;
use App\Helper\Theme;

class AdminBootstrap {
    public static function init(): void {
        // Ensure user is authenticated and is admin
        self::checkAdminAccess();
        
        // Initialize theme for admin
        Theme::init('admin', dirname(__DIR__, 2));
        Theme::setLayout('admin');
        
        // Set up data
        $defaultData = [
            'user' => self::getCurrentUser(),
            'adminMenu' => self::getAdminMenu()
        ];
        Theme::render('admin/index', $defaultData);
    }
    
    private static function checkAdminAccess(): void {
        $user = self::getCurrentUser();
        if (!$user || !$user->isAdmin()) {
            header('Location: /auth/login');
            exit;
        }
    }
    
    private static function getCurrentUser() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }
        
        $userRepo = ServiceContainer::getInstance()->getUserRepository();
        return $userRepo->find($userId);
    }
    
    private static function getAdminMenu(): array {
        return [
            'dashboard' => [
                'icon' => 'fas fa-home',
                'label' => 'Dashboard',
                'url' => '/admin'
            ],
            'users' => [
                'icon' => 'fas fa-users',
                'label' => 'Users',
                'url' => '/admin/users'
            ],
            'themes' => [
                'icon' => 'fas fa-paint-brush',
                'label' => 'Themes',
                'url' => '/admin/themes'
            ],
            'plugins' => [
                'icon' => 'fas fa-puzzle-piece',
                'label' => 'Plugins',
                'url' => '/admin/plugins'
            ],
            'settings' => [
                'icon' => 'fas fa-cog',
                'label' => 'Settings',
                'url' => '/admin/settings'
            ]
        ];
    }
}
