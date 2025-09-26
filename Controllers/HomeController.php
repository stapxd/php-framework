<?php

namespace Controllers;

class HomeController {
    public function index(){
        //$data = HomeModel::getAllData();
        $data = "some name";
        return view('home.php', ['data' => $data]);
    }
}