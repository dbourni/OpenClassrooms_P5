<?php

namespace dbourni\OpenclassroomsP5;

use dbourni\OpenclassroomsP5\Controller;

/**
 * Controller for application errors
 */
class ErrorController extends Controller
{
    /**
     * Displays the "Page not found" page
     *
     * @param  mixed $page
     *
     * @return void
     */
    public function pageNotFound($page) {
        echo $this->twig->render('error.html.twig', [
            'pageName' => $page
        ]);
    }
}
