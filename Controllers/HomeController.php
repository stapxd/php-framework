<?php

namespace Controllers;

use Migration\Migration;
use Models\HomeModel;
use Vendor\Facades\DB;
use Vendor\Facades\Schema;
use Vendor\Foundation\Request;
use Vendor\Facades\Auth;

class HomeController extends Controller {
    public function index(Request $request) {

        if(DB::isConnected()) {
            //echo 'Connected';
        
            // Schema::create('products', function($table) {
            //     $table->id();
            //     $table->int('quantity', false, false, true);
            //     $table->int('code', false, true);
            //     $table->string('title', 100, false);
            //     $table->text('description');
            //     $table->double('price', 10, 2, false);
            // });

            //Schema::dropIfExists('migrations');
//Schema::dropIfExists('products');
            //DB::query('DROP TABLE IF EXISTS users');

            // DB::query('CREATE TABLE IF NOT EXISTS Users (
            //     ID INT PRIMARY KEY AUTO_INCREMENT,

            // )');

            // CREATE TABLE $table_name ()



            /*
            $query = 'CREATE TABLE $table_name (';
            id() {
                $this->query += 'id INT PRIMARY KEY AUTO_INCREMENT';
            }

            string($name) {
                $this->query += ', $name VARCHAR(255)';
            }


            $rows = DB::query(SELECT * FROM users); // <- fetch_assoc()
            DB::drop('table_name');

            //in Model: $fillable = ['col1', 'col2'];
            //как сделать миграции типа ларавеля
            DB::createTable('table_name', [
                'id' -> 'INT PRIMARY KEY'
            ]);
            */

            return view('home.php');
        }
        else {
            echo 'Error connection';
        }
    }

    public function create(int $id, string $title) {
        HomeModel::create($id, $title);
        echo "$id $title";
        redirect('/');
    }

    public function login(){
        return view('login.php');
    }

    public function loginPost(Request $request, string $email, string $password){
        Auth::login([
            'email' => $email,
            'password' => $password
        ]);

        dd(Auth::currentUser());
    }
}