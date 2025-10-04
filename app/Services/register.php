<?php 

use Vendor\App\Application;
use Vendor\Facades\DB;

/** @var Application $app */

app()->register('schema', DB::getSchema());