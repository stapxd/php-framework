<?php

use Controllers\HomeController;
use Vendor\Facades\Router;

// Router::get('/', function(){
//     return view('home.php');
// });

Router::get('/', [HomeController::class, 'index']);

Router::post('/create', [HomeController::class, 'create']);