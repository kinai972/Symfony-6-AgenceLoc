<?php

namespace App\Form\Admin;

use App\Entity\Renting;
use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RentingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'startsAt', type: DateTimeType::class, options: [
                'label' => "Date et Heure de départ",
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Seconde',
                ],
                // 'widget' => 'choice',
                'years' => range(2022, 2024),
            ])
            ->add(child: 'endsAt', type: DateTimeType::class, options: [
                'label' => "Date et Heure de fin",
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Seconde',
                ],
                // 'widget' => 'choice',
                'years' => range(2022, 2024),
            ])
            ->add(child: 'totalPrice', type: MoneyType::class, options: [
                'label' => "Prix total de la location",
                'attr' => [
                    'placeholder' => "Saisir le prix total de la location",
                ]
            ])
            ->add('user', EntityType::class, [
                'label' => "Membre",
                'class' => User::class,
            ])
            ->add('vehicle', EntityType::class, [
                'label' => "Véhicule à louer",
                'class' => Vehicle::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Renting::class,
        ]);
    }
}
