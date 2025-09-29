<?php

use Vendor\App\Application;

return Application::instance()
    ->withDatabase(env('DB_CONNECTION', ''));