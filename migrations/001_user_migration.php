<?php

namespace Migration;

use Vendor\Facades\Schema;
use Vendor\Facades\DB;

class user_migration {
    public function up() {
        Schema::create('users', function($table) {
            $table->id();
            $table->string('email', 100, false, true);
            $table->string('password', 255, false);
        });
    }

    public function down() {
        DB::query('ALTER TABLE products DROP FOREIGN KEY products_created_by_fk');

        Schema::dropIfExists('users');
    }
}