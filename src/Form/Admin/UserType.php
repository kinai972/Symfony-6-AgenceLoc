<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'email', type: TextType::class, options: [
                'label' => "Adresse électronique du membre :",
                'attr' => [
                    'placeholder' => "Saisir l'adresse électronique du membre",
                ]
            ])
            ->add(child: 'username', type: TextType::class, options: [
                'label' => "Pseudo du membre :",
                'attr' => [
                    'placeholder' => "Saisir le pseudo du membre",
                ]
            ])
            ->add(child: 'firstName', type: TextType::class, options: [
                'label' => "Prénom du membre :",
                'attr' => [
                    'placeholder' => "Saisir le prénom du membre",
                ]
            ])
            ->add(child: 'lastName', type: TextType::class, options: [
                'label' => "Nom du membre :",
                'attr' => [
                    'placeholder' => "Saisir le nom du membre",
                ]
            ])
            ->add(child: 'gender', type: ChoiceType::class, options: [
                'label' => "Statut du membre :",
                'choices' => [
                    'Homme' => 'm',
                    'Femme' => 'f',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe :',
                ],
                'second_options' => [
                    'label' => 'Confirmation mot de passe :',
                ],
                'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                'required' => false,
            ])
            ->add(child: 'roles', type: ChoiceType::class, options: [
                'label' => "Statut du membre :",
                'choices' => [
                    'Membre' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
