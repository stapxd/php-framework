<?php

namespace App\Providers;

use Vendor\Foundation\Request;

class AppServiceProvider {
    public function register() {
        app()->bind(Request::class, fn() => Request::capture(), true);
    }
}

