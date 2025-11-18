<?php

require __DIR__ . '/../src/Router.php';
require __DIR__ . '/../src/Controllers/UserController.php';

use Src\Router;
use Src\Controllers\UserController;

$router = new Router();
$userController = new UserController();

$router->add('GET', '/api/v1/users', [$userController, 'index']);
$router->add('GET', '/api/v1/users/{id}', [$userController, 'show']);
$router->add('POST', '/api/v1/users', [$userController, 'store']);
$router->add('PUT', '/api/v1/users/{id}', [$userController, 'update']);
$router->add('DELETE', '/api/v1/users/{id}', [$userController, 'destroy']);


$router->run();
