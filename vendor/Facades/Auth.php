<?php

namespace Vendor\Facades;

class Auth extends Facade {
    public static function currentUser() {
        $auth = static::getInstance();
        return $auth->currentUser();
    }

    public static function register(array $data): bool {
        $auth = static::getInstance();
        return $auth->register($data);
    }

    public static function login(array $data): bool {
        $auth = static::getInstance();
        return $auth->login($data);
    }

    public static function logout(): void {
        $auth = static::getInstance();
        $auth->logout();
    }

    static function getFacadeAccessor(): string {
        return 'auth';
    }

    protected static function getInstance() {
        return app(static::getFacadeAccessor());
    }
}