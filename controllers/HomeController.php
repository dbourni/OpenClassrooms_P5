<?php
/**
 * Homepage controller
 */

namespace OpenclassroomsP5\Controllers;

use OpenclassroomsP5\Models\CommentManager;
use OpenclassroomsP5\Models\PostManager;
use OpenclassroomsP5\Models\UserManager;

/**
 * Class HomeController
 *
 * @package dbourni\OpenclassroomsP5
 */
class HomeController extends Controller
{
    /**
     * Displays the homepage
     *
     * @return bool
     */
    public function viewHome()
    {
        return $this->render('home.html.twig', []);
    }

    /**
     * Dispay the back office dashboard
     *
     * @return bool
     */
    public function viewBackoffice()
    {
        return $this->render('backoffice.html.twig', [
            'nbArticle' => (new PostManager())->countPosts(),
            'nbComments' => (new CommentManager())->countComments(),
            'nbUsers' => (new UserManager())->countUsers(),
        ]);
    }

    /**
     * Send an email with the form
     *
     * @param array $paramForEmail
     *
     * @return bool
     */
    public function sendEmail(array $paramForEmail)
    {
        try {
            mail($paramForEmail[0], 'Email envoyé par le formulaire de votre site de ' . $paramForEmail[1], $paramForEmail[2]);
        } catch (\Exception $e) {
            $this->displayError($e);
            $this->setErrorMessage('Votre email n\'a pas pu être envoyé.');
            return false;
        }

        return $this->render('sentemail.html.twig', []);
    }

    /**
     * Display the connection form
     *
     * @return bool
     */
    public function viewConnect()
    {
        return $this->render('connect.html.twig', []);
    }
}
