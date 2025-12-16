<?php

use App\Middlewares\TestMiddleware2;
use App\Middlewares\TestMiddleware3;
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

Router::middleware([TestMiddleware2::class])->group('users', function() {
    Router::get('/login', [HomeController::class, 'login']);
    Router::post('/login', [HomeController::class, 'loginPost']);

    Router::get('/register', [HomeController::class, 'register'])->middleware([TestMiddleware3::class]);
    Router::post('/register', [HomeController::class, 'registerPost']);


    Router::post('/logout', [HomeController::class, 'logoutPost']);
});

Router::group('form', function() {
    Router::get('/', [HomeController::class, 'formIndex']);
    Router::post('/submit', [HomeController::class, 'formSubmit']);
});