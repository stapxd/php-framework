<?php

use Controllers\HomeController;
use Vendor\Facades\Router;

// Router::get('/', function(){
//     return view('home.php');
// });

Router::get('/routes', function(){
    Router::routesInfo();
});

Router::get('/', [HomeController::class, 'index']);

Router::post('/create', [HomeController::class, 'create']);

Router::group('users', function() {
    Router::get('/login', [HomeController::class, 'login']);
    Router::post('/login', [HomeController::class, 'loginPost']);

    Router::get('/register', [HomeController::class, 'register']);
    Router::post('/register', [HomeController::class, 'registerPost']);


    Router::post('/logout', [HomeController::class, 'logoutPost']);
});