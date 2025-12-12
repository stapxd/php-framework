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
        $sql = "SELECT * FROM $this->tableName WHERE ";

        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = $column . ' = ' . $this->escapeValue($value);
        }
        
        $sql .= implode(' AND ', $whereClauses);

        return DB::query($sql);
    }

    public function update(array $data, array $conditions) {
        $sql = "UPDATE $this->tableName SET ";

        $setClauses = [];
        foreach ($data as $column => $value) {
            $setClauses[] = $column . ' = ' . $this->escapeValue($value);
        }

        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = $column . ' = ' . $this->escapeValue($value);
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
            $whereClauses[] = $column . ' = ' . $this->escapeValue($value);
        }

        $sql .= implode(' AND ', $whereClauses);
        
        return DB::query($sql);
    }
}