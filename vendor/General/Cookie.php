<?php

namespace Vendor\General;

class Cookie {
    public static function set($name, $value, $expire = 0, $path = "/") {
        setcookie($name, $value, $expire, $path);
    }

    public static function get($name) {
        return $_COOKIE[$name] ?? null;
    }

    public static function delete($name, $path = "/") {
        setcookie($name, '', time() - 3600, $path);
    }

    public static function exists($name) {
        return isset($_COOKIE[$name]);
    }

    public static function all() {
        return $_COOKIE;
    }

    public static function clear($path = "/") {
        foreach ($_COOKIE as $name => $value) {
            setcookie($name, '', time() - 3600, $path);
        }
    }
}