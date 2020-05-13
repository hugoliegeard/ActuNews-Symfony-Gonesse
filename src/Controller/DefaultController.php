<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * Générer le Menu Principal
     */
    public function navigation()
    {
        # Récupération des catégories
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        # Transmission au Menu
        return $this->render('components/_nav.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Page Contact
     * @Route("/contact.html", name="default_contact", methods={"POST|GET"})
     */
    public function contact(Request $request)
    {
        # Création d'une demande de contact
        $contact = new Contact();

        # Conception du formulaire
        $form = $this->createFormBuilder($contact)
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre description'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])
            ->add('telephone', TelType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->getForm();

        $form->handleRequest($request);

        #----------
        if ($form->isSubmitted() && $form->isValid()) {

            # Insertion dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            # Notification
            $this->addFlash('notice', 'Message envoye avec succes !');

            # Redirection
            return $this->redirectToRoute('default_contact');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/gestion-contact.html", name="default_gestion",   methods={"GET"})          )
     */
    public function message()
    {
        # Récupération des messages
        $messages = $this->getDoctrine()
            ->getRepository(Contact::class)
            ->findAll();

        # Transmission à la vue
        return  $this->render('default/gestion-contact.html.twig', [
            'messages' => $messages
        ]);
    }
}