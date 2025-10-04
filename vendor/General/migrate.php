<?php

use Vendor\Facades\DB;
use Migration\Migration;

require __DIR__.'/../autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->safeLoad();

$app = require __DIR__.'/../../app/app.php';

app()->register('schema', DB::getSchema());

$migrationTableExists = DB::isTableExists('migrations');

if(!$migrationTableExists) {
    $migrationTable = new Migration();
    $migrationTable->up();
}

$result = DB::query("SELECT name FROM migrations");

$executed = array_column($result->fetch_all(MYSQLI_ASSOC), 'name');

$migrationsPath = __DIR__.'/../../migrations';

$files = scandir($migrationsPath);

$row = DB::query('SELECT MAX(batch) as max_batch FROM migrations')->fetch_assoc();

$batch = $row['max_batch'] ?? 0;
$batch++;

foreach ($files as $file) {
    if($file == 'Migration.php') continue;
    if(in_array($file, $executed)) continue;

    if(pathinfo($file, PATHINFO_EXTENSION) === 'php'){
        require_once $migrationsPath.'/'.$file;
        
        $className = 'Migration\\' . pathinfo($file, PATHINFO_FILENAME);

        if(class_exists($className)) {
            $migration = new $className();
            if(method_exists($migration, 'up')){
                $migration->up();

                DB::query("INSERT INTO migrations (name, batch) VALUES('$file', $batch)");
            }
        }
    }

}

