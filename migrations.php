<?php

require_once 'vendor/autoload.php';

$migrationFiles = scandir('Database/Migrations');

foreach($migrationFiles as $migrationFile) {
    if(($pos =strpos($migrationFile,'.php') )>0){
        $className = substr($migrationFile,0,$pos);
        require_once 'Database/Migrations/'.$migrationFile;
        $callback[0] = new $className('development',$argv,$argc);
        $callback[1] = 'execute';
        call_user_func($callback);
        $callback[0] = new $className('testing',$argv,$argc);
        call_user_func($callback);
    }
}
