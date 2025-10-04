<?php

namespace Vendor\Facades;

class Schema extends Facade {
    public static function create(string $tableName, callable $callback) {
        $schema = static::getInstance();
        $schema->create($tableName, $callback);
    }

    public static function dropIfExists(string $tableName) {
        $schema = static::getInstance();
        $schema->dropIfExists($tableName);
    }

    public static function getFacadeAccessor(): string {
        return 'schema';
    }

    protected static function getInstance() {
        return app(static::getFacadeAccessor());
    }
}