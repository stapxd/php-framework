<?php

/** @var Application $app */

use Controllers\HomeController;

// $app->router()->get('/', function(){
//     return view('home.php');
// });

$app->router()->get('/', [HomeController::class, 'index']);