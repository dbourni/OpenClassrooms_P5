<?php

namespace OpenclassroomsP5;

use OpenclassroomsP5\controllers\CommentController;
use OpenclassroomsP5\controllers\HomeController;
use OpenclassroomsP5\controllers\PostController;
use OpenclassroomsP5\controllers\UserController;

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
    private $userController;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->homeController = new HomeController();
        $this->postController = new PostController();
        $this->commentController = new CommentController();
        $this->userController = new UserController();
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

        // Routing without particular rights
        switch ($_GET['p']) {
            case 'home':
                $this->homeController->viewHome();
                return;
            case 'sendemail':
                $this->homeController->sendEmail($_POST['email'], $_POST['name'], $_POST['message']);
                return;
            case 'connection':
                $this->homeController->viewConnect();
                return;
            case 'disconnection':
                $this->userController->disConnect();
                return;
            case 'list':
                $this->postController->viewList();
                return;
            case 'view':
                if (isset($_GET['post'])) {
                    $this->postController->viewPost($_GET['post']);
                    return;
                }
                $this->postController->displayError('Article inexistant !');
                return;
            case 'connect':
                $this->userController->connect();
                return;
            case 'subscription':
                $this->userController->subscription();
                return;
            case 'saveSubscription':
                $this->userController->saveSubscription();
                return;
            case 'forgottenPassword':
                $this->userController->forgottenPassword();
                return;
            case 'initPassword':
                $this->userController->initPassword();
                return;
            case 'modifyPassword':
                if (isset($_GET['code'])) {
                    $this->userController->modifyPassword($_GET['code']);
                    return;
                }
                $this->userController->displayError('Une erreur s\'est produite lors de la réinitialisation du mot de passe !');
                return;
            case 'saveNewPassword':
                $this->userController->saveNewPassword();
                return;
        }

        // Routing with rights
        if (!$this->homeController->checkRights($_GET['p'])) {
            $this->homeController->displayError('Action interdite ou page inexistante !!!');
            return;
        }

        switch ($_GET['p']) {
//            case 'home':
//                $this->homeController->viewHome();
//                break;
//            case 'sendemail':
//                $this->homeController->sendEmail($_POST['email'], $_POST['name'], $_POST['message']);
//                break;
//            case 'connection':
//                $this->homeController->viewConnect();
//                break;
//            case 'disconnection':
//                $this->userController->disConnect();
//                break;
//            case 'list':
//                $this->postController->viewList();
//                break;
//            case 'view':
//                if (isset($_GET['post'])) {
//                    $this->postController->viewPost($_GET['post']);
//                    break;
//                }
//                $this->postController->displayError('Article inexistant !');
//                break;
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
            case 'backofficeUsersList':
                $this->userController->backofficeUsersList();
                break;
            case 'deleteUser':
                if (isset($_GET['user'])) {
                    $this->userController->deleteUser($_GET['user']);
                    break;
                }
                $this->userController->displayError('Une erreur s\'est produite lors de la suppression de l\'utilisateur !');
                break;
            case 'editUser':
                if (isset($_GET['user'])) {
                    $this->userController->editUser($_GET['user']);
                    break;
                }
                $this->userController->displayError('Une erreur s\'est produite lors de l\'édition de l\'utilisateur !');
                break;
            case 'updateUser':
                if (isset($_GET['user'])) {
                    $this->userController->updateUser($_GET['user']);
                    break;
                }
                $this->userController->displayError('Une erreur s\'est produite lors de la mise à jour de l\'utilisateur !');
                break;
            case 'newUser':
                $this->userController->newUser();
                break;
            case 'saveUser':
                $this->userController->saveUser();
                break;
//            case 'connect':
//                $this->userController->connect();
//                break;
//            case 'subscription':
//                $this->userController->subscription();
//                break;
//            case 'saveSubscription':
//                $this->userController->saveSubscription();
//                break;
            case 'validUser':
                if (isset($_GET['user'])) {
                    $this->userController->validUser($_GET['user']);
                    break;
                }
                $this->commentController->displayError('Une erreur s\'est produite lors de la validation de l\'utilisateur !');
                break;
//            case 'forgottenPassword':
//                $this->userController->forgottenPassword();
//                break;
//            case 'initPassword':
//                $this->userController->initPassword();
//                break;
//            case 'modifyPassword':
//                if (isset($_GET['code'])) {
//                    $this->userController->modifyPassword($_GET['code']);
//                    break;
//                }
//                $this->userController->displayError('Une erreur s\'est produite lors de la réinitialisation du mot de passe !');
//                break;
//            case 'saveNewPassword':
//                $this->userController->saveNewPassword();
//                break;
            default:
                $this->homeController->displayError('La page n\'existe pas');
                break;
        }
    }
}

