<?php


namespace App\Controller;


use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class WorkflowController extends AbstractController
{

    /**
     * @Route("/workflow/{status}/{id}", name="workflow_transition", methods={"GET"})
     * @IsGranted("ROLE_AUTHOR")
     * @param $status
     * @param Article $article
     * @param Registry $registry
     * @param Request $request
     * @return RedirectResponse
     */
    public function transition($status, Article $article, Registry $registry, Request $request)
    {
        # Récupération du workflow
        $workflow = $registry->get($article, 'article_publishing');

        try {

            # Changement de status
            $workflow->apply($article, $status);

            # Insertion dans la BDD
            $this->getDoctrine()->getManager()->flush();

            # Notification
            $this->addFlash('success', 'Votre article a bien été transmis. Merci.');

        } catch (LogicException $e) {

            # Transition non autorisé
            $this->addFlash('danger', 'Changement de statut impossible.');

        }

        # Autoriser la publication d'un article
        if ($workflow->can($article, 'to_be_published')) {

            $workflow->apply($article, 'to_be_published');
            $this->getDoctrine()->getManager()->flush();

        }

        # Récupération du redirect
        $redirect = $request->get('redirect') ?? 'default_home';

        return $this->redirectToRoute($redirect);

    }

}