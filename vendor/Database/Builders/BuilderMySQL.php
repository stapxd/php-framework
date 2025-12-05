<?php

namespace Vendor\Database\Builders;

use Vendor\Facades\DB;

class BuilderMySQL extends Builder {
    public function insert(array $data) {
        $fields = implode(", ", array_keys($data));

        $escapedValues = array_map(
            fn($v) => is_string($v) ? "'" . mysqli_real_escape_string($this->connection, $v) . "'" : $v, 
            $data
        );

        $values = implode(", ", $escapedValues);

        $sql = "INSERT INTO {$this->tableName} ($fields) VALUES ($values)";
        DB::query($sql);
    }

    public function where(string $columnName, string $data) {
        $sql = "SELECT * FROM {$this->tableName} WHERE $columnName = '$data'";
        return DB::query($sql);
    }
}