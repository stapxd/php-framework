<?php

namespace Vendor\Database\Schema;

class ForeignKeyMySQL extends ForeignKey {

    public function references(string $referencedColumn) {
        $this->referencedColumn = $referencedColumn;
        return $this;
    }

    public function on(string $referencedTable) {
        $this->referencedTable = $referencedTable;
        return $this;
    }

    public function onDelete(string $action) {
        if(!in_array($action, $this->actions)) {
            throw new \InvalidArgumentException("Invalid action '$action' for ON DELETE clause.");
        }
        $this->onDeleteAction = $action;
        return $this;
    }

    public function onUpdate(string $action) {
        if(!in_array($action, $this->actions)) {
            throw new \InvalidArgumentException("Invalid action '$action' for ON UPDATE clause.");
        }
        $this->onUpdateAction = $action;
        return $this;
    }
}