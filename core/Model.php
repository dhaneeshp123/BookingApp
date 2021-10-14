<?php


namespace app\core;


abstract class Model extends DbModel
{

    protected $id;

    protected function getAttributes(): array
    {
        return [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function loadData(array $data)
    {
        $attributes = $this->getAttributes();
        foreach ($data as $key => $value) {
            $key = strtolower($key);
            if (property_exists($this, $key)) {
                if (in_array($key, $attributes)) {
                    $this->{$key} = $value;
                }
            }
        }
    }
}
