<?php

namespace App\Form;

use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use App\Services\Helpers\TranslationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                FormHelper::LABEL => 'Email',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                    FormHelper::ICON => IconHelper::USER,
                ],
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => "email.empty",
                    ]),
                    new Email([
                        'mode' => 'html5',
                        'message' => "email.notValid",
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                FormHelper::LABEL => 'password',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::ICON => IconHelper::PASSWORD],
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => 'password.empty',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'password.length',
                        'max' => 255,
                    ]),
                ],
            ])->add('_remember_me' . ($options['box'] ? '_box' : ''), CheckboxType::class, [
            FormHelper::LABEL => 'rememberMe',
            FormHelper::REQUIRED => false,
        ])->add('submit', SubmitType::class, [
            FormHelper::LABEL => 'login.button',
            FormHelper::ATTR => ['class' => 'btn btnCenter btnSecondary buttonMargin'],
            FormHelper::ROW_ATTR => [FormHelper::RESPONSIVE => 'cl-both w-100'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'box' => false,
            FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
            FormHelper::CSRF_PROTECTION => true,
            FormHelper::CSRF_NAME => 'csfr_login_token',
            FormHelper::CSRF_TOKEN_ID => 'login_authenticate_token_38d7f',
            //'data_class' => User::class,
        ]);
    }
}