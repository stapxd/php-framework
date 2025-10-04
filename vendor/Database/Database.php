<?php

namespace Vendor\Database;

abstract class Database {
    protected $connection;

    abstract function connect();
    abstract function isConnected();

    abstract function query(string $query);
    abstract function dropIfExists(string $tableName);
    abstract function isTableExists(string $tableName);

    abstract function getSchema();
}
