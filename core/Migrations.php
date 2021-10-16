<?php

namespace app\core;


abstract class Migrations
{
    private Connection $connection;

    private array $alreadyExecutedMigrations = ['up' => [],'down' => []];

    private string $method = 'up';

    private array $sql = [];

    private function checkAndCreateMigrationTable()
    {
        $result = $this->connection->select('SHOW TABLES LIKE "migrations"');
        if(count($result) === 0){
            $this->connection->execute("CREATE TABLE migrations (`path` varchar(255) NOT NULL, `method` varchar(10) NOT NULL ,`create_date` datetime NOT NULL DEFAULT current_timestamp())");
        }
        $migrations = $this->connection->select('SELECT `path` FROM migrations WHERE method ="'.$this->method.'"');
        if(is_array($migrations)){
            foreach($migrations as $migration)
            {
                $this->alreadyExecutedMigrations[$this->method][] = $migration['path'];
            }
        }
    }

    public function __construct($env, $argv, $argc)
    {
        if ($argc < 2 || !in_array($argv[1], ['up', 'down'])) {
            echo "please specify the option up/down";
        }
        $this->method = $argv[1];
        putenv("APP_ENV=".$env);
        $config = [];
        include "config/config.php";
        Application::$config = $config;
        $this->connection = new Connection();
        $this->checkAndCreateMigrationTable();
    }


    final protected function  addSql(string $sql)
    {
        $this->sql[] = $sql;
    }

    final public function execute()
    {
        call_user_func([$this,$this->method]);
        $className = get_class($this);
        if(!in_array($className,$this->alreadyExecutedMigrations[$this->method])) {
            echo 'Migrations '.$this->method.' applied to '.$className."\n";
            $this->connection->execute('DELETE FROM migrations WHERE path="'.$className.'"');
            $this->connection->execute('INSERT INTO migrations (path,method) values("'.$className.'","'.$this->method.'")');
            foreach($this->sql as $sql) {
                $this->connection->execute($sql);
            }
        }

    }
}
