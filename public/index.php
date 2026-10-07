<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 1. Register Composer Autoloader safely
$autoloadPath = __DIR__.'/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    $autoloadPath = __DIR__.'/vendor/autoload.php';
}

if (!file_exists($autoloadPath)) {
    die("<h3>Laravel System Error: Vendor directory is missing!</h3><p>Please upload the 'vendor' folder or run 'composer install' on your cPanel server.</p>");
}

require_once $autoloadPath;

// 2. Bootstrap Laravel and handle the request
$appPath = __DIR__.'/../bootstrap/app.php';
if (!file_exists($appPath)) {
    $appPath = __DIR__.'/bootstrap/app.php';
}

(require_once $appPath)
    ->handleRequest(Request::capture());

