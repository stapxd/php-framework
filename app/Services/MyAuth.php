<?php 

namespace App\Services;

use Vendor\Auth\Auth;

class MyAuth extends Auth {
    public function __construct() {
        parent::__construct();
        $this->fields = [
            'login' => 'username',
            'password' => 'password'
        ];
    }
}