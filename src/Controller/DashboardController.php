<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Repository\ExperiencesRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(UserRepository $userRepository, ExperiencesRepository $experiencesRepository, SkillRepository $skillRepository): Response
    {
        if($this->getUser())
        {
            $identifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $identifier]);
            $exps = $experiencesRepository->findBy(['user' => $user]);

            return $this->render('dashboard/profile.html.twig', compact('user', 'exps'));
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/profile/add/exp', name: 'app_exp_add')]
    public function addExp(UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        if($this->getUser())
        {
            $identifier = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $identifier]);
            $exp = new Experience();
            $form = $this->createFormBuilder($exp)
                ->add('jobName', TextType::class)
                ->add('startedAt', DateTimeType::class, [
                    'input' => 'datetime_immutable',
                ])
                ->add('endAt', DateTimeType::class, [
                    'input' => 'datetime_immutable',
                ])
                ->add('description', TextareaType::class)
                ->getForm();

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $exp->setUser($user);
                $em->persist($exp);
                $em->flush();
                return $this->redirectToRoute('app_profile');
            }

            return $this->render('dashboard/add_profile_exp.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
}
