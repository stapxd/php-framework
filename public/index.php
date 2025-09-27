<?php

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->safeLoad();

$app = require __DIR__.'/../app/app.php';

require __DIR__.'/../routes/routes.php';

$app->handleRequest($_SERVER['REQUEST_URI']);