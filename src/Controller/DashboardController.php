<?php

namespace App\Controller;

use App\Repository\ExperiencesRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $userRepository, ExperiencesRepository $experiencesRepository, SkillRepository $skillRepository): Response
    {
        if($this->getUser())
        {
            $identifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $identifier]);
            $exps = $experiencesRepository->findBy(['user' => $user]);

            return $this->render('dashboard/profil.html.twig', compact('user', 'exps'));
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function indexVue(): Response
    {
        // Code à décommenter plus tard pour activer le login avant d'accéder au dashboard avec vuejs
        // if($this->getUser())
        // {
        //     return $this->render('basevue.html.twig');
        // }
        // return $this->redirectToRoute('app_login');

        return $this->render('basevue.html.twig');
    }
}
