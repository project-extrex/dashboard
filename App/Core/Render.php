<?php

namespace App\Core;

use App\Database\Entities\Settings;
use App\Database\Entities\User;

require_once __DIR__ . '/../Helper/ExtraxEnqueue.php';

class Render
{
    private $settingsRepo;
    private $entityManager;

    public function __construct($settingsRepo, $entityManager)
    {
        $this->settingsRepo = $settingsRepo;
        $this->entityManager = $entityManager;
    }

    /**
     * Render a frontend/theme view
     */
    public function render(string $view, array $data = []): void
    {
        $theme = $this->settingsRepo->getSetting('theme') ?? 'extrax';
        $themeJson = file_get_contents(__DIR__ . '/../../theme/' . $theme . '/theme.json');
        $metadataTheme = json_decode($themeJson);

        $siteName = $this->settingsRepo->getSetting('site_name') ?? 'Extrax';

        // include admin sticky header if the user is admin
        // include_once __DIR__ . '/../../connection.php';

        if (isset($_SESSION['user_id'])) {
            $user = $this->entityManager->find(User::class, $_SESSION['user_id']);
            if ($user && $user->isAdmin()) {
                include __DIR__ . '/../Admin/components/adminHeader.php';
            }
        }

        // Include theme header and functions

        $headerPath = __DIR__ . "/../../theme/{$theme}/header.php";
        if (is_file($headerPath))
            include $headerPath;

        $functionPath = __DIR__ . "/../../theme/{$theme}/function.php";
        if (file_exists($functionPath))
            include $functionPath;

        extrax_run_headers($siteName, $view);

        // Load the requested page
        $pageFile = $metadataTheme->page->$view ?? null;
        $themePath = __DIR__ . "/../../theme/{$theme}/" . $pageFile;

        if ($pageFile && file_exists($themePath)) {
            extract($data);
            include $themePath;
        } else {
            echo 'Theme view not found: ' . htmlspecialchars($view);
        }

        // Include footer
        $footerPath = __DIR__ . "/../../theme/{$theme}/footer.php";
        if (file_exists($footerPath))
            include $footerPath;

        extrax_run_footer();
    }

    /**
     * Render an admin view
     */
    public function renderAdmin(string $view, array $data = []): void
    {
        $adminPath = __DIR__ . '/../Admin/' . $view . '.php';

        if (file_exists($adminPath)) {
            echo '<!DOCTYPE html>
                 <html lang="en">
                 <head>
                 <meta charset="UTF-8">
                 <meta name="viewport" content="width=device-width, initial-scale=1.0">
                 <title>Admin - ' . htmlspecialchars($view) . '</title>
                ';
          echo "<link href=\"/admin/assets/{$view}/style.css\" />";
          echo '
              </head>
              <body>';

            extract($data);
            $adminView = true;
            $user = $this->entityManager->find(User::class, $_SESSION['user_id']);
            include __DIR__ . '/../Admin/components/adminHeader.php';
            include $adminPath;

            echo '</body></html>';
        } else {
            echo 'Admin view not found: ' . htmlspecialchars($view);
        }
    }
}
