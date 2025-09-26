<?php

/** @var Application $app */

$app->router()->get('/', function(){
    return view('home.php');
});