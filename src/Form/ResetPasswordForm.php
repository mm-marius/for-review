<?php

namespace App\Form;

use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use App\Services\Helpers\TranslationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                FormHelper::LABEL => 'password',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::PASSWORD],
                'mapped' => false,
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => 'password.empty',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'password.length',
                        // max length allowed by Symfony for security reasons
                        'max' => 255,
                    ]),
                ],
            ])->add('plainPasswordConfirm', PasswordType::class, [
            FormHelper::LABEL => 'passwordConfirm',
            FormHelper::ATTR => [
                'class' => 'form-control',
                FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                FormHelper::ICON => IconHelper::PASSWORD],
            'mapped' => false,
            FormHelper::CONSTRAINTS => [
                new NotBlank([
                    'message' => 'password.empty',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'password.length',
                    // max length allowed by Symfony for security reasons
                    'max' => 255,
                ]),
            ],
        ])->add('jwt', HiddenType::class, [
            FormHelper::LABEL => false,
        ])
            ->add('user', HiddenType::class, [
                FormHelper::REQUIRED => false,
                FormHelper::LABEL => false,
            ])
            ->add('submit', SubmitType::class, [
                FormHelper::LABEL => 'forgotPassword.button',
                FormHelper::ATTR => ['class' => 'btn btnCenter btnSecondary',
                    FormHelper::RESPONSIVE => 'col-md-12'],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
            FormHelper::CSRF_PROTECTION => true,
            FormHelper::CSRF_NAME => 'csfr_reset_password_token',
            FormHelper::CSRF_TOKEN_ID => 'reset_password_token_g72d9',
            //'data_class' => User::class,
        ]);
    }
}