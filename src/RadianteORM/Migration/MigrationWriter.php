<?php

namespace Jacksonsr45\RadianteORM\Migration;

use Exception;

class MigrationWriter
{
    private $tableName;
    private $migrationPath;

    public function __construct($tableName, $migrationPath)
    {
        $this->tableName = $tableName;
        $this->migrationPath = $migrationPath;
    }

    public function writeMigrationContent($primaryColumn, $columns): void
    {
        $primaryKeyColumn = "{$primaryColumn->getName()} {$primaryColumn->getType()} AUTO_INCREMENT PRIMARY KEY";

        $columnsContent = implode(",\n", array_map(function ($column) {
            return "{$column->getName()} {$column->getType()} NOT NULL";
        }, $columns));

        $migrationContent = "CREATE TABLE {$this->tableName} (\n";
        $migrationContent .= $primaryKeyColumn . ",\n";
        $migrationContent .= $columnsContent;
        $migrationContent .= "\n);";

        try {
            file_put_contents($this->migrationPath, $migrationContent);
            echo "Migration '{$this->migrationPath}' created successfully!\n";
        } catch (Exception $e) {
            die("Error creating migration file: " . $e->getMessage());
        }
    }
}
