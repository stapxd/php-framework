<?php

namespace Vendor\App\Http;

use Vendor\Foundation\Request;

class Middleware {
    public function handle(Request $request, callable $next) {
        return $next($request);
    }
}