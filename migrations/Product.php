<?php

namespace Migration;

use Vendor\Facades\Schema;

class Product {
    public function up() {
        Schema::create('products', function($table) {
            $table->id();
            $table->int('quantity', false, false, true);
            $table->int('code', false, true);
            $table->string('title', 100, false);
            $table->text('description');
            $table->double('price', 10, 2, false);
        });
    }

    public function down() {
        Schema::dropIfExists('products');
    }
}