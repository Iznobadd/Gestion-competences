<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('User Details'),
            TextField::new('firstName')->setColumns(6),
            TextField::new('lastName')->setColumns(6),
            EmailField::new('email')->setColumns(6),
            // TextField::new('password')->setFormType(PasswordType::class)->setColumns(6),

            FormField::addPanel('Associations'),
            AssociationField::new('skills')->setFormTypeOptions([
                'by_reference' => false
            ])->setColumns(6),
            AssociationField::new('mission')->setFormTypeOptions([
                'by_reference' => false
            ])->setColumns(6),

            FormField::addPanel(),
            BooleanField::new('status')->setColumns(3),

            FormField::addPanel('Choose your role'),
            ChoiceField::new('roles')->setChoices([
                'Admin' => 'ROLE_ADMIN',
                'Collaborator' => 'ROLE_COLLABORATOR',
                'SalesMan' => 'ROLE_SALE',
                'Candidate' => 'ROLE_USER'
            ])
                ->allowMultipleChoices(true),
            BooleanField::new('isAdmin')->setColumns(4),
            BooleanField::new('isCommercial')->setColumns(4),
            BooleanField::new('isCollab')->setColumns(4)
        ];
    }

}
