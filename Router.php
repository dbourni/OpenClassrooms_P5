<?php

namespace dbourni\OpenclassroomsP5;

use dbourni\OpenclassroomsP5\ErrorController;
use dbourni\OpenclassroomsP5\HomeController;

/**
 * Routing class
 */
class Router
{
    /** @var HomeController */
    private $homeController;
    /** @var ErrorController */
    private $errorController;

    /**
     * Instantiates the controllers
     */
    public function __construct()
    {
        $this->homeController = new HomeController();
        $this->errorController = new ErrorController();
    }

    /**
     * Routing
     */
    public function start()
    {
        if (false === isset($_GET['p'])) {
            $this->homeController->viewHome();
            return;
        } 

        switch ($_GET['p']) {
            case 'home':
                $this->homeController->viewHome();
                break;
            case 'sendemail':
                $this->homeController->sendEmail($_POST['email'], $_POST['name'], $_POST['message']);
                break;
            default:
                $this->errorController->pageNotFound($_GET['p']);
                break;
        }
    
    }
}
