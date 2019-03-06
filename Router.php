<?php

namespace OpenclassroomsP5;

use OpenclassroomsP5\Controllers\CommentController;
use OpenclassroomsP5\Controllers\HomeController;
use OpenclassroomsP5\Controllers\PostController;
use OpenclassroomsP5\Controllers\UserController;

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
        $getP = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_STRING);

        if (!$getP) {
            $this->homeController->viewHome();
            return;
        }

        $route = $this->getRouteWithoutRights($getP);

        if (!$route AND $this->homeController->checkRights($getP)) {
            $route = $this->getRouteWithRights($getP);
        }

        if ($route AND $this->getController($route['class'])) {
            if (!$this->{$this->getController($route['class'])}->{$getP}($route['parameters'])) {
                $errorMessage = isset($GLOBALS['errorMessage']) ? $GLOBALS['errorMessage'] : '';
                $this->homeController->displayError('Une erreur est survenue<br>' . $errorMessage);
            }

            return;
        }

        $this->homeController->displayError('Action interdite ou page inexistante !!!');
    }

    /**
     * Return the route without rights
     *
     * @param string $getParameter
     *
     * @return array|null
     */
    private function getRouteWithoutRights(string $getParameter)
    {
        $getPost = filter_input(INPUT_GET, 'post');
        $getCode = filter_input(INPUT_GET, 'code');
        $postEmail = filter_input(INPUT_POST, 'email');
        $postName = filter_input(INPUT_POST, 'name');
        $postMessage = filter_input(INPUT_POST, 'message');

        $routes = ['viewHome' => ['class' => 'home', 'parameters' => []],
            'sendEmail' => ['class' => 'home', 'parameters' => [$postEmail, $postName, $postMessage]],
            'viewConnect' => ['class' => 'home', 'parameters' => []],
            'disConnect' => ['class' => 'user', 'parameters' => []],
            'viewPost' => ['class' => 'post', 'parameters' => $getPost],
            'viewList' => ['class' => 'post', 'parameters' => []],
            'connect' => ['class' => 'user', 'parameters' => []],
            'subscription' => ['class' => 'user', 'parameters' => []],
            'saveSubscription' => ['class' => 'user', 'parameters' => []],
            'forgottenPassword' => ['class' => 'user', 'parameters' => []],
            'initPassword' => ['class' => 'user', 'parameters' => []],
            'modifyPassword' => ['class' => 'user', 'parameters' => $getCode],
            'saveNewPassword' => ['class' => 'user', 'parameters' => []],];

        if (array_key_exists($getParameter, $routes)) {
            return $routes[$getParameter];
        }

        return null;
    }

    /**
     * Get the route with rights
     *
     * @param string $getParameter
     *
     * @return array|null
     */
    private function getRouteWithRights(string $getParameter)
    {
        $getPost = filter_input(INPUT_GET, 'post');
        $getComment = filter_input(INPUT_GET, 'comment');
        $getUser = filter_input(INPUT_GET, 'user');

        $routes = ['viewBackoffice' => ['class' => 'home', 'parameters' => []],
            'backofficePostsList' => ['class' => 'post', 'parameters' => []],
            'newPost' => ['class' => 'post', 'parameters' => []],
            'savePost' => ['class' => 'post', 'parameters' => []],
            'editPost' => ['class' => 'post', 'parameters' => $getPost],
            'updatePost' => ['class' => 'post', 'parameters' => $getPost],
            'deletePost' => ['class' => 'post', 'parameters' => $getPost],
            'saveComment' => ['class' => 'comment', 'parameters' => $getPost],
            'backofficeCommentsList' => ['class' => 'comment', 'parameters' => []],
            'validComment' => ['class' => 'comment', 'parameters' => $getComment],
            'deleteComment' => ['class' => 'comment', 'parameters' => $getComment],
            'backofficeUsersList' => ['class' => 'user', 'parameters' => []],
            'deleteUser' => ['class' => 'user', 'parameters' => $getUser],
            'editUser' => ['class' => 'user', 'parameters' => $getUser],
            'updateUser' => ['class' => 'user', 'parameters' => $getUser],
            'newUser' => ['class' => 'user', 'parameters' => []],
            'saveUser' => ['class' => 'user', 'parameters' => []],
            'validUser' => ['class' => 'user', 'parameters' => $getUser],];

        if (array_key_exists($getParameter, $routes)) {
            return $routes[$getParameter];
        }

        return null;
    }

    /**
     * Return the controller
     *
     * @param string $class
     *
     * @return string|null
     */
    private function getController(string $class)
    {
        if (!class_exists('OpenclassroomsP5\Controllers\\' . $class . 'Controller')) {
            return null;
        }

        return $class . 'Controller';
    }
}

