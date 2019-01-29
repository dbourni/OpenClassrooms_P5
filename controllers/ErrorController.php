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
     * @param  string $page
     */
    public function pageNotFound(string $page) 
    {
        echo $this->twig->render('error.html.twig', [
            'message' => 'La page ' . $page . 'est inexistante'
        ]);
    }

    /**
     * Displays the "Email not sent" page
     *
     * @param  string $e
     */
    public function emailNotSent(string $e) 
    {
        echo $this->twig->render('error.html.twig', [
            'message' => 'Cet email n\'a pas pu être envoyé.'
        ]);
    }
}
