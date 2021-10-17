<?php

namespace app\core;

use PDO;
use PDOStatement;

class Connection
{

    private PDO $dbConnection;

    public const RETURN_TYPE_OBJECT = PDO::FETCH_OBJ;

    public const RETURN_TYPE_ASSOC = PDO::FETCH_ASSOC;

    public function __construct()
    {
        try{
            $config = Application::$config;
            $serverName= $config['database_server'];
            $databaseName = $config['database_name'];
            $this->dbConnection = new PDO("mysql:host=$serverName;dbname=$databaseName",$config['database_username'],$config['database_password']);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (\Exception $exception){
            die( 'DB Connection failed');
        }
    }

    /**
     * @param string $sql
     * @return false|PDOStatement
     */
    public function prepare(string $sql)
    {
        return $this->dbConnection->prepare($sql);
    }

    /**
     * @param string $sql
     * @return bool
     */
    public function execute(string $sql): bool
    {
        return ($this->prepare($sql))->execute();
    }

    /**
     * @param string $sql
     * @param int $mode
     * @return array
     */
    public function select(string $sql,int $mode = self::RETURN_TYPE_ASSOC)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($mode);
    }

    public function beginTransaction()
    {
        $this->dbConnection->beginTransaction();
    }

    public function commit()
    {
        $this->dbConnection->commit();
    }

    public function rollBack()
    {
        $this->dbConnection->rollBack();
    }

}
