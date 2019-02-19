<?php

namespace dbourni\OpenclassroomsP5;

session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['name'] = '';
    $_SESSION['role'] = '';
}

require 'config.php';
require 'controllers/Controller.php';
require 'controllers/CommentController.php';
require 'controllers/HomeController.php';
require 'controllers/PostController.php';
require 'controllers/UserController.php';
require 'models/Manager.php';
require 'models/CommentManager.php';
require 'models/PostManager.php';
require 'models/UserManager.php';
require 'Router.php';
require 'vendor/autoload.php';

$router = new Router();
$router->start();
