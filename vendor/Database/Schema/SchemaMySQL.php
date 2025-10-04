<?php

namespace Vendor\Database\Schema;

use Vendor\Facades\DB;

class SchemaMySQL extends SchemaBuilder {
    public function create(string $tableName, callable $callback) {
        $blueprint = new Blueprint($tableName);
    
        $callback($blueprint);
        $sql = $blueprint->toQuery();
        
        DB::query($sql);
    }

    public function dropIfExists($tableName) {
        DB::dropIfExists($tableName);
    }
}