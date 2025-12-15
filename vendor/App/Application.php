<?php

namespace Vendor\App;

use Closure;
use Exception;

use Vendor\Database\DatabaseFactory;
use Vendor\Facades\DB;
use Vendor\Facades\Router as RouterFacade;
use Vendor\Foundation\Request;
use Vendor\Foundation\Router;

class Application {
    private static ?Application $instance = null;

    private array $services;
    private array $bindings;

    private array $middlewares = [];
    
    private function __construct() {
        $this->register(RouterFacade::getFacadeAccessor(), new Router());
    }

    public function bind(string $abstract, $concrete, $shared = false) {
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared'   => $shared
        ];
    }

    public function make(string $abstract) {
        if(isset($this->services[$abstract]))
            return $this->services[$abstract];

        if(!isset($this->bindings[$abstract])) throw new Exception("Application::make : class $abstract was not bound");

        $concrete = $this->bindings[$abstract]['concrete'];
        $shared = $this->bindings[$abstract]['shared'];

        if ($concrete instanceof \Closure) {
            $object = $concrete($this);
        } elseif (is_object($concrete)) {
            $object = $concrete;
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $object = new $concrete();
        } else {
            throw new Exception("Cannot instantiate service: $abstract");
        }
        
        if($shared) {
            $this->register($abstract, $object);
        }
        
        return $object;
    }

    public function resolve(string $serviceName) {
        if(isset($this->services[$serviceName]))
            return $this->services[$serviceName];
    }

    public function register(string $serviceName, $service) {
        if(isset($this->services[$serviceName])){
            echo "Warning: service -> '$serviceName' already exists!";
        }

        $this->services[$serviceName] = $service;
    }

    public function handleRequest(Request $request) {
        $final = function($request){
            return RouterFacade::execute($request);
        };

        $next =  $final;

        foreach (array_reverse($this->middlewares) as $middlewareClass) {
            if(!class_exists($middlewareClass)) {
                throw new Exception("Middleware class $middlewareClass does not exist");
            }
            $middleware = new $middlewareClass();
            
            $prevNext = $next;

            $next = function($request) use ($middleware, $prevNext) {
                return $middleware->handle($request, $prevNext);
            };
        }
        
        return $next($request);
    }

    public function withDatabase(string $dbConnection) {
        if(!$dbConnection) throw new Exception('.env file does not have DB_CONNECTION!');

        $this->register(DB::getFacadeAccessor(), DatabaseFactory::createDatabase($dbConnection));
        return self::$instance;
    }

    public function withProviders(array $providers) {
        foreach($providers as $providerClass) {
            if(!class_exists($providerClass)) {
                throw new Exception("Application::withProviders : Provider class $providerClass does not exist");
            }
            $provider = new $providerClass($this);
            $provider->register();
        }
        return self::$instance;
    }
    
    public function withMiddlewares(array $middlewares) {
        $this->middlewares = $middlewares;
        return self::$instance;
    }

    public static function instance() {
        if(self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}