<?php

use Vendor\Facades\Router;

Router::get('/', function(){
    return view('home.php');
});

Router::get('/routes', function(){
    Router::routesInfo();
});