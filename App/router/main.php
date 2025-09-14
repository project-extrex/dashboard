<?php

use App\Core\Render;
use App\Core\RenderAdmin;
use App\Core\Router;
use App\Database\Entities\Settings;
use App\Database\Entities\User;
use App\Database\Entities\Resources;

session_start();
global $entityManager;

$settingsRepo = $entityManager->getRepository(Settings::class);
$router = new Router();
$renderer = new Render($settingsRepo, $entityManager);

// --- Helpers ---
function wantsJson(): bool
{
    return isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
}

function jsonResponse(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// --- Routers ---
require_once __DIR__ . "/indexRouter.php";
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/front.php";
require_once __DIR__ . "/admin.php";
require_once __DIR__ . '/staticServer.php';
require_once __DIR__ . "/../../theme/{$settingsRepo->getSetting('theme')}/functions.php";

// --- Dispatch ---
if (php_sapi_name() !== 'cli') {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} else {
    echo 'its running as cli';
}
