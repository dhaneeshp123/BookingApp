<?php


namespace app\core;


use PDO;

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
            echo $exception->getMessage();
            die( 'DB Connection failed');
        }
    }

    public function execute(string $sql): bool
    {
        $stmt = $this->dbConnection->prepare($sql);
        return $stmt->execute();
    }

    public function select(string $sql,int $mode = self::RETURN_TYPE_ASSOC)
    {
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($mode);
    }

    public function prepare(string $sql)
    {
        $this->dbConnection->prepare($sql);
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

    public function getLastInsertId()
    {
        $this->dbConnection->lastInsertId('id');
    }

    public function __destruct()
    {
       // $this->dbConnection = null;
    }
}
