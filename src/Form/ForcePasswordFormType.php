<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ForcePasswordFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'mapped' => false,
                'required' => !$options['is_first_login'],
                'attr' => [
                    'autocomplete' => 'current-password',
                    'class' => 'form-control',
                ],
                'constraints' => $options['is_first_login'] ? [] : [
                    new NotBlank([
                        'message' => 'Please enter your current password',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'New Password',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirm New Password',
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password must be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                    new PasswordStrength([
                        'minScore' => PasswordStrength::STRENGTH_MEDIUM,
                        'message' => 'The password is too weak. Please use a stronger password with a mix of letters, numbers, and symbols.',
                    ]),
                    new NotCompromisedPassword([
                        'message' => 'This password has been leaked in a data breach. Please use a different password.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'is_first_login' => false,
        ]);
    }
}
