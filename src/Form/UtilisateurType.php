<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'M.' => 'M.',
                    'Mme' => 'Mme'
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('promotion', EntityType::class, [
                'class' => Promotion::class,
                'choice_label' => function(Promotion $promotion) {
                    return $promotion->getAnnee() . ' ' . $promotion->getFormation()->getSpecialite();
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Formateur' => 'ROLE_FORMATEUR',
                    'Stagiaire' => 'ROLE_STAGIAIRE',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('plaintextpassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
