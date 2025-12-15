<?php

use Vendor\App\Application;

return Application::instance()
    ->withDatabase(env('DB_CONNECTION', ''))
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthProvider::class,
    ])
    ->withMiddlewares([
        App\Middlewares\TestMiddleware1::class,
    ]);