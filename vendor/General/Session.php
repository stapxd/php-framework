<?php

namespace Vendor\General;

class Session {
    public static function start() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function destroy() {
        self::start();
        session_destroy();
    }

    public static function unset(string $key) {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function flash(string $key, $value = null) {
        self::start();
        if($value !== null) {
            $_SESSION['flash'][$key] = $value;
        } else {
            if(isset($_SESSION['flash'][$key])) {
                $flashValue = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]);
                return $flashValue;
            }
            return null;
        }
    }

    public static function flush() {
        self::start();
        unset($_SESSION);
    }

    public static function flushFlash() {
        self::start();
        unset($_SESSION['flash']);
    }
}