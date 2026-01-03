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
    die;
}

$migrationsPath = __DIR__.'/../../migrations';

$files = scandir($migrationsPath);

$files = array_filter($files, fn ($f) =>
    str_ends_with($f, '.php') && preg_match('/^\d+_/', $f)
);

sort($files);

$files = array_values($files);

$result = DB::query('SELECT MAX(batch) as max_batch FROM migrations');
$row = $result->fetch_assoc();

if($result->num_rows <= 0) die;

$batch = (int) ($row['max_batch'] ?? 0);

if($batch === 0) {
    echo "Nothing to rollback.\n";
    die;
}

$result = DB::query("SELECT * FROM migrations WHERE batch=$batch");

$ranAny = false;

while ($row = mysqli_fetch_assoc($result)) {
    
    $migration = require $migrationsPath.'/'.$row['name'];

    if(!is_object($migration) || !method_exists($migration, 'up')) {
        throw new Exception("Migration {$file} is invalid");
    }

    echo "Rolling back: {$row['name']}\n";

    $migration->down();

    DB::table('migrations')->delete(['id' => $row['id']]);

    $ranAny = true;
}

if(!$ranAny) {
    echo "Nothing to rollback.\n";
}

