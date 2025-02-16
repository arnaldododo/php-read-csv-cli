<?php

/**
 * FileHandler.php - FileHandler class
 * This file manages file processing.
 */
class FileHandler
{
    /**
     * Constructor for the class.
     *
     * @param string $filePath The path to the CSV file.
     */
    public function __construct(
        private string $filePath
    ) {}

    /**
     * Parses a CSV file and returns its contents as an array.
     *
     * @return array The parsed CSV data.
     */
    public function parseCsv()
    {
        if (!file_exists($this->filePath)) {
            die("Error: File '{$this->filePath}' not found.\n");
        }

        $data = [];
        if (($handle = fopen($this->filePath, "r")) !== false) {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Prints the data in a tabular format.
     *
     * @param array $data The data to be printed.
     */
    public function printData(array $data)
    {
        foreach ($data as $row) {
            echo implode(" | ", $row) . PHP_EOL;
        }
    }
}
