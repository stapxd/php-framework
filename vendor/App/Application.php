<?php

namespace Vendor\App;

use Vendor\Foundation\Router;

class Application {
    private Router $router;

    public function __construct() {
        $this->router = new Router();
    }

    public function router(): Router { 
        return $this->router;
    }

    public function handleRequest(string $path) {
        $this->router->execute($path);
    }
}