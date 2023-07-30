<?php

namespace Jacksonsr45\RadianteORM\Connection;

use PDO;
use PDOException;

class Connection
{
    private static $instance;
    private $dbHost;
    private $dbName;
    private $dbUsername;
    private $dbPassword;
    private $dbDriver;
    private $dbConnection;

    private function __construct(
        string $dbHost = null,
        string $dbName = null,
        string $dbUsername = null,
        string $dbPassword = null,
        string $dbDriver = null
    ) {
        $this->dbHost = $dbHost !== null ? $dbHost : getenv('DB_HOST');
        $this->dbName = $dbName !== null ? $dbName : getenv('DB_NAME');
        $this->dbUsername = $dbUsername !== null ? $dbUsername : getenv('DB_USERNAME');
        $this->dbPassword = $dbPassword !== null ? $dbPassword : getenv('DB_PASSWORD');
        $this->dbDriver = $dbDriver !== null ? $dbDriver : getenv('DB_DRIVER');
    }

    public static function getInstance(
        string $dbHost = null,
        string $dbName = null,
        string $dbUsername = null,
        string $dbPassword = null,
        string $dbDriver = null
    ) {
        if (self::$instance === null) {
            self::$instance = new Connection($dbHost, $dbName, $dbUsername, $dbPassword, $dbDriver);
        }

        return self::$instance;
    }

    public function connect()
    {
        try {
            $dsn = "{$this->dbDriver}:host={$this->dbHost};dbname={$this->dbName}";

            $this->dbConnection = new PDO($dsn, $this->dbUsername, $this->dbPassword);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $this->dbConnection = null;
    }

    public function executeQuery($query)
    {
        return $this->dbConnection->exec($query);
    }
}
