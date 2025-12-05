<?php

namespace Vendor\Database\Builders;

class Builder {
    public function __construct(protected $connection, protected string $tableName) 
    {}

    public function insert(array $data) {}
    public function where(string $columnName, string $data) {}
}