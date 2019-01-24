<?php

class Controller
{
    protected $twig;
    
    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem('.\templates');
        $this->twig = new Twig_Environment($loader, [
            'cache' => false //'.\tmp'    
        ]);
    }
}
