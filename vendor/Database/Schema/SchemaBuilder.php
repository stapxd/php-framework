<?php

namespace Vendor\Database\Schema;

abstract class SchemaBuilder {
    public abstract function create(string $tableName, callable $callback);
    public abstract function dropIfExists($tableName);
}