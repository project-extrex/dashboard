<?php
namespace App\Admin\Controllers;

use App\Core\Plugin\PluginManager;
use App\Helper\Theme;
use App\Database\Repositories\SettingsRepository;

class PluginController {
    private SettingsRepository $settings;
    private PluginManager $pluginManager;

    public function __construct() {
        $this->settings = new SettingsRepository();
        $this->pluginManager = PluginManager::getInstance();
    }

    public function index(): void {
        $plugins = $this->pluginManager->getAllPlugins();
        $availablePlugins = $this->getAvailablePlugins();
        
        Theme::setLayout('layouts/admin');
        Theme::render('admin/plugins/index', [
            'title' => 'Plugins',
            'activePlugins' => $plugins,
            'availablePlugins' => $availablePlugins
        ]);
    }

    public function activate(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $pluginName = $_POST['plugin'] ?? '';
        $plugin = $this->pluginManager->getPlugin($pluginName);
        
        if ($plugin) {
            try {
                $plugin->onActivate();
                $activePlugins = $this->settings->get('active_plugins', []);
                $activePlugins[] = $pluginName;
                $this->settings->set('active_plugins', array_unique($activePlugins));
                $this->settings->save();
                
                header('Location: ' . Theme::url('admin/plugins?success=activated'));
                exit;
            } catch (\Exception $e) {
                header('Location: ' . Theme::url('admin/plugins?error=activation_failed'));
                exit;
            }
        }
        
        header('Location: ' . Theme::url('admin/plugins?error=invalid_plugin'));
        exit;
    }

    public function deactivate(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $pluginName = $_POST['plugin'] ?? '';
        $plugin = $this->pluginManager->getPlugin($pluginName);
        
        if ($plugin) {
            try {
                $plugin->onDeactivate();
                $activePlugins = $this->settings->get('active_plugins', []);
                $activePlugins = array_diff($activePlugins, [$pluginName]);
                $this->settings->set('active_plugins', $activePlugins);
                $this->settings->save();
                
                header('Location: ' . Theme::url('admin/plugins?success=deactivated'));
                exit;
            } catch (\Exception $e) {
                header('Location: ' . Theme::url('admin/plugins?error=deactivation_failed'));
                exit;
            }
        }
        
        header('Location: ' . Theme::url('admin/plugins?error=invalid_plugin'));
        exit;
    }

    private function getAvailablePlugins(): array {
        $plugins = [];
        $pluginsDir = dirname(__DIR__, 3) . '/plugins';
        
        if (!is_dir($pluginsDir)) {
            return $plugins;
        }

        foreach (glob($pluginsDir . '/*', GLOB_ONLYDIR) as $pluginDir) {
            $manifestFile = $pluginDir . '/plugin.json';
            if (file_exists($manifestFile)) {
                $manifest = json_decode(file_get_contents($manifestFile), true) ?? [];
                if (isset($manifest['name'])) {
                    $plugins[$manifest['name']] = $manifest;
                }
            }
        }

        return $plugins;
    }
}
