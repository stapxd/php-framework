<?php

use Vendor\Facades\Schema;
use Vendor\Facades\DB;
use Migration\Migration;

return new class implements Migration {
    public function up() {
        Schema::create('users', function($table) {
            $table->id();
            $table->string('email', 100, false, true);
            $table->string('password', 255, false);
        });
    }

    public function down() {
        if(DB::doesTableExist('products')) {
            DB::query('ALTER TABLE products DROP FOREIGN KEY products_created_by_fk');
        }

        Schema::dropIfExists('users');
    }
};