<?php

use Vendor\App\Application;

return Application::configure()
    ->withDatabase(env('DB_CONNECTION', ''));