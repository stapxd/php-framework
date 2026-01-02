<?php

namespace Vendor\Database\Builders;

use Vendor\Facades\DB;

class BuilderMySQL extends Builder {
    protected function escapeValue($value) {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        return "'" . mysqli_real_escape_string($this->connection, $value) . "'";
    }

    public function wrapName(string $name): string {
        if($name === '*')
            return $name;
        $names = explode('.', $name);
        return implode('.', array_map(fn($innerName) => '`' . str_replace('`', '', $innerName) . '`', $names));
    }

    public function select(array $columns = ['*']) {
        $this->selectColumns = $columns;
        $columnsList = implode(', ', array_map(fn($col) => $this->wrapName($col), $columns));
        $this->sql = "SELECT $columnsList FROM " . $this->wrapName($this->tableName) . " ";
        return $this;
    }

    public function find() {
        echo $this->sql;
        return DB::query($this->sql);
    }

    public function insert(array $data) {
        $fields = implode(", ", array_keys($data));

        $escapedValues = array_map(
            fn($value) => is_string($value) ? $this->escapeValue($value) : $value, 
            $data
        );

        $values = implode(", ", $escapedValues);

        $sql = "INSERT INTO {$this->tableName} ($fields) VALUES ($values)";

        return DB::query($sql);
    }

    public function where(array $conditions) {
        $tableName = $this->wrapName($this->tableName);
        $this->sql .= " WHERE ";

        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = $this->wrapName($column) . ' = ' . $this->escapeValue($value);
        }
        
        $this->sql .= implode(' AND ', $whereClauses);

        return $this;
    }
    
    public function innerJoin(string $table, string $firstColumn, string $secondColumn) {
        $table = $this->wrapName($table);
        $firstColumn = $this->wrapName($firstColumn);
        $secondColumn = $this->wrapName($secondColumn);

        $this->sql .= ' INNER JOIN '.$this->wrapName($table).' ON '.$firstColumn.' = '.$secondColumn.' ';
        return $this;
    }

    public function limit(int $limit) {
        $this->sql .= " LIMIT " . intval($limit) . " ";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC') {
        $this->sql .= " ORDER BY " . $this->wrapName($column) . " " . strtoupper($direction) . " ";
        return $this;
    }

    public function update(array $data, array $conditions) {
        $sql = "UPDATE $this->tableName SET ";

        $setClauses = [];
        foreach ($data as $column => $value) {
            $setClauses[] = $this->wrapName($column) . ' = ' . $this->escapeValue($value);
        }

        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = $this->wrapName($column) . ' = ' . $this->escapeValue($value);
        }
        
        $sql .= implode(', ', $setClauses);

        $sql .= " WHERE ";
        $sql .= implode(' AND ', $whereClauses);

        return DB::query($sql);
    }

    public function delete(array $conditions) {
        $sql = "DELETE FROM $this->tableName WHERE ";

        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = $this->wrapName($column) . ' = ' . $this->escapeValue($value);
        }

        $sql .= implode(' AND ', $whereClauses);
        
        return DB::query($sql);
    }
}