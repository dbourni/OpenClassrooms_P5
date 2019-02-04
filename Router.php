<?php

namespace dbourni\OpenclassroomsP5;

/**
 * Routing class
 * Class Router
 * @package dbourni\OpenclassroomsP5
 */
class Router
{
    private $homeController;
    private $postController;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->homeController = new HomeController();
        $this->postController = new PostController();
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
            case 'list':
                $this->postController->viewList();
                break;
            case 'view':
                if (isset($_GET['post'])) {
                    $this->postController->viewPost($_GET['post']);
                } else {
                    $this->postController->displayError('Une erreur s\'est produite !');
                }
                break;
            case 'backoffice':
                $this->homeController->viewBackoffice();
                break;
            case 'backofficePostsList':
                $this->postController->backofficePostsList();
                break;
            case 'newPost':
                $this->postController->newPost();
                break;
            case 'savePost':
                $this->postController->savePost();
                break;
            case 'editPost':
                if (isset($_GET['post'])) {
                    $this->postController->editPost($_GET['post']);
                } else {
                    $this->postController->displayError('Une erreur s\'est produite !');
                }
                break;
            case 'updatePost':
                if (isset($_GET['post'])) {
                    $this->postController->updatePost($_GET['post']);
                } else {
                    $this->postController->displayError('Une erreur s\'est produite !');
                }
                break;
            case 'deletePost':
                if (isset($_GET['post'])) {
                    $this->postController->deletePost($_GET['post']);
                } else {
                    $this->postController->displayError('Une erreur s\'est produite !');
                }
                break;
            default:
                $this->homeController->displayError('La page n\'existe pas');
                break;
        }
    
    }
}

