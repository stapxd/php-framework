<?php

namespace Controllers;

use Migration\Migration;
use Models\HomeModel;
use Vendor\Facades\DB;
use Vendor\Facades\Schema;
use Vendor\Foundation\Request;
use Vendor\Facades\Auth;
use Vendor\General\Session;
use Vendor\General\Validator\Validator;

class HomeController extends Controller {
    public function index(Request $request) {

        HomeModel::getAll();

        $user = Auth::currentUser();

        $data = [
            'user' => $user,
        ];
        return view('home.php', $data);

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

    public function register(){
        return view('register.php');
    }

    public function registerPost(string $email, string $password){
        $result = HomeModel::registerUser($email, $password);

        $errors = [];
        if($result === false){
            $errors[] = "User with email $email already exists.";
            Session::flash('errors', $errors);
            
            redirect('/users/register');
        }
        
        redirect('/users/login');
    }

    public function loginPost(string $email, string $password){
        $result = HomeModel::loginUser($email, $password);

        $errors = [];
        if($result === false){
            $errors[] = "Login error: incorrect email or password.";
            Session::flash('errors', $errors);
        }
        
        redirect('/');
    }

    public function logoutPost(){
        Auth::logout();
        redirect('/');
    }

    public function formIndex() {
        $formData = Session::flash('formData');
        return view('form.php', ['formData' => $formData]);
    }

    public function formSubmit(Request $request) {
        $validated = Validator::validate($request, [
            'username' => 'required|min_length:3|max_length:10',
            'email' => 'unique:users|regexp:/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/',
            'age' => 'min:18|max:100'
        ]);

        if(!$validated) {
            $formData = [
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'age' => $request->input('age')
            ];

            Session::flash('formData', $formData);

            redirect('/form');
        }
        else {
            echo "Form submitted successfully!";
        }
    }
}