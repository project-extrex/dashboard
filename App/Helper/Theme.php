<?php
namespace App\Helper;

class Theme {
    protected static string $basePath;
    protected static array $data = [];
    protected static ?string $currentTheme = null;
    protected static ?string $currentLayout = null;
    protected static array $settings = [];

    public static function init(string $themeName, string $basePath = null): void {
        self::$currentTheme = $themeName;
        self::$basePath = $basePath ?? dirname(__DIR__, 2);
        self::loadThemeSettings();
    }

    protected static function loadThemeSettings(): void {
        $settingsFile = self::getThemePath('theme.json');
        if (file_exists($settingsFile)) {
            self::$settings = json_decode(file_get_contents($settingsFile), true) ?? [];
        }
    }

    public static function getThemePath(string $path = ''): string {
        return self::$basePath . '/theme/' . self::$currentTheme . '/' . ltrim($path, '/');
    }

    public static function getPublicPath(string $path = ''): string {
        return '/theme/' . self::$currentTheme . '/' . ltrim($path, '/');
    }

    public static function renderHead(): void {
        extrax_run_headers(self::$data['siteName'] ?? '', self::$data['view'] ?? '');
    }

    public static function renderView(): void {
        $view = self::getThemePath('views/' . (self::$data['view'] ?? 'index') . '.php');
        if (file_exists($view)) {
            extract(self::$data);
            require $view;
        }
    }

    public static function renderScripts(): void {
        extrax_run_footer();
    }

    public static function render(string $template, array $data = []): void {
        self::$data = array_merge(self::$data, $data);
        extract(self::$data);

        if (self::$currentLayout) {
            ob_start();
            require self::getThemePath('views/' . $template . '.php');
            $content = ob_get_clean();
            require self::getThemePath('views/' . self::$currentLayout . '.php');
        } else {
            require self::getThemePath('views/' . $template . '.php');
        }
    }

    public static function setLayout(?string $layout): void {
        self::$currentLayout = $layout;
    }

    public static function content(): void {
        echo self::$data['content'] ?? '';
    }

    public static function url(string $path): string {
        return '/' . ltrim($path, '/');
    }

    public static function asset(string $path): string {
        return self::getPublicPath('assets/' . ltrim($path, '/'));
    }

    public static function css(string $file): void {
        echo '<link rel="stylesheet" href="' . self::asset('css/' . $file) . '">';
    }

    public static function js(string $file): void {
        echo '<script src="' . self::asset('js/' . $file) . '"></script>';
    }

    public static function getSetting(string $key, $default = null) {
        return self::$settings[$key] ?? $default;
    }

    public static function partial(string $name, array $data = []): void {
        extract(array_merge(self::$data, $data));
        require self::getThemePath('views/partials/' . $name . '.php');
    }

    public static function escape(string $value): string {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

}
