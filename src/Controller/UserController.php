<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/membre")
 */
class UserController extends  AbstractController
{
    /**
     * Page d'Inscription
     * @Route("/inscription.html", name="user_register", methods={"GET|POST"})
     * @param Request $request
     */
    public function register(Request $request)
    {
        # Creation d'un nouveau user
        $user = new User();
        $user->setRegistrationDate(new \DateTimeImmutable());
        $user->setRoles(['ROLE_USER']);

        # Creation d'un formulaire
        $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre prenom'
                ]
            ])

            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])

            ->add('email', EmailType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre email'
                ]
            ])

            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Votre mot de passe'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider l\'inscription'

            ])

            ->getForm();
        $form->handleRequest($request);

        # Si le formulaire est soumi et si il est valide
        if ( $form->isSubmitted() && $form->isValid() ) {

            # Enregistrement dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # Notification
            $this->addFlash('notice', 'Inscription réussi !');

            # Redirection
            return $this->redirectToRoute('user_login');        }



        return $this->render('user/inscription.html.twig',[
            'form' => $form ->createView()
        ]);
    }
   

    /**
     * Page de Connexion
     * * @Route("/connexion.html", name="user_login", methods={"GET|POST"})
     */
    public function login()
    {
        return new Response("<h1>PAGE CONNEXION</h1>");
    }
}