<?php

namespace Migration;

use Vendor\Facades\Schema;
use Vendor\Foundation\MigrationBase;

class migration implements MigrationBase {
    public function up() {
        Schema::create('migrations', function($table) {
            $table->id();
            $table->string('name');
            $table->int('batch');
        });
    }

    public function down() {
        Schema::dropIfExists('migrations');
    }
}