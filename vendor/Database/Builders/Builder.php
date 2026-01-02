<?php

namespace Vendor\Database\Builders;

class Builder {
    protected array $selectColumns = [];
    protected string $sql = '';

    public function __construct(protected $connection, protected string $tableName) 
    {}

    public function wrapName(string $name): string {}

    public function insert(array $data) {}
    public function update(array $data, array $conditions) {}
    public function delete(array $conditions) {}
    
    public function find() {}
    public function select(array $columns = ['*']) {}
    public function where(array $conditions) {}
    public function innerJoin(string $table, string $firstColumn, string $secondColumn) {}
    public function limit(int $limit) {}
    public function orderBy(string $column, string $direction = 'ASC') {}
}