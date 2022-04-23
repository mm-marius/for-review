<?php

namespace App\Form;

use App\Services\Helpers\FormHelper;
use App\Services\Helpers\IconHelper;
use App\Services\Helpers\TranslationHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                'invalid_message' => 'errors.password.invalid_message',
                'options' => [
                    FormHelper::ATTR => [
                        'class' => 'password-field form-control',
                        FormHelper::RESPONSIVE => 'col-md-12 col-lg-12',
                        FormHelper::ICON => IconHelper::PASSWORD,
                    ],
                ],
                'required' => true,
                'first_options' => [
                    FormHelper::LABEL => 'fields.password',
                    FormHelper::ATTR => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        FormHelper::ICON => IconHelper::PASSWORD,
                    ],
                ],
                'second_options' => [
                    FormHelper::LABEL => 'fields.repeat_password',
                    FormHelper::ATTR => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                        FormHelper::ICON => IconHelper::PASSWORD,
                    ],
                ],
                FormHelper::ROW_ATTR => [
                    'class' => 'col-md-12 col-lg-12',
                ],
                'mapped' => false,
                FormHelper::CONSTRAINTS => [
                    new NotBlank([
                        'message' => $this->translator->trans('fields.passwordEmpty', [], 'security'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('fields.passwordLength', [], 'security'),
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                FormHelper::TRANSLATION_DOMAIN => TranslationHelper::LOGIN_DOMAIN,
                FormHelper::LABEL => 'resetPassword',
                FormHelper::ATTR => ['class' => 'btn btnCenter btnInfo buttonMargin',
                    FormHelper::RESPONSIVE => 'cl-both w-100 pt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}