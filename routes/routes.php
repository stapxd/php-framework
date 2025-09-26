<?php

use Controllers\HomeController;

/** @var Application $app */

$app->router()->get('/', [HomeController::class, 'index']);

$app->router()->get('/other', function(){
    echo "other";
});