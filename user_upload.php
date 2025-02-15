<?php

/** 
 * user_upload.php - Main file
 * This file is the entry point of the application.
 */

require_once __DIR__ . '/CLIHandler.php';

$options = getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);

$cliHandler = new CLIHandler($options);
$cliHandler->run();
