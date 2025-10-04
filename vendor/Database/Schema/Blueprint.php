<?php

namespace Vendor\Database\Schema;

class Blueprint {
    private string $tableName;
    private array $columns;

    public function __construct(string $tableName) {
        $this->tableName = $tableName;
    }

    public function id($name = 'id') {
        $this->columns[] = "$name INT UNSIGNED PRIMARY KEY AUTO_INCREMENT";
    }

    public function string($name, $length = 255, $canBeNull = true, $isUnique = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';

        $this->columns[] = "$name VARCHAR($length) $null $unique";
    }

    public function text($name, $canBeNull = true) {
        $null = $canBeNull ? '' : 'NOT NULL';

        $this->columns[] = "$name TEXT $null";
    }

    public function int($name, $canBeNull = true, $isUnique = false, $isUnsigned = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';
        $unsigned = $isUnsigned ? 'UNSIGNED' : '';

        $this->columns[] = "$name INT $unsigned $null $unique";
    }

    public function double($name, $M, $D, $canBeNull = true, $isUnique = false, $isUnsigned = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';
        $unsigned = $isUnsigned ? 'UNSIGNED' : '';

        $this->columns[] = "$name DOUBLE($M, $D) $unsigned $null $unique";
    }

    public function toQuery() {
        $columnsSQL = implode(', ', $this->columns);
        return 'CREATE TABLE '.$this->tableName.' ('.($columnsSQL).')';
    }
}