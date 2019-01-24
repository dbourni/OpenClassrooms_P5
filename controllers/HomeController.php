<?php

require_once("Controller.php");

class HomeController extends Controller
{
    public function viewHome() {
        echo $this->twig->render('home.html.twig', [
            'variable' => 'Twig'
        ]);
    }
}
