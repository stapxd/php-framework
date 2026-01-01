<?php

namespace Models;

use Vendor\Facades\DB;
use Vendor\Facades\Auth;

class HomeModel extends Model {

// temp
    private static array $products = [];

    public static function getAll() {
        DB::table('products')->select()->innerJoin('users', 'products.created_by', 'users.id')->where(['title' => 'Aaa'])->find();
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