<?php

namespace Vendor\Database\Schema;

abstract class Blueprint {
    protected string $tableName;
    protected array $columns;
    protected array $foreignKeys = [];

    public function __construct(string $tableName) {
        $this->tableName = $tableName;
        $this->columns = [];
        $this->foreignKeys = [];
    }

    abstract public function wrapName(string $name): string;

    abstract public function foreign(string $columnName);

    abstract public function id($name = 'id');

    abstract public function string($name, $length = 255, $canBeNull = true, $isUnique = false);

    abstract public function text($name, $canBeNull = true);

    abstract public function int($name, $canBeNull = true, $isUnique = false, $isUnsigned = false);

    abstract public function double($name, $M, $D, $canBeNull = true, $isUnique = false, $isUnsigned = false);

    abstract public function date($name, $canBeNull = true, $isUnique = false);
    
    abstract public function datetime($name, $canBeNull = true, $isUnique = false);

    abstract public function timestamps();

    abstract public function toQuery();

    abstract public function toTableQuery();

    abstract public function toForeignKeysQuery();
}