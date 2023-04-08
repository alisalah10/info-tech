<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = array_combine(User::ROLES, User::ROLES);

        return [
            TextField::new('nom')->setRequired(true),
            TextField::new('prenom')->setRequired(true),
            TextField::new('email')->setRequired(true),
            IntegerField::new('phone')->setRequired(true)->setFormTypeOption('constraints', [new Length(['min' => 8, 'max' => 8])]),
            TextField::new('address')->setRequired(true),
            ChoiceField::new('roles')
                ->setChoices($roles)
                ->allowMultipleChoices()
                ->setRequired(true),
        ];
    }
}
