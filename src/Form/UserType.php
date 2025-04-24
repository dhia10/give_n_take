<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Enter your email',
                    'class' => 'form-control',
                    'autocomplete' => 'email',
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Choose a username',
                    'class' => 'form-control',
                    'autocomplete' => 'username',
                ],
            ])
            ->add('phonenumber', TextType::class, [
                'label' => 'Phone Number',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Enter your phone number',
                    'class' => 'form-control',
                    'autocomplete' => 'tel',
                    'pattern' => '[0-9+()\- ]*',
                    'title' => 'Only numbers, +, (), - and spaces are allowed',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}