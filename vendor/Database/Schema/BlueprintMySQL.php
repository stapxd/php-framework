<?php

namespace Vendor\Database\Schema;

class BlueprintMySQL extends Blueprint {

    public function __construct(string $tableName) {
        $this->tableName = $tableName;
    }

    public function wrapName(string $name): string {
        return '`' . str_replace('`', '', $name) . '`';
    }

    public function foreign(string $columnName) {
        $foreignKey = new ForeignKeyMySQL($columnName);
        $this->foreignKeys[] = $foreignKey;
        return $foreignKey;
    }

    public function id($name = 'id') {
        $this->columns[] = "$name INT UNSIGNED PRIMARY KEY AUTO_INCREMENT";
    }

    public function string($name, $length = 255, $canBeNull = true, $isUnique = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';

        $name = $this->wrapName($name);

        $this->columns[] = "$name VARCHAR($length) $null $unique";
    }

    public function text($name, $canBeNull = true) {
        $null = $canBeNull ? '' : 'NOT NULL';

        $name = $this->wrapName($name);
        
        $this->columns[] = "$name TEXT $null";
    }

    public function int($name, $canBeNull = true, $isUnique = false, $isUnsigned = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';
        $unsigned = $isUnsigned ? 'UNSIGNED' : '';

        $name = $this->wrapName($name);
        
        $this->columns[] = "$name INT $unsigned $null $unique";
    }

    public function double($name, $M, $D, $canBeNull = true, $isUnique = false, $isUnsigned = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';
        $unsigned = $isUnsigned ? 'UNSIGNED' : '';

        $name = $this->wrapName($name);
        
        $this->columns[] = "$name DOUBLE($M, $D) $unsigned $null $unique";
    }

    public function date($name, $canBeNull = true, $isUnique = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';

        $name = $this->wrapName($name);
        
        $this->columns[] = "$name DATE $null $unique";
    }

    public function datetime($name, $canBeNull = true, $isUnique = false) {
        $null = $canBeNull ? '' : 'NOT NULL';
        $unique = $isUnique ? 'UNIQUE' : '';

        $name = $this->wrapName($name);
        
        $this->columns[] = "$name DATETIME $null $unique";
    }

    public function timestamps() {
        $this->columns[] = "created_at DATETIME NOT NULL";
        $this->columns[] = "updated_at DATETIME NOT NULL";
    }

    public function toQuery() {
        $columnsSQL = implode(', ', $this->columns);

        $tableName = $this->wrapName($this->tableName);

        $foreignKeysSQL = [];
        foreach($this->foreignKeys as $foreignKey) {
            if(!$foreignKey->getColumnName() || !$foreignKey->getReferencedTable() || !$foreignKey->getReferencedColumn()) {
                throw new \Exception('Foreign key definition is incomplete.');
            }

            $fkName = $this->tableName.'_'.$foreignKey->getColumnName().'_fk';
            $fkColumn = $this->wrapName($foreignKey->getColumnName());
            $fkRefTable = $this->wrapName($foreignKey->getReferencedTable());
            $fkRefColumn = $this->wrapName($foreignKey->getReferencedColumn());

            $fkLineSQL = 'CONSTRAINT '.$fkName.' FOREIGN KEY ('.$fkColumn.') REFERENCES '.$fkRefTable.'('.$fkRefColumn.')';
            
            if($foreignKey->getOnDeleteAction()) {
                $fkLineSQL .= ' ON DELETE '.$foreignKey->getOnDeleteAction();
            }
            if($foreignKey->getOnUpdateAction()) {
                $fkLineSQL .= ' ON UPDATE '.$foreignKey->getOnUpdateAction();
            }
            $foreignKeysSQL[] = $fkLineSQL;
        }

        if(!empty($foreignKeysSQL)) {
            $columnsSQL .= ', '.implode(', ', $foreignKeysSQL);
        }

        $sql = 'CREATE TABLE '.$tableName.' ('.$columnsSQL.')';
        return $sql;
    }
    
    public function toTableQuery() {
        $columnsSQL = implode(', ', $this->columns);
        $tableName = $this->wrapName($this->tableName);
        $sql = 'CREATE TABLE '.$tableName.' ('.$columnsSQL.')';
        return $sql;
    }

    public function toForeignKeysQuery(){
        $queries = [];
        $tableName = $this->wrapName($this->tableName);
        foreach($this->foreignKeys as $foreignKey) {
            if(!$foreignKey->getColumnName() || !$foreignKey->getReferencedTable() || !$foreignKey->getReferencedColumn()) {
                throw new \Exception('Foreign key definition is incomplete.');
            }

            $fkName = $this->tableName.'_'.$foreignKey->getColumnName().'_fk';
            $fkColumn = $this->wrapName($foreignKey->getColumnName());
            $fkRefTable = $this->wrapName($foreignKey->getReferencedTable());
            $fkRefColumn = $this->wrapName($foreignKey->getReferencedColumn());

            $fkLineSQL = 'ALTER TABLE '.$tableName.' ADD CONSTRAINT '.$fkName.' FOREIGN KEY ('.$fkColumn.') REFERENCES '.$fkRefTable.'('.$fkRefColumn.')';
            
            if($foreignKey->getOnDeleteAction()) {
                $fkLineSQL .= ' ON DELETE '.$foreignKey->getOnDeleteAction();
            }
            if($foreignKey->getOnUpdateAction()) {
                $fkLineSQL .= ' ON UPDATE '.$foreignKey->getOnUpdateAction();
            }
            $queries[] = $fkLineSQL . ';';
        }
        return $queries;
    }
}