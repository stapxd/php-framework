<?php

namespace App\Middlewares;

use Vendor\App\Http\Middleware;
use Vendor\Foundation\Request;

class TestMiddleware2 extends Middleware {
    public function handle(Request $request, callable $next) {
        echo '--- TestMiddleware2 Start ---';
        return $next($request);
    }
}