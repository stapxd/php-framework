<?php

namespace Vendor\Database;

abstract class Database {
    abstract function connect();
    abstract function query(string $query);
}