<?php

namespace Vendor\Auth;

use Vendor\Facades\DB;

class Auth {
    protected $currentUser = null;
    protected $fields = ['email', 'password'];

    public function __construct() {}

    public function currentUser() {
        return $this->currentUser;
    }

    public function register(array $data): bool {
        
        if(DB::isConnected()) {
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
            $result = DB::table('users')->where('email', $data['email']);
            $user = mysqli_fetch_assoc($result);

            if($user && password_verify($data['password'], $user['password'])) {
                $this->currentUser = $user;
                return true;
            }
            else {
                return false;
            }
        }

        echo "Fatal error: Unable to connect to database.";
        return false;
    }
}