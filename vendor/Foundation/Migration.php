<?php

namespace Vendor\Foundation;

interface MigrationBase {
    public function up();
    public function down();
}