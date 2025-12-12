<?php

namespace Vendor\Foundation;

use Exception;
use ReflectionMethod;
use Vendor\General\Converter;

class Router {
    private array $routes;
    private string $currentGroup = '';

    public function __construct() {
        $routes = [];
    }

    public function routesInfo() {
        if(empty($this->routes)) {
            echo '<h1 style="
                font-family: sans-serif;
                color: #9d2424ff;
            ">No routes registered</h1>';
            return;
        }

        echo '<h1 style="
            font-family: sans-serif;
            color: #333;
        ">Registered Routes:</h1>';
        
        foreach ($this->routes as $method => $paths) {
            echo '<h2 style="
                font-family: sans-serif;
            ">'.$method.'</h2>';

            echo '<table style="
                width: 100%;
                border-collapse: collapse;
                background: #fff;
                font-family: sans-serif;
                font-size: 14px;
            ">';

            echo '
            <thead style="background: #f0f0f0;">
                <tr>
                    <th style="
                        width: 40%;
                        padding: 12px 14px;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        border: 1px solid #ddd;
                        border-bottom: 2px solid #ccc;
                        text-align: left;
                    ">Path</th>

                    <th style="
                        width: 60%;
                        padding: 12px 14px;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        border: 1px solid #ddd;
                        border-bottom: 2px solid #ccc;
                        text-align: left;
                    ">Callback</th>
                </tr>
            </thead>
            ';

            foreach ($paths as $path => $info) {
                echo '<tr style="transition: background 0.2s;" 
                        onmouseover="this.style.background=\'#f0f8ff\'"
                        onmouseout="this.style.background=\'\'">';

                echo '<td style="
                    padding: 10px 14px;
                    border: 1px solid #ddd;
                ">'.$path.'</td>';

                if (is_callable($info['callback'])) {
                    echo '<td style="
                        padding: 10px 14px;
                        border: 1px solid #ddd;
                    ">Closure</td>';
                }
                elseif (is_array($info['callback']) && count($info['callback']) == 2) {
                    [$class, $methodName] = $info['callback'];
                    echo '<td style="
                        padding: 10px 14px;
                        border: 1px solid #ddd;
                    ">'.$class.'::'.$methodName.'</td>';
                }

                echo '</tr>';
            }

            echo '</table>';
        }


        // echo '<pre>';
        // print_r($this->routes);
        // echo '</pre>';
    }

    public function group(string $prefix, callable $callback) {
        if(!str_starts_with($prefix, '/')) {
            $prefix = '/'.$prefix;
        }
        $previousGroup = $this->currentGroup;
        $this->currentGroup .= $prefix;

        $callback();

        $this->currentGroup = $previousGroup;
    }

    public function get(string $path, $callback) {
        $path = $this->modifyPath($path);

        $this->routes['GET'][$path] = [
                    'callback' => $callback
                ];
    }

    public function post(string $path, $callback) {
        $path = $this->modifyPath($path);

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

    private function modifyPath(string $path): string {
        if(!str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        if($this->currentGroup !== '') {
            $path = $this->currentGroup . $path;
        }

        return $path;
    }
}