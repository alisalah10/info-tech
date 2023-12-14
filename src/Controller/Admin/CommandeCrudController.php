<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('description'),
            IntegerField::new('quantite'),
            NumberField::new('prix'),
            TextField::new('auteur'),
            TextField::new('type'),
            DateField::new('dateEdition'),
            ImageField::new('photo')
                ->setBasePath('livre/')
                ->setUploadDir('public/livre')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }*/
}
