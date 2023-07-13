<?php

declare(strict_types=1);

ini_set('display_errors', 'On');

// Load Dependencies
require dirname(__DIR__) . '/vendor/autoload.php';

set_exception_handler("ErrorHandler::handleException");

// Load connection variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Recibe request
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

$resource = $parts[2];

$id = $parts[3] ?? null;

if ($resource !== 'tasks') {
	http_response_code(404);
	exit;
}

header("Content-Type: application/json; charset=UTF-8");

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);

$taskGateway = new TaskGateway($database);

$controller = new TaskController($taskGateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);