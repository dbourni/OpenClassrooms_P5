<?php

require 'controllers/Controller.php';
require 'controllers/ErrorController.php';
require 'controllers/HomeController.php';
require 'Router.php';
require 'vendor/autoload.php';

use dbourni\OpenclassroomsP5\Router;

$router = new Router();
$router->start();
