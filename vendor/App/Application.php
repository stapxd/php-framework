<?php

namespace Vendor\App;

use Exception;
use Vendor\Database\Database;
use Vendor\Database\DatabaseFactory;
use Vendor\Foundation\Router;

class Application {
    private Router $router;
    private Database $database;

    public function __construct() {
        $this->router = new Router();
    }

    public function router(): Router { 
        return $this->router;
    }

    public function handleRequest(string $path) {
        $this->router->execute($path);
    }

    public function withDatabase(string $dbConnection) {
        if(!$dbConnection) throw new Exception('.env file does not have DB_CONNECTION!');

        $this->database = DatabaseFactory::createDatabase($dbConnection);
        return $this;
    }

    public static function configure() {
        return new Application();
    }
}