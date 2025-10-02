<?php

namespace Models;

use Vendor\Facades\DB;

class HomeModel extends Model {
    private static array $products = [];

    public static function getAll() {
        //var_dump(Application::instance());

        DB::query("SELECT * ...");

        return self::$products;
    }

    public static function create(int $id, string $title) {
        self::$products[] = [
            'id' => $id,
            'title' => $title
        ];
    }
}