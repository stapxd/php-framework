<?php

namespace Controllers;

use Models\HomeModel;

class HomeController extends Controller {
    public function index() {
        $products = HomeModel::getAll();
        return view('home.php', $products);
    }
}