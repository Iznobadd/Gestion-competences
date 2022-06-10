<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use ContainerMxb9QDX\getConsole_ErrorListenerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'app_redirect')]
    public function redirectToLogin(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getRoles() == ['ROLE_ADMIN'])
            {
                $user->setIsAdmin(true);
                $user->setIsCollab(false);
                $user->setIsCommercial(false);
            }
            else if($user->getRoles() == ['ROLE_SALE'])
            {
                $user->setIsAdmin(false);
                $user->setIsCollab(false);
                $user->setIsCommercial(true);
            }
            else {
                $user->setIsAdmin(false);
                $user->setIsCollab(true);
                $user->setIsCommercial(false);
            }


            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_profile');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

//    #[Route('/admin', name: 'admin')]
//    public function admin(EntityManagerInterface $em, UserRepository $userRepository): Response
//    {
//        $user = $this->getUser()->getUserIdentifier();
//        $use = $userRepository->findOneBy(['email' => $user]);
//        $use->setRoles(["ROLE_ADMIN"]);
//        $em->persist($use);
//        $em->flush();
//        return $this->redirectToRoute('app_profile');
//    }
}
