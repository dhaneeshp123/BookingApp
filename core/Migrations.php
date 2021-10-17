<?php

namespace app\core;

abstract class Migrations
{
    private Connection $connection;

    private array $alreadyExecutedMigrations = [];

    private string $method = 'up';

    private array $sql = [];

    private string $env;

    private function checkAndCreateMigrationTable()
    {
        $result = $this->connection->select('SHOW TABLES LIKE "migrations"');
        if (count($result) === 0) {
            $this->connection->execute("CREATE TABLE migrations (`path` varchar(255) NOT NULL, `method` varchar(10) NOT NULL ,`create_date` datetime NOT NULL DEFAULT current_timestamp())");
        }
        $migrations = $this->connection->select('SELECT `path` FROM migrations WHERE method ="' . $this->method . '"');
        if (is_array($migrations)) {
            foreach ($migrations as $migration) {
                $this->alreadyExecutedMigrations[$this->method][] = $migration['path'];
            }
        }
    }

    /**
     * @return array
     */
    protected function getAllowedMethods():array
    {
        return [];
    }

    /**
     * Migrations constructor.
     * @param $env
     * @param $argv
     * @param $argc
     */
    public function __construct($env, $argv, $argc)
    {
        if ($argc < 2 || !in_array($argv[1], $this->getAllowedMethods())) {
            echo "please specify any of this option specified " . join('|',$this->getAllowedMethods());
            exit;
        }
        $this->method = $argv[1];
        $this->alreadyExecutedMigrations[$this->method] = [];
        $this->env = $env;
        putenv("APP_ENV=" . $env);
        $config = [];
        include "config/config.php";
        Application::$config = $config;
        $this->connection = new Connection();
        $this->checkAndCreateMigrationTable();
    }

    /**
     * @param string $sql
     */
    final protected function addSql(string $sql)
    {
        $this->sql[] = $sql;
    }

    final public function execute()
    {
        call_user_func([$this, $this->method]);
        $className = get_class($this);
        if (!in_array($className, $this->alreadyExecutedMigrations[$this->method])) {
            echo 'Migrations ' . $this->method . ' applied to ' . $className . " for $this->env\n";
            $this->connection->execute('DELETE FROM migrations WHERE path="' . $className . '"');
            $this->connection->execute('INSERT INTO migrations (path,method) values("' . $className . '","' . $this->method . '")');
            foreach ($this->sql as $sql) {
                $this->connection->execute($sql);
            }
        }

    }
}
