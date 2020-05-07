<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'Accueil
     */
    public function home()
    {

        # Récupération de tous les articles
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        # Transmission à la vue
        return $this->render('default/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Page Categorie : Afficher les articles d'une Catégorie
     * @Route("/categorie/{alias}", name="default_category", methods={"GET"})
     */
    public function category($alias)
    {
        # Récupération de la catégorie via son alias
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['alias' => $alias]);

        /*
         * Grâce à la relation entre Article et Categorie
         * (OneToMany), je suis en mesure de récupérer
         * les articles de la catégorie...
         */
        $articles = $category->getArticles();

        # Transmission à la vue
        return $this->render('default/category.html.twig', [
            'articles' => $articles,
            'category' => $category
        ]);
    }

    /**
     * Afficher un Article
     * @Route("/{category}/{alias}_{id}.html", name="default_article", methods={"GET"})
     */
    public function article(Article $article)
    {
        # Exemple URL :
        # http://localhost:8000/politique/le-deconfinement-est-annule_14564.html

        # Récupération de l'article
        #$article = $this->getDoctrine()
        #    ->getRepository(Article::class)
        #    ->find($id);

        # Transmission à la vue
        return $this->render('default/article.html.twig', [
            'article' => $article
        ]);
    }
}