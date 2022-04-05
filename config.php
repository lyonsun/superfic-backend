<?php

require_once('vendor/autoload.php');

$heroku_db_url = parse_url(getenv("JAWSDB_URL"));

if (!empty($heroku_db_url['path'])) {
    $db_host = $heroku_db_url["host"];
    $db_user = $heroku_db_url["user"];
    $db_pass = $heroku_db_url["pass"];
    $db_name = substr($heroku_db_url["path"], 1);
} else {
    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    $db_host = $_ENV['MYSQL_DB_HOST'];
    $db_user = $_ENV['MYSQL_USER'];
    $db_pass = $_ENV['MYSQL_PASSWORD'];
    $db_name = $_ENV['MYSQL_DATABASE'];
}

define("DB_HOST", $db_host);
define("DB_USERNAME", $db_user);
define("DB_PASSWORD", $db_pass);
define("DB_DATABASE_NAME", $db_name);

define("API_URL", $_ENV['API_URL']);
define("CLIENT_ID", $_ENV['CLIENT_ID']);
define("CLIENT_EMAIL", $_ENV['CLIENT_EMAIL']);
define("CLIENT_NAME", $_ENV['CLIENT_NAME']);
