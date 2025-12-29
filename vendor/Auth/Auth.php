<?php

namespace Vendor\Auth;

use Vendor\Facades\DB;
use Vendor\General\Cookie;

class Auth {
    protected $fields = [
        'login' => 'email', 
        'password' => 'password'
    ];

    public function __construct() {}

    public function currentUser() {
        return Cookie::exists('currect_user') ? json_decode(Cookie::get('currect_user'), true) : null;
    }

    public function register(array $data): bool {
        
        if(DB::isConnected()) {
            if(DB::table('users')->where([
                $this->fields['login'] => $data[$this->fields['login']]
                ])->num_rows !== 0) {
                return false;
            }

            DB::table('users')->insert([
                $this->fields['login'] => $data[$this->fields['login']],
                $this->fields['password'] => password_hash($data[$this->fields['password']], PASSWORD_BCRYPT)
            ]);
            
            return true;
        }

        echo "Fatal error: Unable to connect to database.";
        return false;
    }

    public function login(array $data): bool {
        
        if(DB::isConnected()) {
            $result = DB::table('users')->where([
                $this->fields['login'] => $data[$this->fields['login']]
            ]);

            $user = mysqli_fetch_assoc($result);

            if($user && password_verify($data[$this->fields['password']], $user[$this->fields['password']])) {
                unset($user[$this->fields['password']]);
                Cookie::set('currect_user', json_encode($user), time() + 60*60*24*7);
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
        Cookie::delete('currect_user');
    }
}