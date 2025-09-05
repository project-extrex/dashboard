<?php
namespace App\Core\Plugin;

use App\Database\Repositories\SettingsRepository;

class PluginManager {
    private static ?PluginManager $instance = null;
    private array $plugins = [];
    private array $hooks = [];
    private array $adminMenus = [];
    private array $activePlugins = [];
    private SettingsRepository $settings;

    private function __construct() {
        $this->settings = new SettingsRepository();
        $this->loadActivePlugins();
    }

    public static function getInstance(): PluginManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadActivePlugins(): void {
        $activePlugins = $this->settings->get('active_plugins', []);
        $this->activePlugins = is_array($activePlugins) ? $activePlugins : [];
    }

    public function loadPlugins(): void {
        $pluginsDir = dirname(__DIR__, 3) . '/plugins';
        if (!is_dir($pluginsDir)) {
            return;
        }

        // Load plugin manifests
        foreach (glob($pluginsDir . '/*', GLOB_ONLYDIR) as $pluginDir) {
            $manifest = $this->loadPluginManifest($pluginDir);
            if ($manifest && in_array($manifest['id'], $this->activePlugins)) {
                $this->loadPlugin($manifest);
            }
        }
    }

    private function loadPluginManifest(string $pluginDir): ?array {
        $manifestFile = $pluginDir . '/plugin.json';
        if (!file_exists($manifestFile)) {
            return null;
        }

        $manifest = json_decode(file_get_contents($manifestFile), true);
        if (!$this->validateManifest($manifest)) {
            return null;
        }

        $manifest['path'] = $pluginDir;
        return $manifest;
    }

    private function validateManifest(array $manifest): bool {
        $required = ['name', 'version', 'main', 'description', 'author'];
        foreach ($required as $field) {
            if (!isset($manifest[$field])) {
                return false;
            }
        }
        return true;
    }

    private function loadPlugin(array $manifest): void {
        $pluginFile = $manifest['path'] . '/' . $manifest['main'];
        if (!file_exists($pluginFile)) {
            return;
        }

        require_once $pluginFile;
        $className = $manifest['class'] ?? $this->getPluginClassName($manifest['name']);
        
        if (class_exists($className)) {
            $plugin = new $className($manifest);
            if ($plugin instanceof PluginInterface) {
                $this->plugins[$manifest['name']] = $plugin;
                $plugin->onActivate();
            }
        }
    }

    private function getPluginClassName(string $name): string {
        return str_replace([' ', '-', '_'], '', ucwords($name)) . 'Plugin';
    }

    public function addHook(string $hookName, callable $callback, int $priority = 10): void {
        if (!isset($this->hooks[$hookName])) {
            $this->hooks[$hookName] = [];
        }
        $this->hooks[$hookName][] = [
            'callback' => $callback,
            'priority' => $priority
        ];
        
        // Sort by priority
        usort($this->hooks[$hookName], function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
    }

    public function executeHook(string $hookName, ...$args) {
        if (!isset($this->hooks[$hookName])) {
            return null;
        }

        $result = null;
        foreach ($this->hooks[$hookName] as $hook) {
            $result = call_user_func_array($hook['callback'], $args);
        }
        return $result;
    }

    public function addAdminMenu(string $title, string $slug, callable $callback, string $icon = 'fas fa-puzzle-piece', int $position = 100): void {
        $this->adminMenus[$slug] = [
            'title' => $title,
            'slug' => $slug,
            'callback' => $callback,
            'icon' => $icon,
            'position' => $position
        ];
    }

    public function getAdminMenus(): array {
        uasort($this->adminMenus, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });
        return $this->adminMenus;
    }

    public function getPlugin(string $name): ?PluginInterface {
        return $this->plugins[$name] ?? null;
    }

    public function getAllPlugins(): array {
        return $this->plugins;
    }
}
