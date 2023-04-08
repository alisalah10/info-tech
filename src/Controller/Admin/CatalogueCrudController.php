<?php

namespace App\Controller\Admin;

use App\Entity\Catalogue;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


class CatalogueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Catalogue::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('description'),
            ImageField::new('icon')
                ->setBasePath('catalogue/')
                ->setUploadDir('public/catalogue')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
}
