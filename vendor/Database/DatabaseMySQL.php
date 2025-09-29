<?php

namespace Vendor\Database;

class DatabaseMySQL extends Database {

    public function __construct() {
        $this->connect();
    }

    public function connect()
    {
        
    }

    public function query(string $sql) : array
    {
        echo "This is mysql query! $sql";
        return [];
    }
}