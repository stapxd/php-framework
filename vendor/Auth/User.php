<?php

use Vendor\Facades\Schema;
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
        // Remove foreign key constraint if it exists
        
        Schema::dropIfExists('users');
    }
};