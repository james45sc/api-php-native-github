<?php

use Src\Router;
use Src\Controllers\UserController;

require __DIR__ . '/../src/Router.php';
require __DIR__ . '/../src/Controllers/UserController.php';

$router = new Router();
$userController = new UserController();

$router->add('GET', '/api/v1/users', [$userController, 'index']);
$router->add('GET', '/api/v1/users/{id}', [$userController, 'show']);

$router->run();
