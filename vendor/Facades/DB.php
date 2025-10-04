<?php

namespace Vendor\Facades;

class DB extends Facade {
    public static function query(string $query) {
        $db = static::getInstance();
        return $db->query($query);
    }

    public static function dropIfExists(string $tableName)
    {
        $db = static::getInstance();
        return $db->dropIfExists($tableName);
    }

    public static function isTableExists(string $tableName)
    {
        $db = static::getInstance();
        return $db->isTableExists($tableName);
    }

    public static function isConnected() {
        $db = static::getInstance();
        return $db->isConnected();
    }

    public static function getSchema() {
        $db = static::getInstance();
        $schema = $db->getSchema();
        return $schema;
    }    

    public static function getFacadeAccessor(): string {
        return 'db';
    }

    protected static function getInstance() {
        return app(static::getFacadeAccessor());
    }
}