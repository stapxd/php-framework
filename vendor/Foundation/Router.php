<?php

namespace Vendor\Foundation;

class Router {
    private array $routes;

    public function __construct() {
        $routes = [];
    }

    public function get(string $path, $callback) {
        $this->routes[$path] = [
                    'callback' => $callback,
                    'method' => 'get'
                ];
    }

    public function post(string $path, $callback) {
        $this->routes[$path] = [
                    'callback' => $callback,
                    'method' => 'post'
                ];
    }

    public function execute(string $path) {

        $realPath = explode('?', $path);
        //var_dump($realPath);

        if(!isset($this->routes[$realPath[0]]['callback'])) {
            return include(__DIR__.'\..\..\Views\404.php');
        }

        $callback = $this->routes[$realPath[0]]['callback'];

        if(is_callable($callback)) { $callback(); }
        else if (is_array($callback) && count($callback) == 2){

            [$class, $method] = $callback;
            
            if (method_exists($class, $method)) {
                $object = new $class();
                return $object->$method();
            }
            
        }
    }
}