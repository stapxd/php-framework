<?php

namespace Vendor\Auth;

use Vendor\Facades\DB;

class Auth {
    public function __construct() {}

    public function currentUser() {
        return isset($_COOKIE['currect_user']) ? json_decode($_COOKIE['currect_user'], true) : null;
    }

    public function register(array $data): bool {
        
        if(DB::isConnected()) {
            if(DB::table('users')->where([
                'email' => $data['email']
                ])->num_rows !== 0) {
                return false;
            }

            DB::table('users')->insert([
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            
            return true;
        }

        return false;
    }

    public function login(array $data): bool {
        
        if(DB::isConnected()) {
            $result = DB::table('users')->where([
                'email' => $data['email']
            ]);

            $user = mysqli_fetch_assoc($result);

            if($user && password_verify($data['password'], $user['password'])) {
                setcookie('currect_user', json_encode($user), time() + 60*60*24*7, "/");
                return true;
            }
            else {
                return false;
            }
        }

        echo "Fatal error: Unable to connect to database.";
        return false;
    }

    public function logout(): void {
        setcookie('currect_user', '', time() - 3600, "/");
    }
}