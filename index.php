<?php

require 'controllers/HomeController.php';
require 'vendor/autoload.php';

try {
    if (false === isset($_GET['p'])) {
        $homeController = new HomeController();
        $homeController->viewHome();
    } elseif ('home' === $_GET['p']) {
        $homeController = new HomeController();
        $homeController->viewHome();
    } else {
        throw new Exception('Unknown page');
    }
} catch(Exception $e) {
    echo 'Error : ' . $e->getMessage();
}
