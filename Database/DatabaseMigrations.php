<?php


namespace app\Database;


abstract class DatabaseMigrations extends \app\core\Migrations
{
    public final function __construct($env, $argv, $argc)
    {
        parent::__construct($env, $argv, $argc);
    }

    public abstract function up();

    public abstract function down();
}
