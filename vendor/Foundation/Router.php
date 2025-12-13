<?php

namespace Vendor\Foundation;

use Exception;

class Router {
    private array $routes = [];

    private array $currentGroup = [
        'prefix' => '',
        'middlewares' => []
    ];

    public function __construct() 
    {}

    public function routesInfo(){
        if (empty($this->routes)) {
            echo '
            <div style="
                font-family: sans-serif;
                color: #9d2424;
                background: #fdeaea;
                padding: 16px;
                border-radius: 8px;
                width: fit-content;
            ">
                <strong>No routes registered</strong>
            </div>';
            return;
        }

        echo '
        <div style="width: 100%; font-family: sans-serif; margin: 20px 0;">
            <h2 style="margin-bottom: 12px; color: #333;">Registered Routes</h2>

            <table style="
                border-collapse: collapse;
                min-width: 600px;
                background: #fff;
                border: 1px solid #ddd;
                overflow: hidden;
                width: 100%;
            ">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="width: 10%; padding: 12px 16px; text-align: left; border: 1px solid #ddd;">METHOD</th>
                        <th style="width: 30%; padding: 12px 16px; text-align: left; border: 1px solid #ddd;">PATH</th>
                        <th style="width: 60%; padding: 12px 16px; text-align: left; border: 1px solid #ddd;">CALLBACK</th>
                    </tr>
                </thead>
                <tbody>
        ';

        foreach ($this->routes as $index => $route) {
            $method = strtoupper($route->getMethod());
            $path   = $route->getPath();
            $callback = $route->getCallback();

            $methodColor = match ($method) {
                'GET'    => '#1e88e5',
                'POST'   => '#43a047',
                default  => '#6d6d6d',
            };

            if (is_callable($callback)) {
                $callbackText = 'Closure';
            } elseif (is_array($callback) && count($callback) === 2) {
                [$class, $methodName] = $callback;
                $callbackText = $class . '::' . $methodName;
            } else {
                $callbackText = 'Unknown';
            }
            $rowBg = $index % 2 === 0 ? '#fafafa' : '#fff';
            echo '
                <tr style="background: '.$rowBg.';">
                    <td style="padding: 10px 16px; border: 1px solid #ddd;">
                        <span style="
                            display: inline-block;
                            padding: 4px 10px;
                            border-radius: 6px;
                            font-weight: bold;
                            color: #fff;
                            background: '.$methodColor.';
                            font-size: 12px;
                        ">
                            '.$method.'
                        </span>
                    </td>
                    <td style="padding: 10px 16px; color: #1565c0; border: 1px solid #ddd;">
                        '.$path.'
                    </td>
                    <td style="
                        padding: 10px 16px;
                        font-family: monospace;
                        color: #444;
                        border: 1px solid #ddd;
                    ">
                        '.$callbackText.'
                    </td>
                </tr>
            ';
        }

        echo '
                </tbody>
            </table>
        </div>
        ';
    }

    public function middleware(array $middlewares) {
        $this->currentGroup['middlewares'] = array_merge(
            $this->currentGroup['middlewares'] ?? [],
            $middlewares
        );
        return $this;
    }

    public function group(string $prefix, callable $callback) {
        if(!str_starts_with($prefix, '/')) {
            $prefix = '/'.$prefix;
        }
        $previousGroup = $this->currentGroup['prefix'] ?? '';
        $previousMiddlewares = $this->currentGroup['middlewares'] ?? [];

        $this->currentGroup['prefix'] .= $prefix;

        $callback();

        $this->currentGroup['prefix'] = $previousGroup;
        $this->currentGroup['middlewares'] = $previousMiddlewares;
    }

    public function get(string $path, $callback) {
        return $this->addRoute('GET', $path, $callback);
    }

    public function post(string $path, $callback) {
        return $this->addRoute('POST', $path, $callback);
    }

    public function execute(Request $request) {

        $realPath = $request->getPath();
        //var_dump($realPath);

        $method = $request->getMethod();

        $currentRoute = $this->findRoute($method, $realPath);

        if($currentRoute) {
            return $currentRoute->execute($request);
        }
        else {
            return include(__DIR__.'\..\..\Views\404.php');
        }
    }

    private function modifyPath(string $path): string {
        if(!str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        if($this->currentGroup['prefix'] !== '') {
            $path = $this->currentGroup['prefix'] . $path;
        }

        return $path;
    }

    private function findRoute(string $method, string $realPath) {
        foreach ($this->routes as $route) {
            if($route->getMethod() === $method && $route->getPath() === $realPath) {
                return $route;
            }
        }
        return null;
    }

    private function addRoute(string $method, string $path, $callback) {
        $path = $this->modifyPath($path);

        if($this->findRoute($method, $path)) {
            throw new Exception("Route already exists for $method $path");
        }

        $this->routes[] = new Route($method, $path, $callback);

        $currentRoute = $this->findRoute($method, $path);
        
        $currentRoute->middleware($this->currentGroup['middlewares'] ?? []);

        return $currentRoute;
    }
}