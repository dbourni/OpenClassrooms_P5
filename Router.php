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
     *
     * @return void
     */
    public function __construct()
    {
        $this->homeController = new HomeController();
        $this->errorController = new ErrorController();
    }

    /**
     * Routing
     *
     * @return void
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
            default:
                $this->errorController->pageNotFound($_GET['p']);
                break;
        }
    
    }
}
