<?php


namespace app\core;


abstract class DbModel
{
    /** @var string */
    protected $tableName;

    public function getSingleObject(array $result)
    {
        if(count($result) > 0) {
            foreach($result[0] as $key => $value)
            {
                if(property_exists($this,$key))
                {
                    $this->{$ke} = $value;
                }
            }
        }

       return $this;
    }

    public function getById(string $id)
    {
        $stmt = "select * from " . $this->tableName . " where id='". $id ."'";
    }

}
