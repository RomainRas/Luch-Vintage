<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForgotPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Votre adresse email",
                'help' => "Vous recevrez votre nouveau mot de passe par email",
                'attr' => [
                    'placeholder' => "email@exemple.fr",
                    'class' => 'form-control reset-input'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réinitialiser',
                'attr' => [
                    'class' => 'btn btn-success reset-btn'
                ]
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=> User::class,
        ]);
    }
    
}
