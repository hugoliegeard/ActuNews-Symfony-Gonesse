<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
{
    /**
     * @Route("/rediger-un-article.html",
     *     name="article_new",
     *     methods={"GET|POST"})
     * @return Response
     */
    public function addArticle()
    {
        return new Response("<h1>PAGE REDIGER UN ARTICLE</h1>");
    }
}