<?php

namespace Migration;

use Vendor\Facades\Schema;

class UserMigration {
    public function up() {
        Schema::create('users', function($table) {
            $table->id();
            $table->string('email', 100, false, true);
            $table->string('password', 255, false);
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
}