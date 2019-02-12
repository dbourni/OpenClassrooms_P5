<?php
/**
 * Homepage controller
 */

namespace dbourni\OpenclassroomsP5;

class HomeController extends Controller
{
    /**
     * Displays the homepage
     */
    public function viewHome()
    {
        $this->render('home.html.twig', [
            'title' => 'Blog de David - Accueil',
        ]);
    }

    /**
     * Dispay the back office dashboard
     */
    public function viewBackoffice()
    {
        $postManager = new PostManager();
        $nbPosts = $postManager->countPosts();

        $this->render('backoffice.html.twig', [
            'title' => 'Blog de David - Back-Office',
            'nbArticle' => $nbPosts,
        ]);
    }

    /**
     * Send an email with the form
     * @param string $to
     * @param string $name
     * @param string $message
     */
    public function sendEmail(string $to, string $name, string $message)
    {
        try {
            $object = 'Email envoyé par le formulaire de votre site de ' . $name;
            mail($to, $object, $message);
            
            echo $this->twig->render('sentemail.html.twig', [
                'title' => 'Blog de David - Email envoyé'
            ]);
        } catch (\Exception $e) {
            $this->displayError($e);
        }
    }
}
