<?php

namespace App\Middlewares;

use Vendor\App\Http\Middleware;
use Vendor\Foundation\Request;

class TestMiddleware1 extends Middleware {
    public function handle(Request $request, callable $next) {
        echo '--- TestMiddleware1 Start ---';
        return $next($request);
    }
}