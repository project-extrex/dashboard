<?php

namespace App\Icon;

class Icon
{
    /**
     * Load a PHP-based icon function.
     * The icon file must define a function named icon_{name}.
     */
    public static function use(string $icon, int|string $size): string
    {
        $path = __DIR__ . "/{$icon}.php";

        if (!file_exists($path)) {
            throw new \RuntimeException("Icon file not found: {$icon}");
        }

        include_once $path;

        $func = "icon_{$icon}";
        if (!function_exists($func)) {
            throw new \RuntimeException("Icon function not defined in {$icon}.php (expected {$func})");
        }

        return $func($size);
    }

    /**
     * Load an SVG file and return its raw content.
     */
    public static function useSvg(string $icon): string
    {
        $path = __DIR__ . "/{$icon}.svg";

        if (!file_exists($path)) {
            throw new \RuntimeException("Icon svg file not found: {$icon}");
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException("Failed to read SVG file: {$icon}");
        }

        return $content;
    }

    /**
     * Download an SVG from a URL and save it locally.
     */
    public static function downloadSvgIcon(string $url, string $name): void
    {
        $icondata = @file_get_contents($url);
        if ($icondata === false) {
            throw new \RuntimeException("Failed to download icon from {$url}");
        }

        $saved = @file_put_contents(__DIR__ . "/{$name}.svg", $icondata);
        if ($saved === false) {
            throw new \RuntimeException("Failed to save icon as {$name}.svg");
        }
    }

    /**
     * Delete an SVG icon by name.
     */
    public static function deleteSvgIcon(string $name): bool
    {
        $path = __DIR__ . "/{$name}.svg";

        if (!file_exists($path)) {
            throw new \RuntimeException("SVG icon not found: {$name}");
        }

        return unlink($path); 
    }
}
