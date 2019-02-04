<?php
/**
 * Main controller
 */

namespace dbourni\OpenclassroomsP5;

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
            'cache' => false //'.\tmp'    
        ]);
    }

    /**
     * Render the twig file with the parameters
     * @param string $twigFile
     * @param array $parameters
     */
    public function render(string $twigFile, array $parameters)
    {
        try {
            echo $this->twig->render($twigFile, $parameters);
        } catch (\Exception $e) {
            $this->displayError('Cette page n\'existe pas.');
        }
    }

    /**
     * Display an error
     * @param string $e
     */
    public function displayError(string $e)
    {
        $this->render('error.html.twig', [
            'message' => $e
        ]);
    }
}
