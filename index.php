<?php

namespace dbourni\OpenclassroomsP5;

require 'config.php';
require 'controllers/Controller.php';
require 'controllers/HomeController.php';
require 'controllers/PostController.php';
require 'models/Manager.php';
require 'models/PostManager.php';
require 'Router.php';
require 'vendor/autoload.php';

$router = new Router();
$router->start();
