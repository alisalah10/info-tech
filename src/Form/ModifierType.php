<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('quantite')
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'required' => true,
                'mapped' => false,
            ])
            ->add('prix')
            ->add('auteur')
            ->add('type')
            ->add('DateEdition', DateType::class, [
                'label' => 'Date d\'édition',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('modifier', SubmitType::class, [
                'label' => 'Modifier Livre',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}