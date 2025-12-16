<?php

namespace Vendor\Foundation;

class Request {
    private static $instance;

    private function __construct(private $url, private $method, private array $getParams,  private array $postParams) { }

    private static function create() {
        $request = new static(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD'],
            $_GET,
            $_POST
        );

        return $request;
    }

    static function capture() {
        if(!self::$instance) self::$instance = self::create();
        return self::$instance;
    }

    public function getPath() {
        $realPath = explode('?', $_SERVER['REQUEST_URI']);
        return $realPath[0];
    }

    public function getMethod() { return $this->method; }

    public function query(string $param, $default = null) {
        if(isset($this->getParams[$param])) return $this->getParams[$param];
        return $default;
    }

    public function input(string $param, $default = null) {
        if(isset($this->postParams[$param])) return $this->postParams[$param];
        return $default;
    }

    public function getAny(string $param, $default = null) {
        if(isset($this->postParams[$param])) return $this->postParams[$param];
        if(isset($this->getParams[$param])) return $this->getParams[$param];
        return $default;
    }
}