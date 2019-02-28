<?php

namespace OpenclassroomsP5;

session_start();

if (!isset($_SESSION['name'])) {
    $_SESSION['name'] = '';
    $_SESSION['role'] = '';
}

require 'config.php';
require __DIR__.'/vendor/autoload.php';

$router = new Router();
$router->start();
