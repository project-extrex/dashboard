<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . "/connection.php";
require_once __DIR__ . "/App/Helper/Old.php";

\App\Registry\Menu::initial();

if (php_sapi_name() !== 'cli') {
    require_once __DIR__ . '/App/router/main.php';
} else {
    if(!internal_function_call) {
    echo 'this app running as cli';
    }
}
