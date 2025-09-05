<?php
namespace App\Admin\Controllers;

class ThemeController extends AdminBaseController {
    public function index(): void {
        $this->render('themes/index', [
            'title' => 'Themes',
            'themes' => $this->getInstalledThemes()
        ]);
    }

    public function configure(string $themeId): void {
        $theme = $this->getThemeById($themeId);
        if (!$theme) {
            // Handle 404
            return;
        }

        $this->render('themes/configure', [
            'title' => 'Configure Theme',
            'theme' => $theme
        ]);
    }

    public function saveConfiguration(string $themeId): void {
        // TODO: Implement theme configuration saving
    }

    private function getInstalledThemes(): array {
        $themesDir = dirname(__DIR__, 3) . '/theme';
        $themes = [];
        
        foreach (scandir($themesDir) as $item) {
            if ($item === '.' || $item === '..' || !is_dir("$themesDir/$item")) {
                continue;
            }
            
            $themeJson = "$themesDir/$item/theme.json";
            if (!file_exists($themeJson)) {
                continue;
            }
            
            $themes[] = [
                'id' => $item,
                'config' => json_decode(file_get_contents($themeJson), true)
            ];
        }
        
        return $themes;
    }

    private function getThemeById(string $themeId): ?array {
        $themes = $this->getInstalledThemes();
        foreach ($themes as $theme) {
            if ($theme['id'] === $themeId) {
                return $theme;
            }
        }
        return null;
    }
}
