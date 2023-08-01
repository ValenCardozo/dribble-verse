<?php

ini_set('display_errors', 'On');

// Load Dependencies
require dirname(__DIR__) . '/vendor/autoload.php';

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

// Load connection variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header("Content-Type: application/json; charset=UTF-8");