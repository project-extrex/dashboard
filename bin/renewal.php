#!/usr/bin/env php
<?php

use App\Database\Entities\Servers;

define("internal_function_call", true);

require_once __DIR__ . "/../bootstrap.php";

$text   = "Checking servers for expiry...";

// Box characters
$top        = "┌───────────────────────────────┐";
$middle     = "│ " . str_pad($text, 29, " ", STR_PAD_BOTH) . " │";
$bottom     = "└───────────────────────────────┘";

// Green background + white text (compatible)
$greenWhite = "\e[42;37m"; 
$reset      = "\e[0m";

// Print box
echo $greenWhite . $top . $reset . PHP_EOL;
echo $greenWhite . $middle . $reset . PHP_EOL;
echo $greenWhite . $bottom . $reset . PHP_EOL;

while (true):
// Fetch all servers
$servers = $entityManager->getRepository(Servers::class)->findAll();

foreach ($servers as $server) {
    $status = $server->isExpired() ? "\e[31mExpired\e[0m" : "\e[32mActive\e[0m";
    echo "Server '{$server->getName()}' status: $status" . PHP_EOL;
}
sleep(5);
endwhile;