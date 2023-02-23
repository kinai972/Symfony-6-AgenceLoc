<?php

namespace App\Form\Front;

use App\Entity\Renting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RentingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'startsAt', type: DateType::class, options: [
                'label' => "Date de départ",
                'widget' => 'single_text',
            ])
            ->add(child: 'endsAt', type: DateType::class, options: [
                'label' => "Date de fin",
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Renting::class,
        ]);
    }
}
