<?php

namespace Vendor\App;

use Exception;

use Vendor\Database\DatabaseFactory;
use Vendor\Facades\DB;
use Vendor\Facades\Router as RouterFacade;
use Vendor\Foundation\Router;

class Application {
    private static ?Application $instance = null;

    private array $services;
    
    private function __construct() {
        $this->register(RouterFacade::getFacadeAccessor(), new Router());
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

    public function handleRequest(string $path) {
        RouterFacade::execute($path);
    }

    public function withDatabase(string $dbConnection) {
        if(!$dbConnection) throw new Exception('.env file does not have DB_CONNECTION!');

        $this->register(DB::getFacadeAccessor(), DatabaseFactory::createDatabase($dbConnection));
        return self::$instance;
    }

    public static function instance() {
        if(self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}