<?php

namespace Vendor\Facades;

use Vendor\Foundation\Request;

class Router extends Facade {
    
    static function get(string $path, $callback) {
        $router = static::getInstance();
        
        return $router->get($path, $callback);
    }

    static function post(string $path, $callback) {
        $router = static::getInstance();
        
        return $router->post($path, $callback);
    }

    static function execute(Request $request) { 
        $router = static::getInstance();
        
        return $router->execute($request);
    }

    static function getFacadeAccessor(): string {
        return 'router';
    }

    protected static function getInstance() {
        return app(static::getFacadeAccessor());
    }
}