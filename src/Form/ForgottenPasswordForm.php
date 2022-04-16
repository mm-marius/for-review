<?php

namespace App\Form;

use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use App\Services\Helpers\TranslationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgottenPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                FormHelper::LABEL => 'email',
                FormHelper::ATTR => [
                    'class' => 'form-control',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6',
                    FormHelper::ICON => IconHelper::EMAIL,
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
            ->add('submit', SubmitType::class, [
                FormHelper::LABEL => 'forgotPassword.button',
                FormHelper::ATTR => ['class' => 'btn btnCenter btnSecondary',
                    FormHelper::RESPONSIVE => 'col-md-12 col-lg-6'],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
            FormHelper::CSRF_PROTECTION => true,
            FormHelper::CSRF_NAME => 'csfr_forgotten_token',
            FormHelper::CSRF_TOKEN_ID => 'forgotten_password_token_d41d8',
            //'data_class' => User::class,
        ]);
    }
}