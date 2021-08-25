<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController extends AbstractController {
    protected $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * @Route("/hello/{prenom?World}", name="hello")
     */
    public function hello($prenom) 
    {   
        return $this->render('hello.html.twig', [
            'prenom' => $prenom,
            'title' => 'Hello'
        ]);
    }

    /**
     * @Route("/exemple", name="exemple")
     *
     */
    public function exemple() {
        return $this->render('exemple.html.twig', [
            'age' => 33
        ]);
    }

}