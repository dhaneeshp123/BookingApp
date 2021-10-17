<?php

namespace app\Database;

abstract class DatabaseMigrations extends \app\core\Migrations
{
    /**
     * DatabaseMigrations constructor.
     * @param $env
     * @param $argv
     * @param $argc
     */
    public final function __construct($env, $argv, $argc)
    {
        parent::__construct($env, $argv, $argc);
    }

    protected function getAllowedMethods(): array
    {
        return ['up','down'];
    }

    public abstract function up();

    public abstract function down();

}
