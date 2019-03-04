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

        if ($route) {
            try {
                $parameters = $route['parameters'];
                $this->{$route['class']}->{$route['function']}($parameters);
            } catch (\Throwable $throwable) {
                $this->homeController->displayError($throwable);
            }
            return;
        }

        $this->homeController->displayError('Action interdite ou page inexistante !!!');
    }

    /**
     * Get the route without rights
     *
     * @param string $getParameter
     *
     * @return string
     */
    private function getRouteWithoutRights(string $getParameter)
    {
        $getPost = filter_input(INPUT_GET, 'post');
        $getCode = filter_input(INPUT_GET, 'code');
        $postEmail = filter_input(INPUT_POST, 'email');
        $postName = filter_input(INPUT_POST, 'name');
        $postMessage = filter_input(INPUT_POST, 'message');

        $routes = array(
            ['param' => 'home', 'class' => 'homeController', 'function' => 'viewHome', 'parameters' => []],
            ['param' => 'sendemail', 'class' => 'homeController', 'function' => 'sendEmail', 'parameters' => [$postEmail, $postName, $postMessage]],
            ['param' => 'connection', 'class' => 'homeController', 'function' => 'viewConnect', 'parameters' => []],
            ['param' => 'disconnection', 'class' => 'userController', 'function' => 'disConnect', 'parameters' => []],
            ['param' => 'view', 'class' => 'postController', 'function' => 'viewPost', 'parameters' => $getPost],
            ['param' => 'list', 'class' => 'postController', 'function' => 'viewList', 'parameters' => []],
            ['param' => 'connect', 'class' => 'userController', 'function' => 'connect', 'parameters' => []],
            ['param' => 'subscription', 'class' => 'userController', 'function' => 'subscription', 'parameters' => []],
            ['param' => 'saveSubscription', 'class' => 'userController', 'function' => 'saveSubscription', 'parameters' => []],
            ['param' => 'forgottenPassword', 'class' => 'userController', 'function' => 'forgottenPassword', 'parameters' => []],
            ['param' => 'initPassword', 'class' => 'userController', 'function' => 'initPassword', 'parameters' => []],
            ['param' => 'modifyPassword', 'class' => 'userController', 'function' => 'modifyPassword', 'parameters' => $getCode],
            ['param' => 'saveNewPassword', 'class' => 'userController', 'function' => 'saveNewPassword', 'parameters' => []],
        );

        $route = array_search($getParameter, array_column($routes, 'param'));

        if ($route OR $route === 0) {
            return $routes[$route];
        }

        return null;
    }

    /**
     * Get the route with rights
     *
     * @param string $getParameter
     *
     * @return string
     */
    private function getRouteWithRights(string $getParameter)
    {
        $getPost = filter_input(INPUT_GET, 'post');
        $getComment = filter_input(INPUT_GET, 'comment');
        $getUser = filter_input(INPUT_GET, 'user');

        $routes = array(
            ['param' => 'backoffice', 'class' => 'homeController', 'function' => 'viewBackoffice', 'parameters' => []],
            ['param' => 'backofficePostsList', 'class' => 'postController', 'function' => 'backofficePostsList', 'parameters' => []],
            ['param' => 'newPost', 'class' => 'postController', 'function' => 'newPost', 'parameters' => []],
            ['param' => 'savePost', 'class' => 'postController', 'function' => 'savePost', 'parameters' => []],
            ['param' => 'editPost', 'class' => 'postController', 'function' => 'editPost', 'parameters' => $getPost],
            ['param' => 'updatePost', 'class' => 'postController', 'function' => 'updatePost', 'parameters' => $getPost],
            ['param' => 'deletePost', 'class' => 'postController', 'function' => 'deletePost', 'parameters' => $getPost],
            ['param' => 'saveComment', 'class' => 'commentController', 'function' => 'saveComment', 'parameters' => $getPost],
            ['param' => 'backofficeCommentsList', 'class' => 'commentController', 'function' => 'backofficeCommentsList', 'parameters' => []],
            ['param' => 'validComment', 'class' => 'commentController', 'function' => 'validComment', 'parameters' => $getComment],
            ['param' => 'deleteComment', 'class' => 'commentController', 'function' => 'deleteComment', 'parameters' => $getComment],
            ['param' => 'backofficeUsersList', 'class' => 'userController', 'function' => 'backofficeUsersList', 'parameters' => []],
            ['param' => 'deleteUser', 'class' => 'userController', 'function' => 'deleteUser', 'parameters' => $getUser],
            ['param' => 'editUser', 'class' => 'userController', 'function' => 'editUser', 'parameters' => $getUser],
            ['param' => 'updateUser', 'class' => 'userController', 'function' => 'updateUser', 'parameters' => $getUser],
            ['param' => 'newUser', 'class' => 'userController', 'function' => 'newUser', 'parameters' => []],
            ['param' => 'saveUser', 'class' => 'userController', 'function' => 'saveUser', 'parameters' => []],
            ['param' => 'validUser', 'class' => 'userController', 'function' => 'validUser', 'parameters' => $getUser],
        );

        $route = array_search($getParameter, array_column($routes, 'param'));

        if ($route OR $route === 0) {
            return $routes[$route];
        }

        return null;
    }
}

