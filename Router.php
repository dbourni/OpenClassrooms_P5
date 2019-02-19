<?php

namespace dbourni\OpenclassroomsP5;

/**
 * Routing class
 * Class Router
 *
 * @package dbourni\OpenclassroomsP5
 */
class Router
{
    private $homeController;
    private $postController;
    private $commentController;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->homeController = new HomeController();
        $this->postController = new PostController();
        $this->commentController = new CommentController();
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
                    break;
                }
                $this->postController->displayError('Article inexistant !');
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
                    break;
                }
                $this->postController->displayError('Une erreur s\'est produite lors de l\'édition de l\'article!');
                break;
            case 'updatePost':
                if (isset($_GET['post'])) {
                    $this->postController->updatePost($_GET['post']);
                    break;
                }
                $this->postController->displayError('Une erreur s\'est produite lors de la mise à jour de l\'article!');
                break;
            case 'deletePost':
                if (isset($_GET['post'])) {
                    $this->postController->deletePost($_GET['post']);
                    break;
                }
                $this->postController->displayError('Une erreur s\'est produite lors de la suppression de l\'article!');
                break;
            case 'saveComment':
                if (isset($_GET['post'])) {
                    $this->commentController->saveComment($_GET['post']);
                    break;
                }
                $this->commentController->displayError('Une erreur s\'est produite lors de l\'enregistrement du commentaire!');
                break;
            case 'backofficeCommentsList':
                $this->commentController->backofficeCommentsList();
                break;
            case 'validComment':
                if (isset($_GET['comment'])) {
                    $this->commentController->validComment($_GET['comment']);
                    break;
                }
                $this->commentController->displayError('Une erreur s\'est produite lors de la validation du commentaire!');
                break;
            case 'deleteComment':
                if (isset($_GET['comment'])) {
                    $this->commentController->deleteComment($_GET['comment']);
                    break;
                }
                $this->commentController->displayError('Une erreur s\'est produite lors de la suppression du commentaire !');
                break;
            default:
                $this->homeController->displayError('La page n\'existe pas');
                break;
        }
    
    }
}

