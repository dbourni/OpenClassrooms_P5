<?php

namespace dbourni\OpenclassroomsP5;

use dbourni\OpenclassroomsP5\Controller;
use dbourni\OpenclassroomsP5\ErrorController;

/**
 * Homepage controller
 */
class HomeController extends Controller
{
    /**
     * Displays the homepage
     */
    public function viewHome() 
    {
        echo $this->twig->render('home.html.twig', [
            'title' => 'Blog de David - Accueil'
        ]);
    }

    /**
     * Send an email with the form
     *
     * @param  string $to
     * @param  string $name
     * @param  string $message
     */
    public function sendEmail(string $to, string $name, string $message) 
    {
        try {
            $object = 'Email envoyé par le formulaire de votre site de ' . $name;
            mail($to, $object, $message);
            
            echo $this->twig->render('sentemail.html.twig', [
                'title' => 'Blog de David - Email envoyé'
            ]);
        } catch (Exception $e) {
            $errorController = new ErrorController();
            $errorController->emailNotSent($e->getMessage());
        }
    }
}
