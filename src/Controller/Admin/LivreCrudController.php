<?php

namespace App\Controller\Admin;

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Livre;

use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class LivreCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $user = $this->security->getUser();

        $nomField = TextField::new('nom');
        $descriptionField = TextField::new('description');
        $quantiteField = IntegerField::new('quantite');
        $utilisateurField = AssociationField::new('utilisateur')
            ->setFormTypeOption('class', User::class)
            ->setFormTypeOption('choice_label', 'username')
            ->setFormTypeOption('data', $user)
            ->setFormTypeOption('empty_data', $user);
        $prixField = NumberField::new('prix');
        $auteurField = TextField::new('auteur');
        $typeField = TextField::new('type');
        $dateEditionField = DateField::new('dateEdition');
        $photo = ImageField::new('photo')
            ->setBasePath('livre/')
            ->setUploadDir('public/livre')
            ->setUploadedFileNamePattern('[randomhash].[extension]');

        return [
            $nomField,
            $descriptionField,
            $quantiteField,
            $utilisateurField,
            $prixField,
            $auteurField,
            $typeField,
            $dateEditionField,
            $photo,
        ];
    }
}