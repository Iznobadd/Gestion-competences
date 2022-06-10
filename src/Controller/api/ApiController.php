<?php

namespace App\Controller\api;

use App\Repository\UserRepository;
use App\Repository\SkillRepository;
use App\Repository\ExperiencesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/api/index.html.twig');
    }
    #[Route('/api/info_user', name: 'api_user')]
    public function info_user(UserRepository $userRepository, ExperiencesRepository $experiencesRepository, SkillRepository $skillRepository): Response
    {
        if($this->getUser())
        {
            $identifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $identifier]);
            $exps = $experiencesRepository->findBy(['user' => $user]);

            return $this->json(
                $user, 200, [], ['groups' => 'info_user'],
            );
        }
        return $this->json(
            'non connecté'
        );
    }
    #[Route('/api/collab_list', name: 'api_collab_list')]
    public function collabList(UserRepository $userRepository): Response
    {
        $collab = $userRepository->findBy(['is_collab' => true]);
        $candidate = $userRepository->findByRole('ROLE_USER');
        return $this->json(
            $collab, 200, [], ['groups' => 'collab_list'],
            // $candidate, 200, [], ['groups' => 'collab_list'],
        );
    }
    // #[Route('/api/candidate_list', name: 'api_candidate_list')]
    // public function candidateList(UserRepository $userRepository): Response
    // {
    //     $users = $userRepository->findByRole('ROLE_USER');
    //     return $this->json(
    //         $users, 200, [], ['groups' => 'collab_list'],
    //     );
    // }
}