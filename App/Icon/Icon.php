<?php

namespace App\Icon;

class Icon
{
    public static function use(string $icon, int|string $size): string
    {
        $path = __DIR__ . "/{$icon}.php";

        if (!file_exists($path)) {
            throw new \RuntimeException("Icon file not found: {$icon}");
        }

        include_once $path;

        if (!function_exists($icon)) {
            throw new \RuntimeException("Icon function not defined in {$icon}.php");
        }

        return $icon($size);
    }
}