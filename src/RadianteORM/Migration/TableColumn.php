<?php

namespace Jacksonsr45\RadianteORM\Migration;

class TableColumn
{
    private $name;
    private $type;
    private $isPrimaryKey;

    public function __construct($name, $type, $isPrimaryKey = false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isPrimaryKey = $isPrimaryKey;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }
}
