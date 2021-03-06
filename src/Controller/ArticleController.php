<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class ArticleController extends AbstractController
{

    # Chargement du Helper Trait
    use HelperTrait;

    /**
     * Démonstration de l'ajout d'un Article
     * @Route("/demo/article", name="article_demo", methods={"GET"})
     */
    public function demo()
    {
        # Création d'une Catégorie
        $category = new Category();
        $category->setName('Sciences');
        $category->setAlias('sciences');

        # Création d'un Journaliste ( User )
        $user = new User();
        $user->setFirstname('Anthony');
        $user->setLastname('DRAPIER');
        $user->setEmail('anthony@actunews.com');
        $user->setPassword('1234');
        $user->setRoles(['ROLE_AUTHOR']);
        $user->setRegistrationDate(new \DateTimeImmutable());

        # Création d'un Article
        $article = new Article();
        $article->setTitle("Applications contre le Covid-19 : une tribune cosignée par Marlène Schiappa appelle à ne pas \"cautionner des discriminations sous couvert d’innovation\"");
        $article->setAlias("applications-contre-le-covid-19-une-tribune-cosignee-par-marlene-schiappa-appelle-a-ne-pas-cautionner-des-discriminations-sous-couvert-dinnovation");
        $article->setContent("<p>Les auteurs de la tribune, dont la secrétaire d'Etat, estiment qu'\"il y a un danger manifeste pour les libertés fondamentales\" avec les applications pour lutter contre le coronavirus.</p>");
        $article->setImage("21463399.jpg");
        $article->setCreatedDate(new \DateTimeImmutable());
        $article->setCategory($category);
        $article->setUser($user);

        /*
         * Récupération du Manager de Doctrine
         * -------------------------------------
         * Le EntityManager est une classe qui sait
         * comment persister d'autres classes.
         * (Effectuer des opérations C.R.U.D)
         */
        $em = $this->getDoctrine()->getManager();

        # On précise ce que l'on souhaite sauvegarder
        $em->persist($category);
        $em->persist($user);
        $em->persist($article);

        $em->flush(); // Déclencher l'execution par Doctrine

        # Retourne une réponse
        return new Response("Nouvel Article : " . $article->getTitle());
    }

    /**
     * @Route("/rediger-un-article.html",
     *     name="article_new",
     *     methods={"GET|POST"})
     * @IsGranted("ROLE_AUTHOR",
     *     message="Vous n'avez pas les permissions nécessaires.")
     * @param Request $request
     * @param Registry $registry
     * @return Response
     */
    public function addArticle(Request $request, Registry $registry)
    {
        # Création d'un nouvel article
        $article = new Article();

        # Récupération du Workflow
        $workflow = $registry->get($article);
        # dd($workflow->getEnabledTransitions($article));

        # Je récupère un user dans la base
        $article->setUser($this->getUser());
        $article->setCreatedDate(new \DateTimeImmutable());

        # Création du Formulaire
        $form = $this->createFormBuilder($article)
            # Titre
            ->add('title', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => "Titre de l'Article..."
                ]
            ])
            # Categorie
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => false,
            ])
            # Contenu
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => false,
            ])
            # Image d'illustration
            ->add('image', FileType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'dropify'
                ]
            ])
            # Bouton Submit
            ->add('submit', SubmitType::class, [
                'label' => 'Publier mon Article'
            ])
            ->getForm();

        # Permet a SF de gérer les données reçues.
        $form->handleRequest($request);

        # Si le formulaire est soumis et que les informations sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            # Upload de l'image
            # TODO : Mettre en place l'upload de l'image en s'appuyant sur la doc.
            # https://symfony.com/doc/current/controller/upload_file.html

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugify($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setImage($newFilename);
            }

            # -------------------------------- FIN UPLOAD

            # Création de l'Alias de l'Article
            $article->setAlias(
                $this->slugify(
                    $article->getTitle()
                )
            );

            try {

                # Appliquer le Workflow
                $workflow->apply($article, 'to_review');

                # Sauvegarde en BDD
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                # Notification
                $this->addFlash('notice',
                    'Félicitation, votre article est en ligne !');

                # Redirection
                return $this->redirectToRoute('default_article', [
                    'category' => $article->getCategory()->getAlias(),
                    'alias' => $article->getAlias(),
                    'id' => $article->getId()
                ]);

            } catch (LogicException $e) {

                # Transition non autorisé...
                $this->addFlash('danger',
                    "Révision impossible, contactez le rédacteur.");

            }
        }

        # On passe le formulaire à la vue
        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de lister les articles d'un auteur en attente
     * @Route("/mes-articles/en-attente", name="article_awaiting", methods={"GET"})
     * @IsGranted("ROLE_AUTHOR")
     */
    public function awaitingArticles()
    {
        # Récupération de l'auteur en session
        $author = $this->getUser();

        # Récupérer les articles de l'auteur en "review"
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findByAuthorAndStatus($author->getId(), 'review');

        return $this->render('article/articles.html.twig', [
           'title' => 'Mes articles en attente',
           'articles' => $articles
        ]);

    }

    /**
     * Permet de lister les articles d'un auteur en attente
     * @Route("/les-articles/en-attente-de-validation", name="article_to_approval", methods={"GET"})
     * @IsGranted("ROLE_EDITOR")
     */
    public function toApprovalArticles()
    {
        # Récupérer les articles de l'auteur en "review"
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findByStatus('editor');

        return $this->render('article/articles.html.twig', [
            'title' => 'Les articles en attente de validation',
            'articles' => $articles
        ]);

    }

    /**
     * Permet de lister les articles d'un auteur en attente
     * @Route("/les-articles/en-attente-de-correction", name="article_to_corrector", methods={"GET"})
     * @IsGranted("ROLE_CORRECTOR")
     */
    public function toCorrectorArticles()
    {
        # Récupérer les articles de l'auteur en "review"
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findByStatus('corrector');

        return $this->render('article/articles.html.twig', [
            'title' => 'Les articles en attente de correction',
            'articles' => $articles
        ]);

    }

    /**
     * Permet de lister les articles d'un auteur en attente
     * @Route("/les-articles/en-attente-de-publication", name="article_to_publication", methods={"GET"})
     * @IsGranted("ROLE_PUBLISHER")
     */
    public function toPublisherArticles()
    {
        # Récupérer les articles de l'auteur en "review"
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findByStatus('publisher');

        return $this->render('article/articles.html.twig', [
            'title' => 'Les articles en attente de publication',
            'articles' => $articles
        ]);

    }

}