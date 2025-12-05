<?php

namespace Vendor\Database;

use Vendor\Database\Builders\Builder;

abstract class Database {
    protected $connection;

    abstract function connect();
    abstract function isConnected();

    abstract function query(string $query);
    abstract function dropIfExists(string $tableName);
    abstract function isTableExists(string $tableName);

    abstract function getSchema();
    abstract function table(string $tableName) : Builders\Builder;
}
