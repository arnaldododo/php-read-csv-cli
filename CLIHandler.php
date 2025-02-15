
<?php
/**
 * CLIHandler.php - CLIHandler class
 * This file manages CLI's input ouput.
 */
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
