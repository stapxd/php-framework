<?php

namespace Vendor\Database;

use Exception;
use mysqli;

use Vendor\Database\Builders\BuilderMySQL;
use Vendor\Database\Schema\SchemaMySQL;

class DatabaseMySQL extends Database {
    protected $connection = null;

    public function __construct() {
        $this->connect();
    }

    public function connect()
    {
        $this->connection = new mysqli(env('DB_HOST'), 
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            env('DB_PORT'));

        return $this->connection;
    }

    public function isConnected() {
        return $this->connection;
    }

    public function getSchema() {
        return new SchemaMySQL();
    }

    public function query(string $sql)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        return $this->connection->query($sql);
    }

    public function dropIfExists(string $tableName)
    {
        $result = $this->connection->query("DROP TABLE IF EXISTS $tableName");
        return $result;
    }

    public function doesTableExist(string $tableName)
    {
        return mysqli_fetch_column($this->connection->query("SHOW TABLES LIKE '$tableName'")) != '' ? true : false;
    }

    public function table(string $tableName) : BuilderMySQL {
        return new BuilderMySQL($this->connection, $tableName);
    }
}