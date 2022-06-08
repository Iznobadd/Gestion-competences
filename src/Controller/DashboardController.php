<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Mission;
use App\Entity\Skill;
use App\Entity\User;
use App\Repository\ExperiencesRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_COLLAB')]
class DashboardController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(ExperiencesRepository $experiencesRepository): Response
    {
        $user = $this->getUser();
            $exps = $experiencesRepository->findBy(['user' => $user]);

            return $this->render('dashboard/profile.html.twig', compact('user', 'exps'));
    }

    #[Route('/profile/add/exp', name: 'app_add_exp')]
    public function addExp(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
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

    #[Route('/profile/add/skill', name: 'app_add_skill')]
    public function addSkill(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
            $form = $this->createFormBuilder($user)
                ->add('skills', EntityType::class, [
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'by_reference' => false
                ])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                return $this->redirectToRoute('app_profile');
            }

            return $this->render('dashboard/add_profile_skill.html.twig', [
                'form' => $form->createView()
            ]);
    }

    #[Route('/profile/add/mission', name: 'app_add_mission')]
    public function addMission(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $mission = new Mission();
        $form = $this->createFormBuilder($mission)
            ->add('jobName', TextType::class)
            ->add('startAt', DateType::class, [
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

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$form->get('notEnd')->getData())
            {
                $mission->setEndAt(NULL);
            }
            $mission->addUser($user);
            $em->persist($mission);
            $em->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('dashboard/add_profile_mission.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/collab_list', name: 'app_collab_list')]
    #[IsGranted('ROLE_SALE')]
    public function collabList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy(['is_collab' => true]);
        return $this->render('dashboard/collab_list.html.twig', compact('users'));
    }

    #[Route('/profile/{id}', name: 'app_collab_profile')]
    #[IsGranted('ROLE_SALE')]
    public function collabProfile(User $user, UserRepository $userRepository, ExperiencesRepository $experiencesRepository): Response
    {
        $profile = $userRepository->findOneBy(['id' => $user]);
        $exps = $experiencesRepository->findBy(['user' => $user]);
        return $this->render('dashboard/collab_profile.html.twig', [
            'user' => $profile,
            'exps' =>$exps
        ]);
    }

    #[Route('/', name: 'app_home')]
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
