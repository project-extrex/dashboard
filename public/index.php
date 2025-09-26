<?php
/**
 * Development error display with styling
 * Shows all PHP errors, warnings, notices, and fatal errors in a styled block
 * Only use in development!
 */

 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);


// Styled error output
function display_error_styled(array $error) {
    echo "<div style='
        background: #2b2b2b;
        color: #f8f8f2;
        font-family: monospace;
        padding: 20px;
        margin: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        overflow-x: auto;
    '>";
    echo "<h2 style='color: #ff5555; margin-top: 0;'>PHP Error:</h2>";
    echo "<pre>";
    print_r($error);
    echo "</pre>";
    echo "</div>";
}

// Catch runtime fatal errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error) {
        display_error_styled($error);
    }
});

// Convert warnings/notices to exceptions
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Catch uncaught exceptions
set_exception_handler(function ($exception) {
    display_error_styled([
        'type' => 'Uncaught Exception',
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
});

// Parse errors for included files (PHP 7+)
if (PHP_VERSION_ID >= 70100) {
    register_shutdown_function(function () {
        $error = error_get_last();
        if ($error && $error['type'] === E_PARSE) {
            display_error_styled($error);
        }
    });
}

// --------------------------
//  app bootstrap 
// --------------------------


require_once __DIR__ . "/../bootstrap.php";
require_once __DIR__ . "/../App/router/main.php";