<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
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

            # Encodage du Mot de Passe
            $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            # Enregistrement dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # Notification
            $this->addFlash('notice', 'Inscription réussi !');

            # Redirection
            return $this->redirectToRoute('security_login');        }



        return $this->render('user/inscription.html.twig',[
            'form' => $form ->createView()
        ]);
    }
}