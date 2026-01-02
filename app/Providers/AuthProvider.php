<?php

namespace App\Providers;

use Vendor\Auth\Auth;

class AuthProvider implements ServiceProvider {
    public function register() {
        app()->bind('auth', Auth::class, true);
    }
}
