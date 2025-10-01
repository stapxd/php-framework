<?php

namespace Vendor\Facades;

use Vendor\App\Application;

class Router extends Facade {
    
    static function get(string $path, $callback) {
        $router = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $router->get($path, $callback);
    }

    static function post(string $path, $callback) {
        $router = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $router->post($path, $callback);
    }

    static function execute(string $path) { 
        $router = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $router->execute($path);
    }

    static function getFacadeAccessor(): string{
        return 'router';
    }
}