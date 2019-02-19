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
     */
    public function viewHome()
    {
        $this->render('home.html.twig', []);
    }

    /**
     * Dispay the back office dashboard
     */
    public function viewBackoffice()
    {
        $nbPosts = (new PostManager())->countPosts();
        $nbComments = (new CommentManager())->countComments();
        $nbUsers = (new UserManager())->countUsers();

        $this->render('backoffice.html.twig', [
            'nbArticle' => $nbPosts,
            'nbComments' => $nbComments,
            'nbUsers' => $nbUsers,
        ]);
    }

    /**
     * Send an email with the form
     *
     * @param string $to
     * @param string $name
     * @param string $message
     */
    public function sendEmail(string $to, string $name, string $message)
    {
        try {
            $object = 'Email envoyé par le formulaire de votre site de ' . $name;
            mail($to, $object, $message);
            
            echo $this->twig->render('sentemail.html.twig', []);
        } catch (\Exception $e) {
            $this->displayError($e);
        }
    }

    /**
     * Display the connection form
     */
    public function viewConnect()
    {
        $this->render('connect.html.twig', []);
    }
}
