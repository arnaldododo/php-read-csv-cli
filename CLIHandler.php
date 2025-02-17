<?php

/**
 * CLIHandler.php - CLIHandler class
 * This file manages CLI's input ouput.
 */

require_once __DIR__ . '/FileHandler.php';
require_once __DIR__ . '/Database.php';

class CLIHandler
{
    /**
     * Constructor for the class.
     *
     * @param array $options CLI input.
     */
    public function __construct(
        private array $options
    ) {}

    /**
     * Run the CLI command.
     * Contains the logic to handle the CLI input.
     */
    public function run()
    {
        try {
            if (isset($this->options['help'])) {
                $this->showHelp();
                return;
            }

            if (isset($this->options['create_table'])) {
                $this->handleCreateTable();
                return;
            }

            if (isset($this->options['file'])) {
                $this->handleFileProcessing();
                return;
            }

            echo "Invalid command. Use --help for more information." . PHP_EOL;
        } catch (Exception $e) {
            echo "An error occured." . PHP_EOL;
        }
    }

    /**
     * Create the users table.
     */
    private function handleCreateTable()
    {
        list($user, $password, $host) = $this->getDbCredentials();

        echo "Creating users table...\n";
        $database = new Database($user, $password, $host);
        $database->createTable();
    }

    /**
     * Handle file processing, parsing and inserting to the database.
     */
    private function handleFileProcessing()
    {
        $file = $this->options['file'];

        $fileHandler = new FileHandler($file);
        $data = $fileHandler->parseCsv();

        if (isset($this->options['dry_run'])) {
            echo "Dry run mode: Parsed data from file '{$file}':\n\n";
            $fileHandler->printData($data);
            echo "\nDry run complete. No data was inserted into the database.\n";
            return;
        }

        list($user, $password, $host) = $this->getDbCredentials();

        echo "Inserting users data...\n";
        $database = new Database($user, $password, $host);
        $database->insertUser($data);
    }

    /**
     * Retrieve and validate database credentials from CLI options
     *
     * @return array The database credentials.
     */
    private function getDbCredentials()
    {
        $user = $this->options['u'] ?? null;
        $host = $this->options['h'] ?? null;
        $password = $this->options['p'] ?? '';

        if (!$user || !$host) {
            die("Database username (-u) and host (-h) are required. Use --help for more information.\n");
        }

        return [$user, $password, $host];
    }

    /**
     * Show the help message.
     */
    private function showHelp()
    {
        $helpMessage = <<<HELP
            Usage:
            php user_upload.php --create_table -u <username> -p <password> -h <host>
            php user_upload.php --file=<file> -u <username> -p <password> -h <host>
            php user_upload.php --file=<file> --dry_run
            php user_upload.php --help

            Options:
            -u                  Database username
            -p                  Database password
            -h                  Database host

            --file <file>       Name of the CSV file to be parsed.
            --create_table      Create users table (and no further action will be taken).
            --dry_run           Run the script without inserting into the database.
            --help              Show this help message
        HELP;

        echo $helpMessage . PHP_EOL;
    }
}
