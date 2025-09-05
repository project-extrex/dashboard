<?php
namespace App\Helper;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;
use JsonException;

class ThemeLoader
{
    /**
     * Recursively scan a directory for theme.json files
     * and yield decoded JSON data lazily.
     *
     * @param string $baseDir Path to start scanning
     * @return \Generator<string,array> Key = relative path, Value = decoded JSON
     */
    public function loadThemes(string $baseDir): \Generator
    {
        if (!is_dir($baseDir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $baseDir,
                FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'theme.json') {
                try {
                    $json = file_get_contents($file->getPathname());
                    if ($json === false) {
                        continue;
                    }

                    $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

                    // Key: relative directory path
                    $relativePath = str_replace(
                        $baseDir . DIRECTORY_SEPARATOR,
                        '',
                        $file->getPath()
                    );

                    yield $relativePath => $decoded;
                } catch (JsonException) {
                    // Skip invalid JSON
                    continue;
                }
            }
        }
    }
}