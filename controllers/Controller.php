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
        $loader = new \Twig_Loader_Filesystem('templates');
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
     * @param array $parameters
     *
     * @return bool
     */
    protected function render(string $twigFile, array $parameters)
    {
        try {
            echo $this->twig->render($twigFile, $parameters);
        } catch (\Exception $e) {
            $this->setErrorMessage('Hey, cette page est inéxistante !');
            return false;
        }

        return true;
    }

    /**
     * Display an error
     *
     * @param string $errorMessage
     */
    public function displayError(string $errorMessage)
    {
        $this->render('error.html.twig', [
            'message' => $errorMessage,
        ]);
    }

    /**
     * Set the error message displayed by the router
     *
     * @param string $errorMessage
     */
    protected function setErrorMessage(string $errorMessage)
    {
        $GLOBALS['errorMessage'] = $errorMessage;
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
        $editorRights = array_merge($readerRights, ['viewBackoffice', 'backofficePostsList', 'newPost', 'savePost', 'editPost',
            'updatePost', 'deletePost']);
        $adminRights = array_merge($editorRights, ['backofficeCommentsList', 'validComment', 'deleteComment', 'backofficeUsersList',
            'deleteUser', 'editUser', 'updateUser', 'newUser', 'saveUser', 'validUser']);

        $right = false;

        switch ($this->getSessionVariable('role')) {
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

    /**
     * Set the SESSION variable
     *
     * @param string $sessionVariable
     *
     * @param string $value
     */
    public function setSessionVariable(string $sessionVariable, string $value)
    {
        $_SESSION[$sessionVariable] = $value;
    }

    /**
     * Get the SESSION variable
     *
     * @param string $sessionVariable
     *
     * @return string|null
     */
    public function getSessionVariable(string $sessionVariable)
    {
        if (isset($_SESSION[$sessionVariable])) {
            return $_SESSION[$sessionVariable];
        }

        return null;
    }

    /**
     * Sanitize a string from a $_POST or $_GET
     *
     * @param string $type
     * @param string $stringVariable
     *
     * @return string
     */
    public function sanitizedString(string $type, string $stringVariable)
    {
        return filter_input(constant('INPUT_' . strtoupper($type)), $stringVariable, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    /**
     * Sanitize an email from a $_POST or $_GET
     *
     * @param string $type
     * @param string $emailVariable
     *
     * @return string
     */
    public function sanitizedEmail(string $type, string $emailVariable)
    {
        return filter_input(constant('INPUT_' . strtoupper($type)), $emailVariable, FILTER_SANITIZE_EMAIL);
    }
}
