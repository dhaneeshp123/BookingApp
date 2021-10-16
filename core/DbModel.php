<?php

namespace app\core;

use PhpParser\Node\Expr\Cast\Object_;
use Ramsey\Uuid\Uuid;

abstract class DbModel
{
    /** @var string */
    protected string $tableName;

    protected Connection $connection;

    public function __construct()
    {
        $this->connection = new Connection();
    }

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return [];
    }

    /**
     * @param array|null $result
     * @return $this
     */
    public function getSingleObject(?array $result): DbModel
    {
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        return $this;
    }

    /**
     * @param string $id
     * @return $this|null
     */
    public function getById(string $id): ?DbModel
    {

        $stmt = "select * from " . $this->tableName . " where id='" . $id . "'";
        $result = $this->connection->select($stmt);
        if (isset($result[0])) {
            return $this->getSingleObject($result[0]);
        }
        return null;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        $this->id = Uuid::uuid4()->toString();
        $fields = array_merge(['id'], $this->getAttributes());
        $values = [];
        foreach ($fields as $key) {
            $values[] = "'" . $this->{$key} . "'";
        }
        $sql = "INSERT INTO " . $this->getTableName() . " (" . join(',', $fields) . ") values (" . join(',', $values) . ")";
        if ($this->connection->execute($sql)) {
            return $this->id;
        }
        return null;
    }

    /**
     * @param array $values
     * @return array
     */
    protected function prepareEqualValues(array $values): array
    {
        $setValues = [];
        foreach ($values as $key => $value) {
            $setValues[] = $key . "='" . $value . "'";
        }
        return $setValues;
    }

    /**
     * @param array $values
     * @return string
     */
    protected function prepareAndWhere(array $values): string
    {
        return join(' AND ', $this->prepareEqualValues($values));
    }

    /**
     * @param array $values
     * @return string
     */
    protected function prepareSetValues(array $values): string
    {
        return join(',', $this->prepareEqualValues($values));
    }

    /**
     * @param array $condition
     * @param array $updateData
     * @return bool
     */
    public function update(array $condition, array $updateData): bool
    {

        $sql = "UPDATE " . $this->getTableName() . " SET " . $this->prepareSetValues($updateData) . " WHERE " . $this->prepareAndWhere($condition);
        return $this->connection->execute($sql);
    }

    /**
     * @param array $selectFields
     * @param array $condition
     * @return Object|null
     */
    public function selectForUpdate(array $selectFields, array $condition):?Object
    {
        $sql = "SELECT " . join(',', $selectFields) . " FROM " . $this->getTableName() . " WHERE " . $this->prepareAndWhere($condition) . ' FOR UPDATE';
        $result = $this->connection->select($sql,Connection::RETURN_TYPE_OBJECT);
        if ( $result[0]) {
            return $result[0];
        }
        return null;
    }

    /**
     * @return Connection
     */
    public function getConnection():Connection
    {
        return $this->connection;
    }

    /**
     * @return array
     */
    public function fetchAll():array
    {
        $sql = "SELECT * FROM " . $this->getTableName();
        return $this->connection->select($sql);
    }

    /**
     * @param array $condition
     * @return array
     */
    public function findBy(array $condition):array
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->prepareAndWhere($condition) ;
        return $this->connection->select($sql);
    }

    /**
     * @param array $condition
     * @return array|null
     */
    public function findOneBy(array $condition):?array
    {
        $result = $this->findBy($condition);
        if(is_array($result) && count($result) > 0){
            return $result[0];
        }
        return null;
    }
}
