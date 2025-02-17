
<?php
/**
 * CLIHandler.php - CLIHandler class
 * This file manages CLI's input ouput.
 */

require_once __DIR__ . '/FileHandler.php';
require_once __DIR__ . '/Database.php';

class CLIHandler
{
    public function __construct(
        private array $options
    ) {}

    public function run()
    {
        if (isset($this->options['help'])) {
            $this->showHelp();
            return;
        }

        if (isset($this->options['create_table'])) {
            if (isset($this->options['u']) && isset($this->options['h'])) {
                echo "Creating users table...\n";
                $database = new Database(
                    $this->options['u'],
                    !empty($this->options['p']) ? $this->options['p'] : '',
                    $this->options['h']
                );
                $database->createTable();
                return;
            }
        }

        if (isset($this->options['file'])) {
            if (isset($this->options['dry_run'])) {
                echo "Start parsing the file: {$this->options['file']}\n\n";

                $fileHandler = new FileHandler($this->options['file']);
                $data = $fileHandler->parseCsv();
                $fileHandler->printData($data);

                echo "\nDry run complete. No data was inserted into the database.\n";
                return;
            }

            if (isset($this->options['u']) && isset($this->options['h'])) {
                $fileHandler = new FileHandler($this->options['file']);
                $data = $fileHandler->parseCsv();

                echo "Inserting users data...\n";
                $database = new Database(
                    $this->options['u'],
                    !empty($this->options['p']) ? $this->options['p'] : '',
                    $this->options['h']
                );
                $database->insertUser($data);
                return;
            }
        }

        echo "Invalid command. Use --help for more information." . PHP_EOL;
    }

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
