<?php

namespace Models;

use Vendor\Facades\DB;
use Vendor\Facades\Auth;

class HomeModel extends Model {

// temp
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
// ---

    public static function registerUser(string $email, string $password) {
        return Auth::register([
            'email' => $email,
            'password' => $password
        ]);
    }

    public static function loginUser(string $email, string $password) {
        return Auth::login([
            'email' => $email,
            'password' => $password
        ]);
    }
}