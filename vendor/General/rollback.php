<?php

use Vendor\Facades\DB;
use Migration\Migration;

require __DIR__.'/../autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->safeLoad();

$app = require __DIR__.'/../../app/app.php';

app()->register('schema', DB::getSchema());

$migrationTableExists = DB::doesTableExist('migrations');

if(!$migrationTableExists) {
    $migrationTable = new Migration();
    $migrationTable->up();
    die;
}

$migrationsPath = __DIR__.'/../../migrations';

$files = scandir($migrationsPath);

$result = DB::query('SELECT MAX(batch) as max_batch FROM migrations');
$row = $result->fetch_assoc();

if($result->num_rows <= 0) die;

$batch = $row['max_batch'];

$result = DB::query("SELECT * FROM migrations WHERE batch=$batch");

while ($row = mysqli_fetch_assoc($result)) {
    
    require_once $migrationsPath.'/'.$row['name'];

    $className = 'Migration\\' . preg_replace('/^\d+_/', '', explode('.',$row['name'])[0]);

    if(class_exists($className)) {
        
        $migration = new $className();
        if(method_exists($migration, 'down')) {
            $migration->down();

            DB::query('DELETE FROM migrations WHERE id='.$row['id']);
        }
    }
}

