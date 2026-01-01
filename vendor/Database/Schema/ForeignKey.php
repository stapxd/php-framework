<?php

namespace Vendor\Database\Schema;

abstract class ForeignKey { 
    protected string $columnName;
    protected string $referencedTable;
    protected string $referencedColumn;
    protected string $onDeleteAction;
    protected string $onUpdateAction;

    protected array $actions = [
        'CASCADE',
        'SET NULL',
        'RESTRICT',
        'NO ACTION',
        'SET DEFAULT'
    ];

    public function __construct(string $columnName) {
        $this->columnName = $columnName;
        $this->referencedTable = '';
        $this->referencedColumn = '';
        $this->onDeleteAction = 'NO ACTION';
        $this->onUpdateAction = 'NO ACTION';
    }

    public function getColumnName(): string {
        return $this->columnName;
    }

    public function getReferencedTable(): string {
        return $this->referencedTable;
    }

    public function getReferencedColumn(): string {
        return $this->referencedColumn;
    }

    public function getOnDeleteAction(): string {
        return $this->onDeleteAction;
    }

    public function getOnUpdateAction(): string {
        return $this->onUpdateAction;
    }

    abstract public function references(string $referencedColumn);
    abstract public function on(string $referencedTable);
    abstract public function onDelete(string $action);
    abstract public function onUpdate(string $action);
}