<?php

use Vendor\Facades\DB;
use Vendor\Facades\Schema;

require __DIR__.'/../autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->safeLoad();

$app = require __DIR__.'/../../app/app.php';

app()->register('schema', DB::getSchema());

if(!DB::doesTableExist('migrations')) {
    Schema::create('migrations', function($table) {
        $table->id();
        $table->string('name');
        $table->int('batch');
    });
}

$result = DB::query("SELECT name FROM migrations");

$executed = array_column($result->fetch_all(MYSQLI_ASSOC), 'name');

$migrationsPath = __DIR__.'/../../migrations';

$files = scandir($migrationsPath);

$files = array_filter($files, fn ($f) =>
    str_ends_with($f, '.php') && preg_match('/^\d+_/', $f)
);

sort($files);

$files = array_values($files);

$row = DB::query('SELECT MAX(batch) as max_batch FROM migrations')->fetch_assoc();

$batch = (int) ($row['max_batch'] ?? 0) + 1;

$ranAny = false;

foreach ($files as $file) {
    if(in_array($file, $executed))
        continue;

    $migration = require $migrationsPath.'/'.$file;
    
    if(!is_object($migration) || !method_exists($migration, 'up')) {
        throw new Exception("Migration {$file} is invalid");
    }
    
    echo "Migrating: {$file}\n";
    
    $migration->up();

    DB::table('migrations')->insert([
        'name' => $file,
        'batch' => $batch
    ]);

    $ranAny = true;
}

if(!$ranAny) {
    echo "Nothing to migrate.\n";
}

