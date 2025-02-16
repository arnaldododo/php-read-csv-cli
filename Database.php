<?php

/**
 * Database.php - Database class
 * This file manages the database connection.
 */

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Connection;

class Database
{
    /**
     * Constructor for the class.
     *
     * @param string $user The database username.
     * @param string $password The database password.
     * @param string $host The database host.
     * @param string $dbname The database name.
     * @param string $driver The database driver.
     * @param Connection|null $connection The database connection.
     */
    public function __construct(
        private string $user,
        private string $password,
        private string $host = '127.0.0.1',
        private string $dbname = 'users',
        private string $driver = 'pdo_pgsql',
        private ?Connection $connection = null
    ) {
        $this->connect();
    }

    /**
     * Establish the database connection.
     */
    private function connect()
    {
        $connectionParams = [
            'user'     => $this->user,
            'password' => $this->password,
            'host'     => $this->host,
            'dbname'   => $this->dbname,
            'driver'   => $this->driver,
        ];

        try {
            $this->connection = DriverManager::getConnection($connectionParams);
        } catch (Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
