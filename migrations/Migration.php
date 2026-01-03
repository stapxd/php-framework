<?php

namespace Migration;

interface Migration {
    public function up();
    public function down();
}