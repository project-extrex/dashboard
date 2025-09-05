<?php
namespace App\Helper;

use App\Core\ServiceContainer;

/**
 * Helper functions for URLs and options
 */

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $basePath = rtrim($_SERVER['REQUEST_URI'], '/');
        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('get_option')) {
    function get_option(string $key, $default = null) {
        $container = ServiceContainer::getInstance();
        $settingsRepo = $container->getSettingsRepository();
        $setting = $settingsRepo->findOneBy(['key' => $key]);
        return $setting ? $setting->getValue() : $default;
    }
}

if (!function_exists('set_option')) {
    function set_option(string $key, $value): void {
        $container = ServiceContainer::getInstance();
        $settingsRepo = $container->getSettingsRepository();
        $setting = $settingsRepo->findOneBy(['key' => $key]);
        if (!$setting) {
            $setting = new \App\Database\Entities\Setting();
            $setting->setKey($key);
        }
        $setting->setValue($value);
        $container->getEntityManager()->persist($setting);
        $container->getEntityManager()->flush();
    }
}
