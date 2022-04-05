<?php

require_once('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("DB_HOST", $_ENV['MYSQL_DB_HOST']);
define("DB_USERNAME", $_ENV['MYSQL_USER']);
define("DB_PASSWORD", $_ENV['MYSQL_PASSWORD']);
define("DB_DATABASE_NAME", $_ENV['MYSQL_DATABASE']);

define("API_URL", $_ENV['API_URL']);
define("CLIENT_ID", $_ENV['CLIENT_ID']);
define("CLIENT_EMAIL", $_ENV['CLIENT_EMAIL']);
define("CLIENT_NAME", $_ENV['CLIENT_NAME']);
