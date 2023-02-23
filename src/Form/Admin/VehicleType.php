<?php

namespace App\Form\Admin;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'title', type: TextType::class, options: [
                'label' => "Titre du véhicule :",
                'attr' => [
                    'placeholder' => "Saisir le titre du véhicule",
                ]
            ])
            ->add(child: 'make', type: TextType::class, options: [
                'label' => "Marque du véhicule :",
                'attr' => [
                    'placeholder' => "Saisir la marque du véhicule",
                ]
            ])
            ->add(child: 'model', type: TextType::class, options: [
                'label' => "Modèle du véhicule :",
                'attr' => [
                    'placeholder' => "Saisir le modèle du véhicule",
                ]
            ])
            ->add(child: 'description', type: TextType::class, options: [
                'label' => "Description du véhicule :",
                'attr' => [
                    'placeholder' => "Saisir la description du véhicule",
                ]
            ])
            ->add(child: 'dailyPrice', type: MoneyType::class, options: [
                'label' => "Prix journalier du véhicule",
                'attr' => [
                    'placeholder' => "Saisir le prix journalier du véhicule",
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => in_array('create', $options['validation_groups'] ?? []),
                'allow_delete' => false,
                'label' => "Image du véhicule",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
