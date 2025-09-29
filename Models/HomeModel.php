<?php

namespace Models;

use Vendor\Facades\DB;

class HomeModel extends Model {
    public static function getAll() {
        //var_dump(Application::instance());

        DB::query("SELECT * ...");


        return ['products' => [
                 ['id' => 1,
                    'title' => 'Product1'
        ],
        [
                'id' => 2,
                'title' => 'Product2'
            ]
                ],
        ];
    }
}