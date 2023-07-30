<?php

namespace Jacksonsr45\RadianteORM\Migration;

class TableColumn
{
    private string $name;
    private string $type;
    private bool $isPrimaryKey;

    public function __construct(
        string $name,
        string $type,
        bool $isPrimaryKey = false
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->isPrimaryKey = $isPrimaryKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isPrimaryKey(): bool
    {
        return $this->isPrimaryKey;
    }
}
