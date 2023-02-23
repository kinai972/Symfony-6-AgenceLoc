<?php

namespace App\Form\Front;

use App\Entity\Renting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'years' => range(2023, 2024),
            ])
            ->add(child: 'endsAt', type: DateTimeType::class, options: [
                'label' => "Date et Heure de fin",
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute', 'second' => 'Seconde',
                ],
                // 'widget' => 'choice',
                'years' => range(2023, 2024),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Renting::class,
        ]);
    }
}
