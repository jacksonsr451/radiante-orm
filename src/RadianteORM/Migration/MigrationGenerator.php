<?php

namespace Jacksonsr45\RadianteORM\Migration;

use Jacksonsr45\RadianteORM\Connection\Connection;
use Jacksonsr45\RadianteORM\Model\Model;
use ReflectionClass;
use ReflectionProperty;

class MigrationGenerator
{
    private $db;
    private Model $model;
    private ReflectionProperty $tableProperty;
    private ReflectionProperty $properties;

    public function __construct(Connection $db, $model)
    {
        $this->db = $db;
        $this->model = $model;
    }

    private function getTableFromClass($className): mixed
    {
        $reflection = new ReflectionClass($className);
        $this->tableProperty = $reflection->getProperty('table');
        $this->tableProperty->setAccessible(true);
        return $this->tableProperty->getValue(new $className());
    }

    private function getPropertyType($property): mixed
    {
        if ($property->hasType() && !$property->getType()->isBuiltin()) {
            return $property->getType()->getName();
        } else {
            return $this->getTypeFromValue($property->getValue(new $this->model()));
        }
    }

    private function getTypeFromValue(mixed $value): string
    {
        $string = '';

        switch ($value) {
            case is_int($value):
                $string = 'INT';
                break;
            case is_float($value):
                $string = 'FLOAT';
                break;
            case is_bool($value):
                $string = 'BOOL';
                break;
            case is_null($value):
                $string = 'NULL';
                break;
            case is_array($value):
                $string = 'JSON';
                break;
            case $this->isDate($value):
                $string = 'TIMESTAMP';
                break;
            case is_object($value):
                $string = 'INT';
                break;
            default:
                $string = 'VARCHAR(255)';
                break;
        }

        return $string;
    }

    private function isDate($value): bool
    {
        return (bool)strtotime($value);
    }

    public function createMigrationTable(): void
    {
        $migrationTable = new MigrationTable($this->db);
        $migrationTable->createMigrationTable();
    }

    public function createMigrationFromClass(): void
    {
        $tableName = $this->getTableFromClass($this->model);

        $tableName = trim($tableName);
        $tableName = preg_replace('/[^A-Za-z0-9\_]/', '', $tableName);
        $migrationFileName = date('Ymd_His') . "_create_{$tableName}_table.sql";

        $migrationPath = __DIR__ . "/migrations/{$migrationFileName}";

        $reflection = new ReflectionClass($this->model);
        $this->properties = $reflection->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
        );

        $tableColumns = [];
        $tablePrimaryColumn = null;

        foreach ($this->properties as $property) {
            $propertyName = $property->getName();
            $propertyType = $this->getPropertyType($property);

            if ($propertyName === 'id') {
                $tablePrimaryColumn = new TableColumn(
                    $propertyName,
                    $this->getTypeFromValue($property->getValue(new $this->model())),
                    true
                );
            } else {
                $tableColumns[] = new TableColumn($propertyName, $propertyType);
            }
        }

        $migrationWriter = new MigrationWriter($tableName, $migrationPath);
        $migrationWriter->writeMigrationContent($tablePrimaryColumn, $tableColumns);
    }
}
