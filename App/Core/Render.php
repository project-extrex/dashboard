<?php
namespace App\Core;

use App\Database\Entities\Settings;
use App\Database\Entities\User;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class Render
{
    private $settingsRepo;
    private $entityManager;
    private Factory $blade;
    private string $theme;
    private string $themePath;
    private string $cachePath;

    public function __construct($settingsRepo, $entityManager, string $cachePath = __DIR__ . '/../../cache')
    {
        $this->settingsRepo = $settingsRepo;
        $this->entityManager = $entityManager;
        $this->cachePath = $cachePath;

        $this->theme = $this->settingsRepo->getSetting('theme') ?? 'extrax';
        $this->themePath = __DIR__ . "/../../theme/{$this->theme}";

        // Ensure cache folder exists
        if (!is_dir($this->cachePath))
            mkdir($this->cachePath, 0777, true);

        // Setup Blade
        $container = new Container;
        $filesystem = new Filesystem;
        $events = new Dispatcher($container);

        $finder = new FileViewFinder($filesystem, [$this->themePath]);

        $resolver = new EngineResolver();
        $bladeCompiler = new BladeCompiler($filesystem, $this->cachePath);
        $resolver->register('blade', fn() => new CompilerEngine($bladeCompiler));

        // Optional: PHP fallback engine
        $resolver->register('php', fn() => new CompilerEngine($bladeCompiler));

        $this->blade = new Factory($resolver, $finder, $events);
    }

    /**
     * Render frontend Blade view
     */
    public function view(string $file, array $data = []): void
    {
        $data['siteName'] = $this->settingsRepo->getSetting('site_name') ?? 'Extrax';
        $data['theme'] = $this->theme;

        if (isset($_SESSION['user_id'])) {
            $data['user'] = $this->entityManager->find(User::class, $_SESSION['user_id']);
        }

        $themeData = json_decode(file_get_contents($this->themePath . '/theme.json'), true);

        $pagePath = $themeData['page'][$file] ?? null;

        if ($pagePath) {
            // Strip leading './' and convert slashes to dot notation for Blade
            $bladeView = str_replace('/', '.', ltrim($pagePath, './'));

            echo $this->blade->make($bladeView, $data)->render();
            return;
        }

        echo 'Theme view not found: ' . htmlspecialchars($file);
    }

    /**
     * Render admin page (still PHP include)
     */
    public function renderAdmin(string $file, array $data = []): void
    {
        $adminPath = __DIR__ . '/../Admin/' . $file . '.php';

        if (file_exists($adminPath)) {
            $data['adminView'] = true;
            $data['user'] = $_SESSION['user_id']
                ? $this->entityManager->find(User::class, $_SESSION['user_id'])
                : null;

            extract($data, EXTR_SKIP);
            include __DIR__ . '/../Admin/components/adminHeader.php';
            /*include $adminPath;
            echo "
            </body>
            </html>
            ";*/
        } else {
            echo 'Admin view not found: ' . htmlspecialchars($file);
        }
    }
}
