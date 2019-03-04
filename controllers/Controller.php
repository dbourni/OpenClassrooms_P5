<?php
/**
 * Main controller
 */

namespace OpenclassroomsP5\Controllers;

/**
 * Class Controller
 *
 * @package dbourni\OpenclassroomsP5
 */
abstract class Controller
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * Instantiates the Twig attribute
     * Controller constructor.
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem('.\templates');
        $this->twig = new \Twig_Environment($loader, [
            'cache' => false, //'.\tmp'
            'auto_reload' => true,
        ]);
        $this->twig->addGlobal('session', $_SESSION);
    }

    /**
     * Render the twig file with the parameters
     *
     * @param string $twigFile
     *
     * @param array $parameters
     */
    public function render(string $twigFile, array $parameters)
    {
        try {
            echo $this->twig->render($twigFile, $parameters);
        } catch (\Exception $e) {
            $this->displayError('Cette page n\'existe pas...');
        }
    }

    /**
     * Display an error
     *
     * @param string $e
     */
    public function displayError(string $e)
    {
        $this->render('error.html.twig', [
            'message' => $e,
        ]);
    }

    /**
     * Checks the user rights
     *
     * @param string $path
     *
     * @return bool
     */
    public function checkRights(string $path)
    {
        $readerRights = ['saveComment'];
        $editorRights = array_merge($readerRights, ['backoffice', 'backofficePostsList', 'newPost', 'savePost', 'editPost',
            'updatePost', 'deletePost']);
        $adminRights = array_merge($editorRights, ['backofficeCommentsList', 'validComment', 'deleteComment', 'backofficeUsersList',
            'deleteUser', 'editUser', 'updateUser', 'newUser', 'saveUser', 'validUser']);

        $right = false;

        switch ($_SESSION['role']) {
            case 'admin':
                if (in_array($path, $adminRights)) {
                    $right = true;
                }
                break;
            case 'editor':
                if (in_array($path, $editorRights)) {
                    $right = true;
                }
                break;
            case 'reader':
                if (in_array($path, $readerRights)) {
                    $right = true;
                }
                break;
            default:
                $right = FALSE;
                break;
        }

        return $right;
    }
}
