<?php

namespace Vendor\Foundation;

use Exception;
use ReflectionMethod;
use Vendor\Foundation\Request;
use Vendor\General\Converter;

class Route {

    private array $middlewares = [];

    public function __construct(private string $method, private string $path, private $callback) 
    { }

    public function getMethod(): string {
        return $this->method;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getCallback() {
        return $this->callback;
    }

    public function middleware(array $middlewares) {
        $this->middlewares = array_merge(
            $this->middlewares, 
            $middlewares
        );
    }

    public function execute(Request $request) {
        $final = function($request){
            $callback = $this->callback;
            if(is_callable($callback)) 
                { $callback(); }
            else if (is_array($callback) && count($callback) == 2) {

                [$class, $method] = $callback;
                
                $reflection = new ReflectionMethod($class, $method);
                $args = $this->getArgs($reflection, $request);

                if (method_exists($class, $method)) {
                    $object = new $class();
                    return $reflection->invokeArgs($object, $args);
                }
            }
        };
    
        $next =  $final;

        foreach (array_reverse($this->middlewares) as $middlewareClass) {
            if (!class_exists($middlewareClass)) {
                throw new Exception("Middleware class $middlewareClass does not exist.");
            }
            $middleware = new $middlewareClass();
            
            $prevNext = $next;

            $next = function($request) use ($middleware, $prevNext) {
                return $middleware->handle($request, $prevNext);
            };
        }

        return $next($request);
    }

    private function getArgs(ReflectionMethod $reflection, Request $request) {
        $args = [];
        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();

            if($type && !$type->isBuiltin()) {
                $typeName = $type->getName();
                if($typeName == Request::class)
                    $args[] = $request;
                else 
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