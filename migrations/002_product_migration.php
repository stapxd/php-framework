<?php

namespace Migration;

use Vendor\Facades\Schema;

class product_migration {
    public function up() {
        Schema::create('products', function($table) {
            $table->id();
            $table->int('quantity', false, false, true);
            $table->int('code', false, true);
            $table->string('title', 100, false);
            $table->text('description');
            $table->double('price', 10, 2, false);
            $table->int('created_by', false, false, true);

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
        });
    }

    public function down() {
        Schema::dropIfExists('products');
    }
}