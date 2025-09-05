<?php
namespace App\Core\Plugin;

abstract class BasePlugin implements PluginInterface {
    protected array $manifest;

    public function __construct(array $manifest) {
        $this->manifest = $manifest;
    }

    public function getManifest(): array {
        return $this->manifest;
    }

    public function addHook(string $hookName, callable $callback, int $priority = 10): void {
        PluginManager::getInstance()->addHook($hookName, $callback, $priority);
    }

    public function addAdminMenu(string $title, string $slug, callable $callback, string $icon = 'fas fa-puzzle-piece', int $position = 100): void {
        PluginManager::getInstance()->addAdminMenu($title, $slug, $callback, $icon, $position);
    }
}
