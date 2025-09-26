<?php

require __DIR__.'/vendor/autoload.php';

use Vendor\App\Application;

$app = new Application();

require __DIR__.'/routes/routes.php';

$app->handleRequest($_SERVER['REQUEST_URI']);