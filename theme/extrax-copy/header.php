<?php
function ext_header(string $siteName, string $view): void {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($siteName) ?> - <?= str_replace('/', ' - ', htmlspecialchars($view)) ?></title>
    </head>
    <body>
    <?php
}

extrax_enqueue_header('ext_header');