<?php

function dd($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die;
}

function view(string $filename, array $data = []) {
    extract($data);
    require __DIR__.'/../../Views/'.$filename;
}