<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('description'),
            IntegerField::new('quantite'),
            NumberField::new('prix'),
            AssociationField::new('catalogue')
                ->setFormType(EntityType::class)
                ->setFormTypeOptions([
                    'class' => \App\Entity\Catalogue::class,
                    'choice_label' => 'nom',
                ]),
            ImageField::new('photo')
                ->setBasePath('produit/')
                ->setUploadDir('public/produit')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
}
