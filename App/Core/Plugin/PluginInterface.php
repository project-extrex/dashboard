<?php
namespace App\Core\Plugin;

interface PluginInterface {
    public function onActivate(): void;
    public function onDeactivate(): void;
    public function getManifest(): array;
}
