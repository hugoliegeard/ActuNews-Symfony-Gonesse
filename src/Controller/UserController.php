<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user")
 */
class UserController
{
    /**
     * Page d'Inscription
     * @Route("/register", name="user_register", methods={"GET|POST"})
     */
    public function register()
    {
        return new Response("<h1>PAGE INSCRIPTION</h1>");
    }

    /**
     * Page de Connexion
     * * @Route("/login", name="user_login", methods={"GET|POST"})
     */
    public function login()
    {
        return new Response("<h1>PAGE CONNEXION</h1>");
    }
}