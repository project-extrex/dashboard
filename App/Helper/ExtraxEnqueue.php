<?php
// holds all registered header callbacks
$extrax_headers = [];

function extrax_enqueue_header(callable $callback): void {
    global $extrax_headers;
    $extrax_headers[] = $callback;
}

function extrax_run_headers(string $siteName, string $view): void {
    global $extrax_headers;
    foreach ($extrax_headers as $callback) {
        $callback($siteName, $view);
    }
}

// holds all registered footer callbacks
$extrax_footer = [];

function extrax_enqueue_footer(callable $callback): void {
    global $extrax_footer;
    $extrax_footer[] = $callback;
}

function extrax_run_footer(): void {
    global $extrax_footer;
    foreach ($extrax_footer as $callback) {
        $callback();
    }
}