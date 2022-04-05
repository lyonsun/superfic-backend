<?php

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-Type: application/json');

require_once('config.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (!isset($uri[2]) || (isset($uri[2]) && $uri[2] == '')) {
    echo "Welcome to Superfic!";
    exit();
}

if ($uri[2] === 'ping') {
    require_once('db_builder.php');
    $db_builder = new DBBuilder();
    $db_builder->build_db();
    exit();
} else {
    require('controller.php');
    $controller = new Controller();
    if (method_exists($controller, $uri[2])) {
        $controller->{$uri[2]}();
    } else {
        echo json_encode(array('error' => 'Method not found'));
    }
}
