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
            fgetcsv($handle); // Skip the header row
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }

        return $this->preprocessData($data);
    }

    /**
     * Preprocesses the data.
     *
     * @param array $row The row to be preprocessed.
     * @return array The preprocessed row.
     */
    private function preprocessData(array $data)
    {
        $preprocessedData = [];
        foreach ($data as $row) {
            $row[0] = $this->cleanName($row[0]);
            $row[1] = $this->cleanName($row[1]);
            $temp = $row[2];
            if (!$row[2] = $this->cleanEmail($row[2])) {
                echo $temp . " is not a valid email address. Skipping this entry.\n";
                continue;
            }
            $preprocessedData[] = $row;
        }
        echo PHP_EOL;
        return $preprocessedData;
    }

    /**
     * Cleans the name column.
     *
     * @param string $name
     * @return string The cleaned name.
     */
    private function cleanName(string $name)
    {
        $name = trim($name);
        $name = strtolower($name);
        $name = ucwords($name);
        $name = preg_replace("/[^\p{L}']/u", "", $name);

        return $name;
    }

    /**
     * Cleans and validates an email address.
     *
     * @param string $email The raw email address.
     * @return string The cleaned email if valid, otherwise false.
     */
    private function cleanEmail(string $email)
    {
        $email = trim($email);
        $email = strtolower($email);

        $domain = substr(strrchr($email, "@"), 1);
        if (!preg_match('/^[\w.-]+@[\w.-]+\.[a-z]{2,}$/', $email) || !checkdnsrr($domain)) {
            return false;
        }
        return $email;
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
