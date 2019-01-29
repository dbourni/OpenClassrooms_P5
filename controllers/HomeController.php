<?php

namespace dbourni\OpenclassroomsP5;

use dbourni\OpenclassroomsP5\Controller;

/**
 * Homepage controller
 */
class HomeController extends Controller
{
    /**
     * Displays the homepage
     * 
     * @return void
     */
    public function viewHome() {
        echo $this->twig->render('home.html.twig', [
            'variable' => 'Twig'
        ]);
    }
}
