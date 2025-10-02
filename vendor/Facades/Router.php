<?php

namespace Vendor\Facades;

use Vendor\App\Application;
use Vendor\Foundation\Request;

class Router extends Facade {
    
    static function get(string $path, $callback) {
        $router = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $router->get($path, $callback);
    }

    static function post(string $path, $callback) {
        $router = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $router->post($path, $callback);
    }

    static function execute(Request $request) { 
        $router = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $router->execute($request);
    }

    static function getFacadeAccessor(): string{
        return 'router';
    }
}