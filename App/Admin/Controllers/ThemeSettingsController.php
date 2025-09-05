<?php
namespace App\Admin\Controllers;

use App\Core\Plugin\PluginManager;
use App\Helper\Theme;
use App\Database\Repositories\SettingsRepository;

class ThemeSettingsController {
    private SettingsRepository $settings;

    public function __construct() {
        $this->settings = new SettingsRepository();
    }

    public function index(): void {
        $activeTheme = $this->settings->get('active_theme', 'extrax');
        $themes = $this->getAvailableThemes();
        $themeSettings = $this->getThemeSettings($activeTheme);
        
        Theme::setLayout('layouts/admin');
        Theme::render('admin/themes/index', [
            'title' => 'Theme Settings',
            'activeTheme' => $activeTheme,
            'themes' => $themes,
            'settings' => $themeSettings
        ]);
    }

    public function activate(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $theme = $_POST['theme'] ?? '';
        if ($this->isValidTheme($theme)) {
            $this->settings->set('active_theme', $theme);
            $this->settings->save();
            
            // Clear theme cache
            Theme::init($theme);
            
            header('Location: ' . Theme::url('admin/themes?success=1'));
            exit;
        }
        
        header('Location: ' . Theme::url('admin/themes?error=invalid_theme'));
        exit;
    }

    public function saveSettings(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $theme = $_POST['theme'] ?? '';
        $settings = $_POST['settings'] ?? [];

        if ($this->isValidTheme($theme)) {
            $this->settings->set("theme_{$theme}_settings", $settings);
            $this->settings->save();
            
            header('Location: ' . Theme::url('admin/themes?success=settings_saved'));
            exit;
        }
        
        header('Location: ' . Theme::url('admin/themes?error=invalid_settings'));
        exit;
    }

    private function getAvailableThemes(): array {
        $themes = [];
        $themesDir = dirname(__DIR__, 3) . '/theme';
        
        foreach (glob($themesDir . '/*', GLOB_ONLYDIR) as $themeDir) {
            $themeJson = $themeDir . '/theme.json';
            if (file_exists($themeJson)) {
                $config = json_decode(file_get_contents($themeJson), true) ?? [];
                $themeName = basename($themeDir);
                $themes[$themeName] = [
                    'name' => $config['name'] ?? $themeName,
                    'description' => $config['description'] ?? '',
                    'version' => $config['version'] ?? '1.0.0',
                    'author' => $config['author'] ?? 'Unknown',
                    'screenshot' => file_exists($themeDir . '/screenshot.png') 
                        ? Theme::url("theme/{$themeName}/screenshot.png") 
                        : Theme::url('admin/assets/images/no-preview.png'),
                    'settings' => $config['settings'] ?? []
                ];
            }
        }

        return $themes;
    }

    private function getThemeSettings(string $theme): array {
        $savedSettings = $this->settings->get("theme_{$theme}_settings", []);
        $themes = $this->getAvailableThemes();
        $defaultSettings = $themes[$theme]['settings'] ?? [];
        
        return array_merge($defaultSettings, $savedSettings);
    }

    private function isValidTheme(string $theme): bool {
        $themes = $this->getAvailableThemes();
        return isset($themes[$theme]);
    }
}
