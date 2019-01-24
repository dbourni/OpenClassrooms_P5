<?php

namespace dbourni\OpenclassroomsP5;

/**
 * Main controller
 */
class Controller
{
    /**@var Twig_Environment */
    protected $twig;
    
    /**
     * Instantiates the Twig attribute
     *
     * @return void
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem('.\templates');
        $this->twig = new \Twig_Environment($loader, [
            'cache' => false //'.\tmp'    
        ]);
    }
}
