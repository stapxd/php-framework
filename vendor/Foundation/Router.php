<?php

namespace Vendor\Foundation;

use Exception;
use ReflectionMethod;
use Vendor\General\Converter;

class Router {
    private array $routes;

    public function __construct() {
        $routes = [];
    }

    public function get(string $path, $callback) {
        $this->routes['GET'][$path] = [
                    'callback' => $callback
                ];
    }

    public function post(string $path, $callback) {
        $this->routes['POST'][$path] = [
                    'callback' => $callback
                ];
    }

    public function execute(Request $request) {

        $realPath = $request->getPath();
        //var_dump($realPath);

        $method = $request->getMethod();

        if(!$this->pathExists($method, $realPath)) {
            return include(__DIR__.'\..\..\Views\404.php');
        }

        $callback = $this->routes[$method][$realPath]['callback'];

        if(is_callable($callback)) { $callback(); }
        else if (is_array($callback) && count($callback) == 2){

            [$class, $method] = $callback;
            
            $reflection = new ReflectionMethod($class, $method);
            $args = $this->getArgs($reflection, $request);

            if (method_exists($class, $method)) {
                $object = new $class();
                return $reflection->invokeArgs($object, $args);
            }
        }
    }

    private function pathExists(string $method, string $path) {
        if(!isset($this->routes[$method][$path]['callback'])) {
            return false;
        }
        return true;
    }

    private function getArgs(ReflectionMethod $reflection, Request $request) {
        $args = [];
        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();

            if($type && !$type->isBuiltin()) {
                $typeName = $type->getName();
                $args[] = app($typeName);
            }
            else {
                $type = $param->getType();
                $name = $param->getName();
                
                $value = Converter::convertToType($type, $this->getRequestParamByMethod($request, $request->getMethod(), $name));
                
                if ($value === null && !$param->isOptional())
                    throw new Exception("Missing required parameter: $name");
                
                $args[] = $value ?? $param->getDefaultValue();
            }
        }
        return $args;
    }

    private function getRequestParamByMethod(Request $request, string $method, string $paramName) {
        $result = null;

        if($method == 'GET') $result = $request->query($paramName);
        else if ($method == 'POST') $result = $request->input($paramName);

        if($result == null) {
            throw new Exception("Param ($paramName) does not exist in $method request!");
        }

        return $result;
    }
}