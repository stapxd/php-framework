<?php

namespace Vendor\Database;

use Exception;

class DatabaseFactory {
    static private array $dbOptions  = [
        'mysql' => DatabaseMySQL::class
    ];

    static public function createDatabase(string $dbName) {
        $dbName = strtolower($dbName);
        if(isset(self::$dbOptions[$dbName]) && class_exists(self::$dbOptions[$dbName]))
            return new self::$dbOptions[$dbName]();

        throw new Exception("Unknown database: $dbName");
    }
}