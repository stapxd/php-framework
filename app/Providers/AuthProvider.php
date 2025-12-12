<?php

namespace App\Providers;

use Vendor\Auth\Auth;

class AuthProvider {
    public function register() {
        app()->bind('auth', Auth::class, true);
    }
}
