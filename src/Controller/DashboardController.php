<?php

namespace App\Controller;

use App\Entity\CardSkill;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

    #[Route('/profile/update/skill/{id}', name: 'app_update_skill')]
    public function updateSkill(Skill $skill, Request $request, EntityManagerInterface $em): Response
    {
        if(empty($skill->getCardSkills()->getValues())) {
            $card = new CardSkill();
        }
        else
        {
            $card = $skill->getCardSkills()->getValues()[0];
        }

        $form = $this->createFormBuilder($card)
            ->add('love', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true
                ]
            ])
            ->add('stars', ChoiceType::class, [
                'expanded' => true,
                'label_html' => true,
                'choices' => [
                    '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px;" xml:space="preserve">
                        <path style="fill:#FF6647;" d="M474.655,74.503C449.169,45.72,413.943,29.87,375.467,29.87c-30.225,0-58.5,12.299-81.767,35.566  c-15.522,15.523-28.33,35.26-37.699,57.931c-9.371-22.671-22.177-42.407-37.699-57.931c-23.267-23.267-51.542-35.566-81.767-35.566  c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936c0,44.458,13.452,88.335,39.981,130.418  c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146c2.203,0.988,4.779,0.988,6.981,0  c2.57-1.151,63.637-28.798,125.683-80.146c36.618-30.304,65.836-62.565,86.845-95.889C498.548,263.271,512,219.394,512,174.936  C512,137.911,498.388,101.305,474.655,74.503z"/>
                        <path style="fill:#E35336;" d="M160.959,401.243c-36.618-30.304-65.836-62.565-86.845-95.889  c-26.529-42.083-39.981-85.961-39.981-130.418c0-37.025,13.612-73.631,37.345-100.433c21.44-24.213,49.775-39.271,81.138-43.443  c-5.286-0.786-10.653-1.189-16.082-1.189c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936  c0,44.458,13.452,88.335,39.981,130.418c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146  c2.203,0.988,4.779,0.988,6.981,0c0.689-0.308,5.586-2.524,13.577-6.588C251.254,463.709,206.371,438.825,160.959,401.243z"/>
                        </svg>' => 1,
                    '<svg id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px;" xml:space="preserve">
                        <path style="fill:#FF6647;" d="M474.655,74.503C449.169,45.72,413.943,29.87,375.467,29.87c-30.225,0-58.5,12.299-81.767,35.566  c-15.522,15.523-28.33,35.26-37.699,57.931c-9.371-22.671-22.177-42.407-37.699-57.931c-23.267-23.267-51.542-35.566-81.767-35.566  c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936c0,44.458,13.452,88.335,39.981,130.418  c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146c2.203,0.988,4.779,0.988,6.981,0  c2.57-1.151,63.637-28.798,125.683-80.146c36.618-30.304,65.836-62.565,86.845-95.889C498.548,263.271,512,219.394,512,174.936  C512,137.911,498.388,101.305,474.655,74.503z"/>
                        <path style="fill:#E35336;" d="M160.959,401.243c-36.618-30.304-65.836-62.565-86.845-95.889  c-26.529-42.083-39.981-85.961-39.981-130.418c0-37.025,13.612-73.631,37.345-100.433c21.44-24.213,49.775-39.271,81.138-43.443  c-5.286-0.786-10.653-1.189-16.082-1.189c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936  c0,44.458,13.452,88.335,39.981,130.418c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146  c2.203,0.988,4.779,0.988,6.981,0c0.689-0.308,5.586-2.524,13.577-6.588C251.254,463.709,206.371,438.825,160.959,401.243z"/>
                        </svg>' => 2,
                    '<svg id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px;" xml:space="preserve">
                        <path style="fill:#FF6647;" d="M474.655,74.503C449.169,45.72,413.943,29.87,375.467,29.87c-30.225,0-58.5,12.299-81.767,35.566  c-15.522,15.523-28.33,35.26-37.699,57.931c-9.371-22.671-22.177-42.407-37.699-57.931c-23.267-23.267-51.542-35.566-81.767-35.566  c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936c0,44.458,13.452,88.335,39.981,130.418  c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146c2.203,0.988,4.779,0.988,6.981,0  c2.57-1.151,63.637-28.798,125.683-80.146c36.618-30.304,65.836-62.565,86.845-95.889C498.548,263.271,512,219.394,512,174.936  C512,137.911,498.388,101.305,474.655,74.503z"/>
                        <path style="fill:#E35336;" d="M160.959,401.243c-36.618-30.304-65.836-62.565-86.845-95.889  c-26.529-42.083-39.981-85.961-39.981-130.418c0-37.025,13.612-73.631,37.345-100.433c21.44-24.213,49.775-39.271,81.138-43.443  c-5.286-0.786-10.653-1.189-16.082-1.189c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936  c0,44.458,13.452,88.335,39.981,130.418c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146  c2.203,0.988,4.779,0.988,6.981,0c0.689-0.308,5.586-2.524,13.577-6.588C251.254,463.709,206.371,438.825,160.959,401.243z"/>
                        </svg>' => 3,
                    '<svg id="Layer_4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px;" xml:space="preserve">
                        <path style="fill:#FF6647;" d="M474.655,74.503C449.169,45.72,413.943,29.87,375.467,29.87c-30.225,0-58.5,12.299-81.767,35.566  c-15.522,15.523-28.33,35.26-37.699,57.931c-9.371-22.671-22.177-42.407-37.699-57.931c-23.267-23.267-51.542-35.566-81.767-35.566  c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936c0,44.458,13.452,88.335,39.981,130.418  c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146c2.203,0.988,4.779,0.988,6.981,0  c2.57-1.151,63.637-28.798,125.683-80.146c36.618-30.304,65.836-62.565,86.845-95.889C498.548,263.271,512,219.394,512,174.936  C512,137.911,498.388,101.305,474.655,74.503z"/>
                        <path style="fill:#E35336;" d="M160.959,401.243c-36.618-30.304-65.836-62.565-86.845-95.889  c-26.529-42.083-39.981-85.961-39.981-130.418c0-37.025,13.612-73.631,37.345-100.433c21.44-24.213,49.775-39.271,81.138-43.443  c-5.286-0.786-10.653-1.189-16.082-1.189c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936  c0,44.458,13.452,88.335,39.981,130.418c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146  c2.203,0.988,4.779,0.988,6.981,0c0.689-0.308,5.586-2.524,13.577-6.588C251.254,463.709,206.371,438.825,160.959,401.243z"/>
                        </svg>' => 4,
                    '<svg id="Layer_5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px;" xml:space="preserve">
                        <path style="fill:#FF6647;" d="M474.655,74.503C449.169,45.72,413.943,29.87,375.467,29.87c-30.225,0-58.5,12.299-81.767,35.566  c-15.522,15.523-28.33,35.26-37.699,57.931c-9.371-22.671-22.177-42.407-37.699-57.931c-23.267-23.267-51.542-35.566-81.767-35.566  c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936c0,44.458,13.452,88.335,39.981,130.418  c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146c2.203,0.988,4.779,0.988,6.981,0  c2.57-1.151,63.637-28.798,125.683-80.146c36.618-30.304,65.836-62.565,86.845-95.889C498.548,263.271,512,219.394,512,174.936  C512,137.911,498.388,101.305,474.655,74.503z"/>
                        <path style="fill:#E35336;" d="M160.959,401.243c-36.618-30.304-65.836-62.565-86.845-95.889  c-26.529-42.083-39.981-85.961-39.981-130.418c0-37.025,13.612-73.631,37.345-100.433c21.44-24.213,49.775-39.271,81.138-43.443  c-5.286-0.786-10.653-1.189-16.082-1.189c-38.477,0-73.702,15.851-99.188,44.634C13.612,101.305,0,137.911,0,174.936  c0,44.458,13.452,88.335,39.981,130.418c21.009,33.324,50.227,65.585,86.845,95.889c62.046,51.348,123.114,78.995,125.683,80.146  c2.203,0.988,4.779,0.988,6.981,0c0.689-0.308,5.586-2.524,13.577-6.588C251.254,463.709,206.371,438.825,160.959,401.243z"/>
                        </svg>' => 5,
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $card->setSkill($skill);
            $card->setUser($this->getUser());
            $em->persist($card);
            $em->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('dashboard/update_profile_skill.html.twig', [
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

    #[Route('/candidate_list', name: 'app_candidate_list')]
    #[IsGranted('ROLE_SALE')]
    public function candidateList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findByRole('ROLE_USER');
        return $this->render('dashboard/candidate_list.html.twig', compact('users'));
    }

    #[Route('/transform/{id}', name: 'app_transform_to')]
    #[IsGranted('ROLE_SALE')]
    public function transformCandidate(User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $profile = $userRepository->findOneBy(['id' => $user]);
        $profile->setIsCollab(true);
        $profile->setRoles(['ROLE_COLLAB']);
        $em->persist($profile);
        $em->flush();
        return $this->redirectToRoute('app_candidate_list');
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
