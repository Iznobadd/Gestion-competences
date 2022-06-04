<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Repository\ExperiencesRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(ExperiencesRepository $experiencesRepository): Response
    {
        $user = $this->getUser();
        if($user)
        {
            $exps = $experiencesRepository->findBy(['user' => $user]);

            return $this->render('dashboard/profile.html.twig', compact('user', 'exps'));
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/profile/add/exp', name: 'app_add_exp')]
    public function addExp(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if($user)
        {
            $exp = new Experience();
            $form = $this->createFormBuilder($exp)
                ->add('jobName', TextType::class)
                ->add('startedAt', DateType::class, [
                    'input' => 'datetime_immutable',
                ])
                ->add('endAt', DateType::class, [
                    'input' => 'datetime_immutable'
                ])
                ->add('notEnd', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'Still in progress',
                    'required' => false,
                ])
                ->add('description', TextareaType::class)
                ->getForm();


            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                if(!$form->get('notEnd')->getData())
                {
                    $exp->setEndAt(NULL);
                }
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

    #[Route('/profile/add/skill', name: 'app_add_skill')]
    public function addSkill(SkillRepository $skillRepository): Response
    {
        $user = $this->getUser();
        if($user)
        {
            $skill = $skillRepository->findAll();
            return $this->render('dashboard/add_profile_skill.html.twig', compact('skill'));
        }
        return $this->redirectToRoute('app_login');
    }
}
