<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'Accueil
     */
    public function home()
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * Page Categorie : Afficher les articles d'une Catégorie
     * @Route("/categorie/{alias}", name="default_category", methods={"GET"})
     */
    public function category($alias)
    {
        return $this->render('default/category.html.twig');
    }

    /**
     * Afficher un Article
     * @Route("/{category}/{alias}_{id}.html", name="default_article", methods={"GET"})
     */
    public function article($alias, $id, $category)
    {
        # Exemple URL :
        # http://localhost:8000/politique/le-deconfinement-est-annule_14564.html
        return $this->render('default/article.html.twig');
    }
}