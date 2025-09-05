<?php
namespace App\Admin;

use App\Core\Router;
use App\Core\Auth\AuthMiddleware;
use App\Admin\Controllers\{
    DashboardController,
    PluginController,
    ThemeController,
    UserController,
    SettingsController
};

class AdminRouter {
    private Router $router;
    private AuthMiddleware $auth;

    public function __construct(Router $router) {
        $this->router = $router;
        $this->auth = new AuthMiddleware();
        $this->setupRoutes();
    }

    private function setupRoutes(): void {
        // Dashboard
        $this->router->get('/admin', [$this, 'handleDashboard'], [$this->auth]);
        
        // Plugins
        $this->router->get('/admin/plugins', [$this, 'handlePluginList'], [$this->auth]);
        $this->router->get('/admin/plugins/configure/:id', [$this, 'handlePluginConfigure'], [$this->auth]);
        $this->router->post('/admin/plugins/activate/:id', [$this, 'handlePluginActivate'], [$this->auth]);
        $this->router->post('/admin/plugins/deactivate/:id', [$this, 'handlePluginDeactivate'], [$this->auth]);
        $this->router->post('/admin/plugins/save-config/:id', [$this, 'handlePluginSaveConfig'], [$this->auth]);
        
        // Themes
        $this->router->get('/admin/themes', [$this, 'handleThemeList'], [$this->auth]);
        $this->router->post('/admin/themes/activate/:id', [$this, 'handleThemeActivate'], [$this->auth]);
        $this->router->get('/admin/themes/configure/:id', [$this, 'handleThemeConfigure'], [$this->auth]);
        $this->router->post('/admin/themes/save-config/:id', [$this, 'handleThemeSaveConfig'], [$this->auth]);
        
        // Users
        $this->router->get('/admin/users', [$this, 'handleUserList'], [$this->auth]);
        $this->router->get('/admin/users/create', [$this, 'handleUserCreate'], [$this->auth]);
        $this->router->post('/admin/users/create', [$this, 'handleUserStore'], [$this->auth]);
        $this->router->get('/admin/users/edit/:id', [$this, 'handleUserEdit'], [$this->auth]);
        $this->router->post('/admin/users/update/:id', [$this, 'handleUserUpdate'], [$this->auth]);
        $this->router->post('/admin/users/delete/:id', [$this, 'handleUserDelete'], [$this->auth]);
        
        // Settings
        $this->router->get('/admin/settings', [$this, 'handleSettings'], [$this->auth]);
        $this->router->post('/admin/settings/save', [$this, 'handleSettingsSave'], [$this->auth]);
    }

    // Dashboard handlers
    public function handleDashboard(): void {
        (new DashboardController())->index();
    }

    // Plugin handlers
    public function handlePluginList(): void {
        (new PluginController())->index();
    }

    public function handlePluginConfigure(array $params): void {
        (new PluginController())->configure($params['id']);
    }

    public function handlePluginActivate(array $params): void {
        (new PluginController())->activate($params['id']);
    }

    public function handlePluginDeactivate(array $params): void {
        (new PluginController())->deactivate($params['id']);
    }

    public function handlePluginSaveConfig(array $params): void {
        (new PluginController())->saveConfiguration($params['id']);
    }

    // Theme handlers
    public function handleThemeList(): void {
        (new ThemeController())->index();
    }

    public function handleThemeActivate(array $params): void {
        (new ThemeController())->activate($params['id']);
    }

    public function handleThemeConfigure(array $params): void {
        (new ThemeController())->configure($params['id']);
    }

    public function handleThemeSaveConfig(array $params): void {
        (new ThemeController())->saveConfiguration($params['id']);
    }

    // User handlers
    public function handleUserList(): void {
        (new UserController())->index();
    }

    public function handleUserCreate(): void {
        (new UserController())->create();
    }

    public function handleUserStore(): void {
        (new UserController())->store();
    }

    public function handleUserEdit(array $params): void {
        (new UserController())->edit($params['id']);
    }

    public function handleUserUpdate(array $params): void {
        (new UserController())->update($params['id']);
    }

    public function handleUserDelete(array $params): void {
        (new UserController())->delete($params['id']);
    }

    // Settings handlers
    public function handleSettings(): void {
        (new SettingsController())->index();
    }

    public function handleSettingsSave(): void {
        (new SettingsController())->save();
    }
}
