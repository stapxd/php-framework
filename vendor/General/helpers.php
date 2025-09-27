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

function env($key, $default = null) {
    
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;

    if($value == null)
        return $default;

    return match ($value) {
        'true' => true,
        'false' => false,
        'null'  => null,
        default  => $value
    };
}