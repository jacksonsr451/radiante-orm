<?php

namespace Jacksonsr45\RadianteORM\Migration;

use Jacksonsr45\RadianteORM\Connection\Connection;

class MigrationTable
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function createMigrationTable()
    {
        $this->db->connect();

        $createQuery = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration_name VARCHAR(255) NOT NULL,
                migration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $this->db->executeQuery($createQuery);
        echo "Migration table created successfully!\n";

        $this->db->disconnect();
    }
}
