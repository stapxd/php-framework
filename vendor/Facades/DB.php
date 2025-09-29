<?php

namespace Vendor\Facades;

use Vendor\App\Application;

class DB extends Facade {

    static function query(string $query) {
        $db = Application::instance()->resolve(self::getFacadeAccessor());
        
        return $db->query($query);
    }

    static function getFacadeAccessor(): string{
        return 'db';
    }
}