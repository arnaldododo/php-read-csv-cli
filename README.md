# php-read-csv-cli

This repository contains a PHP command-line interface (CLI) script to read and process CSV files and store the data in a PostgreSQL database.

## Features

- Read CSV files
- Process and display CSV data in the CLI
- Store data into PostgreSQL

## Requirements

- PHP 8.3
- PostgreSQL 13 or higher
- Database name: postgres
- File name: users.csv (in the main directory)

## Installation

Clone the repository:

```sh
git clone https://github.com/arnaldododo/php-read-csv-cli.git
```

Navigate to the project directory:

```sh
cd php-read-csv-cli
```

Install dependencies using Composer:

```sh
composer install
```

## Dependency

This project uses the following dependency:
- `doctrine/dbal`

## Usage

Run the script with the user_upload.php as the main file. Use --help option to show all the available options.
```sh
php user_upload.php --help
```

Dry run - This mode will parse the file without inserting to the database. The data will be displayed in a tabular format.
```sh
php user_upload.php --file=users.csv --dry_run
```

Create the users table. Run this before inserting data to the database.
```sh
php user_upload.php --create_table -u <username> -p <password> -h <host>
```

Parse the file and insert the data to the users table.
```sh
php user_upload.php --file=users.csv -u <username> -p <password> -h <host>
```

## License

This project is licensed under the MIT License.

For more details, visit the [repository](https://github.com/arnaldododo/php-read-csv-cli).
