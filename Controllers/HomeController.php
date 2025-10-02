<?php

namespace Controllers;

use Models\HomeModel;
use Vendor\Foundation\Request;

class HomeController extends Controller {
    public function index(Request $request) {
        $products = HomeModel::getAll();
        $data = [
            'products' => $products,
        ];
        return view('home.php', $data);
    }

    public function create(int $id, string $title) {
        HomeModel::create($id, $title);
        echo "$id $title";
        redirect('/');
    }
}