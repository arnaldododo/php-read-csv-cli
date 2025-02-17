<?php

/**
 * Database.php - Database class
 * This file manages the database connection.
 */

require_once __DIR__ . '/vendor/autoload.php';

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
        private string $user = '',
        private string $password = '',
        private string $host = '127.0.0.1',
        private string $dbname = 'postgres',
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
            die("Database connection failed.\n");
        }
    }

    /**
     * Create the users table.
     * 
     * @return int|string
     */
    public function createTable()
    {
        $sql = "DROP TABLE IF EXISTS users;
        CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            surname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE
        )";

        try {
            $this->connection->executeStatement($sql);
            echo "Users table created successfully.\n";
        } catch (Exception $e) {
            die("An error occured. Failed to create the users table.\n");
        }
    }

    /**
     * Insert a user into the database.
     * 
     * @param array $data The user data.
     */
    public function insertUser(array $data)
    {
        foreach ($data as $user) {
            try {
                $qb = $this->connection->createQueryBuilder();

                $qb->insert('users')
                    ->setValue('name', ':name')
                    ->setValue('surname', ':surname')
                    ->setValue('email', ':email')
                    ->setParameter('name', $user[0])
                    ->setParameter('surname', $user[1])
                    ->setParameter('email', $user[2]);

                $qb->executeStatement();
                echo "User inserted successfully.\n";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), '23505') !== false) {
                    echo "Skipping duplicate email: {$user[2]}\n";
                    continue;
                }
                die("An error occurred. Failed to insert users {$e}\n");
            }
        }
    }
}
