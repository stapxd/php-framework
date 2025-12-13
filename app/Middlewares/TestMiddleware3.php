<?php

namespace App\Middlewares;

use Vendor\App\Http\Middleware;
use Vendor\Foundation\Request;

class TestMiddleware3 extends Middleware {
    public function handle(Request $request, callable $next) {
        echo '--- TestMiddleware3 Start ---';
        return $next($request);
    }
}